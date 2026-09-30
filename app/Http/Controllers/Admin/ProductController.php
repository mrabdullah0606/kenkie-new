<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with('category');

        $search = $request->string('search')->trim()->value();
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categoryId = $request->input('category_id');
        if (! empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $status = $request->input('status');
        if (! empty($status)) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'hot_deal') {
                $query->where('is_hot_deal', true);
            } elseif ($status === 'top_deal') {
                $query->where('is_top_deal', true);
            } elseif ($status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        return view('admin.products.index', [
            'products' => $query->latest()->paginate(20)->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedStatus' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::query()->create($this->validatedData($request));

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedData($request, $product));

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('products', 'slug')->ignore($product)],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product)],
            'description' => ['nullable', 'string', 'max:50000'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'unit' => ['required', 'string', 'max:80'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'remove_primary_image' => ['sometimes', 'boolean'],
            'keep_gallery_images' => ['nullable', 'array'],
            'keep_gallery_images.*' => ['string'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:10240'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_top_deal' => ['sometimes', 'boolean'],
            'is_hot_deal' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = 'storage/'.$path;
        } elseif ($request->boolean('remove_primary_image')) {
            $validated['image'] = null;
        }

        // Gallery handling: keep chosen existing images and append newly uploaded files
        $galleryPaths = [];
        if ($request->has('keep_gallery_images')) {
            $keepImages = $request->input('keep_gallery_images', []);
            $currentImages = is_array($product?->images) ? $product->images : [];
            foreach ($keepImages as $img) {
                if (in_array($img, $currentImages, true)) {
                    $galleryPaths[] = $img;
                }
            }
        } elseif ($product && ! $request->hasFile('gallery_files') && ! $request->has('remove_all_gallery')) {
            $galleryPaths = is_array($product->images) ? $product->images : [];
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products/gallery', 'public');
                $galleryPaths[] = 'storage/'.$path;
            }
        }

        $validated['images'] = $galleryPaths;

        unset($validated['image_file'], $validated['gallery_files'], $validated['remove_primary_image'], $validated['keep_gallery_images']);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_top_deal'] = $request->boolean('is_top_deal');
        $validated['is_hot_deal'] = $request->boolean('is_hot_deal');
        $validated['is_active'] = $request->boolean('is_active', $product?->is_active ?? true);

        return $validated;
    }
}
