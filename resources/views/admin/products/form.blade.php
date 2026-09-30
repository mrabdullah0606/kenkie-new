@extends('admin.layouts.app')

@section('title', $product->exists ? 'Edit product' : 'Add product')

@section('content')
    <h1 class="h3 mb-4">{{ $product->exists ? 'Edit product' : 'Add product' }}</h1>
    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="border rounded p-3 p-md-4">
        @csrf
        @if ($product->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="slug">Slug</label>
                <input class="form-control" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required>
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="sku">SKU</label>
                <input class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                @error('sku')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="category_id">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Choose category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="price">Price</label>
                <input class="form-control" id="price" type="number" name="price" min="0.01" step="0.01" value="{{ old('price', $product->price) }}" required>
                @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="stock">Stock</label>
                <input class="form-control" id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
                @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="unit">Unit</label>
                <input class="form-control" id="unit" name="unit" value="{{ old('unit', $product->unit ?? 'each') }}" required>
                @error('unit')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="image">Image path under public/</label>
                <input class="form-control" id="image" name="image" value="{{ old('image', $product->image) }}" placeholder="assets/images/vegetable/product/1.png">
                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 d-flex gap-4">
                <label class="form-check-label"><input class="form-check-input me-2" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>Featured</label>
                <label class="form-check-label"><input class="form-check-input me-2" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>Visible in store</label>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $product->exists ? 'Save changes' : 'Create product' }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Cancel</a>
        </div>
    </form>
@endsection