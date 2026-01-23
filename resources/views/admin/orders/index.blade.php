@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Orders</h1>
    </div>

    <table class="table table-bordered bg-white shadow-sm mt-4" id="ordersTable">
        <thead class="bg-light">
            <tr>
                <th class="ps-4">Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th class="text-end pe-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="ps-4 fw-bold">{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($order->status == 'completed' || $order->status == 'delivered')
                            <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                        @elseif($order->status == 'pending_verification')
                            <span class="badge bg-warning text-dark">Pending Verification</span>
                        @elseif($order->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary-custom">View Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            "order": [[ 2, "desc" ]] // Sort by Date column desc by default
        });
    });
</script>
@endpush
