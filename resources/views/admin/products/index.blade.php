@extends('admin.layouts.app')

@section('title', 'All Products')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Products Management</h3>
            <p class="text-muted mb-0">Manage and update your store catalog items.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, SKU, description..." value="{{ $search ?? '' }}" autofocus>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string)($selectedCategory ?? '') === (string)$cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="active" {{ ($selectedStatus ?? '') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ ($selectedStatus ?? '') === 'inactive' ? 'selected' : '' }}>Hidden / Inactive</option>
                        <option value="low_stock" {{ ($selectedStatus ?? '') === 'low_stock' ? 'selected' : '' }}>⚠️ Low Stock ({{ $lowStockCount ?? 0 }})</option>
                        <option value="hot_deal" {{ ($selectedStatus ?? '') === 'hot_deal' ? 'selected' : '' }}>🔥 Hot Deals</option>
                        <option value="top_deal" {{ ($selectedStatus ?? '') === 'top_deal' ? 'selected' : '' }}>⚡ Top Deals</option>
                        <option value="featured" {{ ($selectedStatus ?? '') === 'featured' ? 'selected' : '' }}>⭐ Featured</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-12 d-flex gap-2 justify-content-lg-end">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-filter"></i> Search
                    </button>
                    @if (!empty($search) || !empty($selectedCategory) || !empty($selectedStatus))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1" title="Clear Filters">
                            <i class="fa-solid fa-xmark"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
            <div>
                <h5 class="fw-bold mb-0">
                    Products List
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1">{{ $products->total() }}</span>
                </h5>
                @if (!empty($search))
                    <small class="text-muted">Showing results for "<strong>{{ $search }}</strong>"</small>
                @endif
            </div>
            <div class="d-flex gap-2">
                @if (($lowStockCount ?? 0) > 0 && ($selectedStatus ?? '') !== 'low_stock')
                    <a href="{{ route('admin.products.index', ['status' => 'low_stock']) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $lowStockCount }} Low Stock Alert
                    </a>
                @endif
                <a href="{{ route('shop.category') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Preview Storefront
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product Image & Name</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Promotions</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset($product->image ?: 'assets/images/product/category/1.jpg') }}" class="rounded-3 border" style="width: 44px; height: 44px; object-fit: contain;" alt="{{ $product->name }}">
                                        <div>
                                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="fw-bold text-dark text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                            <small class="text-muted d-block">{{ $product->unit }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($product->category)
                                        <span class="badge bg-light text-dark border">
                                            @if ($product->category->parent)
                                                {{ $product->category->parent->name }} &gt; {{ $product->category->name }}
                                            @else
                                                {{ $product->category->name }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <code class="text-muted">{{ $product->sku }}</code>
                                </td>
                                <td>
                                    @if ($product->regular_price && (float) $product->regular_price > (float) $product->price)
                                        <div>
                                            <span class="fw-bold text-success">${{ number_format((float) $product->price, 2) }}</span>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1" style="font-size: 10px;">-{{ $product->discount_percentage }}%</span>
                                        </div>
                                        <del class="text-muted small">${{ number_format((float) $product->regular_price, 2) }}</del>
                                    @else
                                        <span class="fw-bold text-dark">${{ number_format((float) $product->price, 2) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->stock <= 0)
                                        <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Out of Stock</span>
                                    @elseif ($product->stock <= 5)
                                        <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Low: {{ $product->stock }}</span>
                                    @elseif ($product->stock <= 15)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">{{ $product->stock }} left</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">{{ $product->stock }} in stock</span>
                                    @endif

                                    @if ($product->variations && $product->variations->isNotEmpty())
                                        <small class="d-block text-muted mt-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-layer-group me-1"></i> {{ $product->variations->count() }} {{ Str::plural('variation', $product->variations->count()) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if ($product->is_hot_deal)
                                            <span class="badge bg-danger" title="Hot Deal (Special Offer)"><i class="fa-solid fa-fire me-1"></i> Hot Deal</span>
                                        @endif
                                        @if ($product->is_top_deal)
                                            <span class="badge bg-primary" title="Top Deals Grid"><i class="fa-solid fa-bolt me-1"></i> Top Deal</span>
                                        @endif
                                        @if ($product->is_featured)
                                            <span class="badge bg-warning text-dark" title="Featured"><i class="fa-solid fa-star me-1"></i> Featured</span>
                                        @endif
                                        @if (! $product->is_featured && ! $product->is_top_deal && ! $product->is_hot_deal)
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($product->is_active)
                                        <span class="badge bg-success px-2 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">Hidden</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('products.show', $product->slug) }}" target="_blank">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i> View in Store
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Product
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.products.duplicate', $product) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fa-solid fa-copy me-2 text-secondary"></i> Duplicate Product
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        @if ($product->is_active)
                                                            <i class="fa-solid fa-eye-slash me-2 text-warning"></i> Set as Inactive / Hide
                                                        @else
                                                            <i class="fa-solid fa-circle-check me-2 text-success"></i> Set as Active / Publish
                                                        @endif
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa-solid fa-trash-can me-2"></i> Delete Product
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-boxes-stacked fs-2 text-muted mb-2 d-block"></i>
                                    @if (!empty($search) || !empty($selectedCategory) || !empty($selectedStatus))
                                        <p class="mb-2">No products matched your search / filter criteria.</p>
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                                        </a>
                                    @else
                                        No products found in the catalog.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($products->hasPages())
            <div class="card-footer bg-transparent border-top py-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection