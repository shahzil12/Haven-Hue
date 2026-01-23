@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1>Users Management</h1>
    <table class="table table-bordered bg-white shadow-sm mt-4" id="usersTable">
        <thead class="table-light">
            <tr>
                <th class="ps-4">ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined Date</th>
                <th class="text-end pe-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="ps-4">{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role == 'admin')
                        <span class="badge bg-primary">Admin</span>
                    @else
                        <span class="badge bg-secondary">Buyer</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td class="text-end pe-4">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable();
    });
</script>
@endpush
