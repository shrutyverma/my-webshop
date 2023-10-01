@extends('layouts.app')

@section('content')
<h1>{{ isset($order) ? 'Edit Order' : 'Create Order' }}</h1>
<form action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}" method="POST">
    @csrf
    @if(isset($order))
        @method('PUT')
    @endif
    <label for="customer_id">Customer:</label>
    <select name="customer_id" id="customer_id">
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}" {{ isset($order) && $order->customer_id === $customer->id ? 'selected' : '' }}>
                {{ $customer->first_name }} {{ $customer->last_name }}
            </option>
        @endforeach
    </select>
    <label for="payed">Payed:</label>
    <input type="hidden" name="payed" value="0"> <!-- Hidden input for '0' (unchecked) -->
    <input type="checkbox" name="payed" id="payed" value="1" {{ isset($order) && $order->payed == 1 ? 'checked' : '' }}>
    <button type="submit" class="btn btn-primary">{{ isset($order) ? 'Update' : 'Create' }}</button>
</form>
<a href="{{ route('orders.index') }}">Back to Orders</a>
@endsection
