@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Edit Category</h1>
    
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary-custom">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
