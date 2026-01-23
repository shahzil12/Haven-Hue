@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom">Add New Product</a>
    </div>
    
    <table class="table table-bordered bg-white shadow-sm mt-4" id="productsTable">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'https://placehold.co/50x50' }}" width="50" height="50" class="img-thumbnail">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>Rs. {{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#productsTable').DataTable();
    });
</script>
@endpush
