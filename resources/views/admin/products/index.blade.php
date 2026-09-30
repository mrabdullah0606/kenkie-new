@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Products</h1>
        <a class="btn btn-primary" href="{{ route('admin.products.create') }}">Add product</a>
    </div>
    @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}<div class="small text-muted">{{ $product->sku }}</div></td>
                        <td>{{ $product->category->name }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->is_active ? 'Active' : 'Hidden' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No products yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links('pagination::bootstrap-5') }}
@endsection