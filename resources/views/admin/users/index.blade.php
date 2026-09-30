@extends('admin.layouts.app')

@section('title', 'Users & Customers')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Users & Customers</h3>
            <p class="text-muted mb-0">Manage registered customers and store administrators.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Search filter -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 justify-content-between flex-wrap">
                <div class="d-flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search by name or email..." style="width: 280px;">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
                    @if ($search)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Registered Users ({{ $users->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User Profile</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Total Orders</th>
                            <th>Registered Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 14px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $user->name }}</span>
                                            @if ($user->id === auth()->id())
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0" style="font-size: 10px;">Current User</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark">{{ $user->email }}</span>
                                </td>
                                <td>
                                    @if ($user->isAdmin())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-semibold">
                                            <i class="fa-solid fa-shield-halved me-1"></i> Admin
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-1">
                                            <i class="fa-solid fa-user me-1"></i> Customer
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                                        {{ $user->orders_count }} {{ Str::plural('order', $user->orders_count) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $user->created_at?->format('M d, Y') }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit User">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-users fs-2 text-muted mb-2 d-block"></i>
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-transparent border-top py-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
