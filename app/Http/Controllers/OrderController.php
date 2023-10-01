<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http; 

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the create order form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all(); 

        return view('orders.create', compact('customers'));

    }

    /**
     * Store a newly created order in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data (you can define validation rules)
        $validatedData = $request->validate([
            'customer_id' => 'required|integer',
            'payed' => 'boolean',
        ]);
        // Create a new order
        $order = Order::create($validatedData);

        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Display the edit order form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);

        // Fetch the customers from your database or another source
        $customers = Customer::all(); // Assuming you have a Customer model
        return view('orders.edit', compact('order', 'customers'));
    }

    /**
     * Update the specified order in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data (you can define validation rules)
        $validatedData = $request->validate([
            'customer_id' => 'required|integer',
            'payed' => 'boolean',
        ]);
        $order = Order::findOrFail($id);
        $order->update($validatedData);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified order from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
    
    public function addProduct($id, Request $request)
    {
        // Find the order by ID
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check if the order is already paid
        if ($order->payed) {
            return response()->json(['message' => 'Order is already paid and cannot be modified'], 400);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        // Find the product by ID
        $productId = $request->input('product_id');
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Attach the product to the order
        $order->products()->attach($productId);

        return response()->json(['message' => 'Product attached to the order successfully']);
    }
    public function payOrder($id, Request $request)
    {
        // Find the order by ID
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check if the order is already paid
        if ($order->payed) {
            return response()->json(['message' => 'Order is already paid'], 400);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'customer_email' => 'required|email',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        // Send a request to the Super Payment Provider
        $response = Http::post('https://superpay.view.agentur-loop.com/pay', [
            'order_id' => $request->input('order_id'),
            'customer_email' => $request->input('customer_email'),
            'value' => $request->input('value'),
        ]);

        // Check the payment response
        if ($response->successful()) {
            // Payment successful, mark the order as paid
            $order->update(['payed' => true]);

            return response()->json(['message' => 'Payment Successful']);
        } else {
            // Payment failed
            return response()->json(['message' => 'Payment Failed'], 400);
        }
    }
}
