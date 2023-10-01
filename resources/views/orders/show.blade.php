@extends('layouts.app')

@section('content')
<h1>Order Details</h1>
<p>ID: {{ $order->id }}</p>
<p>Customer: {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
<p>Payed: {{ $order->payed ? 'Yes' : 'No' }}</p>
<a href="{{ route('orders.index') }}">Back to Orders</a>

@endsection
