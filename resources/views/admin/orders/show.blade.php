@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Order #{{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
    </div>

    <div class="row">
        <!-- Main Order Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless align-middle">
                        <thead class="text-muted small border-bottom">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                            @else
                                                <div class="rounded me-3 d-flex align-items-center justify-content-center bg-light text-muted" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->category->name ?? 'Uncategorized' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="3" class="text-end pt-3">Subtotal</td>
                                <td class="text-end pt-3">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5">Total</td>
                                <td class="text-end fw-bold fs-5">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar: Status & Customer Info -->
        <div class="col-lg-4">
            <!-- Status Update Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label text-muted">Current Status</label>
                            <select name="status" class="form-select">
                                <option value="pending_verification" {{ $order->status == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                                <option value="verified" {{ $order->status == 'verified' ? 'selected' : '' }}>Verified (Processing)</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $order->user->name ?? 'Guest User' }}</h6>
                            <small class="text-muted">{{ $order->user->email ?? 'No Email' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            @php
                $address = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address;
            @endphp
            @if($address)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <!-- Display Name if available explicitly, or fallback to User name -->
                    <p class="mb-1 fw-bold">{{ $order->user->name ?? 'Guest' }}</p>
                    
                    <p class="mb-1">{{ $address['address'] ?? '' }}</p>
                    
                    <!-- City, State, Zip, Country -->
                    <p class="mb-1">
                        {{ $address['city'] ?? '' }}
                        {{ isset($address['state']) ? ', ' . $address['state'] : '' }}
                        {{ $address['zip'] ?? '' }}
                    </p>
                    <p class="mb-0">{{ $address['country'] ?? '' }}</p>
                    <hr>
                    <p class="mb-0 text-muted"><i class="fas fa-phone me-2"></i> {{ $address['phone'] ?? 'N/A' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
