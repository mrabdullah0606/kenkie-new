@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Categories Management</h3>
            <p class="text-muted mb-0">Organize and structure store departments and collections.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Category
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold mb-0">All Categories ({{ $categories->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Products Count</th>
                            <th>Sort Position</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($category->image)
                                            <img src="{{ asset($category->image) }}" class="rounded-3 border" style="width: 40px; height: 40px; object-fit: contain;" alt="{{ $category->name }}">
                                        @else
                                            <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-folder"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $category->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-muted">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                                        {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-muted">#{{ $category->position }}</span>
                                </td>
                                <td>
                                    @if ($category->is_active)
                                        <span class="badge bg-success px-2 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">Hidden</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit Category">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Category">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-tags fs-2 text-muted mb-2 d-block"></i>
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-transparent border-top py-3">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
