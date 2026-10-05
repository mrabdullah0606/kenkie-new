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
        $query = Product::query()->with(['category', 'variations']);

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
            } elseif ($status === 'low_stock') {
                $query->where('stock', '<=', 5);
            } elseif ($status === 'hot_deal') {
                $query->where('is_hot_deal', true);
            } elseif ($status === 'top_deal') {
                $query->where('is_top_deal', true);
            } elseif ($status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $lowStockCount = Product::query()->where('stock', '<=', 5)->count();

        return view('admin.products.index', [
            'products' => $query->latest()->paginate(20)->withQueryString(),
            'categories' => Category::query()->with('parent')->orderBy('name')->get(),
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedStatus' => $status,
            'lowStockCount' => $lowStockCount,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::query()->with('parent')->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $product = Product::query()->create($data);

        $this->syncVariations($product, $request->input('variations', []));
        $this->syncOffers($product, $request->input('offers', []));

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load(['variations', 'offers']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->with('parent')->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product);
        $product->update($data);

        $this->syncVariations($product, $request->input('variations', []));
        $this->syncOffers($product, $request->input('offers', []));

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    public function duplicate(Product $product): RedirectResponse
    {
        $product->load('variations');

        $clone = $product->replicate(['slug', 'sku']);
        $clone->name = $product->name.' (Copy)';
        $clone->slug = $product->slug.'-copy-'.time();
        $clone->sku = $product->sku.'-COPY-'.rand(100, 999);
        $clone->is_active = false;
        $clone->save();

        foreach ($product->variations as $variation) {
            $cloneVariation = $variation->replicate(['sku']);
            $cloneVariation->product_id = $clone->id;
            if ($variation->sku) {
                $cloneVariation->sku = $variation->sku.'-COPY-'.rand(10, 99);
            }
            $cloneVariation->save();
        }

        foreach ($product->offers as $offer) {
            $cloneOffer = $offer->replicate();
            $cloneOffer->product_id = $clone->id;
            $cloneOffer->save();
        }

        return redirect()->route('admin.products.edit', $clone)->with('status', 'Product duplicated successfully. You are now editing the duplicate.');
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        $statusMsg = $product->is_active ? 'Product is now active.' : 'Product is now hidden/inactive.';

        return back()->with('status', $statusMsg);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    /**
     * @param  array<int, array<string, mixed>>  $variationsData
     */
    private function syncVariations(Product $product, array $variationsData): void
    {
        $validVariationIds = [];

        foreach ($variationsData as $row) {
            if (empty($row['name']) && empty($row['size']) && empty($row['color']) && empty($row['sku'])) {
                continue;
            }

            $variationAttributes = [
                'name' => $row['name'] ?? null,
                'color' => $row['color'] ?? null,
                'size' => $row['size'] ?? null,
                'dimensions' => $row['dimensions'] ?? null,
                'material' => $row['material'] ?? null,
                'sku' => $row['sku'] ?? null,
                'cost_price' => ! empty($row['cost_price']) ? (float) $row['cost_price'] : null,
                'regular_price' => ! empty($row['regular_price']) ? (float) $row['regular_price'] : null,
                'sale_price' => ! empty($row['sale_price']) ? (float) $row['sale_price'] : null,
                'stock' => isset($row['stock']) ? (int) $row['stock'] : 0,
                'is_active' => isset($row['is_active']) ? (bool) $row['is_active'] : true,
            ];

            if (! empty($row['id'])) {
                $existing = $product->variations()->find($row['id']);
                if ($existing) {
                    $existing->update($variationAttributes);
                    $validVariationIds[] = $existing->id;

                    continue;
                }
            }

            $created = $product->variations()->create($variationAttributes);
            $validVariationIds[] = $created->id;
        }

        // Delete any variations that were removed in the form
        $product->variations()->whereNotIn('id', $validVariationIds)->delete();
    }

    /**
     * @param  array<int, array<string, mixed>>  $offersData
     */
    private function syncOffers(Product $product, array $offersData): void
    {
        $validOfferIds = [];

        foreach ($offersData as $row) {
            if (empty($row['min_quantity']) || empty($row['discount_percentage'])) {
                continue;
            }

            $offerAttributes = [
                'title' => $row['title'] ?? null,
                'min_quantity' => max(2, (int) $row['min_quantity']),
                'discount_percentage' => (float) $row['discount_percentage'],
                'badge_label' => $row['badge_label'] ?? null,
                'is_active' => isset($row['is_active']) ? (bool) $row['is_active'] : true,
                'starts_at' => ! empty($row['starts_at']) ? $row['starts_at'] : null,
                'ends_at' => ! empty($row['ends_at']) ? $row['ends_at'] : null,
            ];

            if (! empty($row['id'])) {
                $existing = $product->offers()->find($row['id']);
                if ($existing) {
                    $existing->update($offerAttributes);
                    $validOfferIds[] = $existing->id;

                    continue;
                }
            }

            $created = $product->offers()->create($offerAttributes);
            $validOfferIds[] = $created->id;
        }

        $product->offers()->whereNotIn('id', $validOfferIds)->delete();
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
            'regular_price' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'cost_price' => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'unit' => ['required', 'string', 'max:80'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'size_chart_file' => ['nullable', 'image', 'max:10240'],
            'remove_primary_image' => ['sometimes', 'boolean'],
            'remove_size_chart' => ['sometimes', 'boolean'],
            'keep_gallery_images' => ['nullable', 'array'],
            'keep_gallery_images.*' => ['string'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:10240'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_top_deal' => ['sometimes', 'boolean'],
            'is_hot_deal' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = 'storage/'.$path;
        } elseif ($request->boolean('remove_primary_image')) {
            $validated['image'] = null;
        }

        if ($request->hasFile('size_chart_file')) {
            $path = $request->file('size_chart_file')->store('products/size_charts', 'public');
            $validated['size_chart'] = 'storage/'.$path;
        } elseif ($request->boolean('remove_size_chart')) {
            $validated['size_chart'] = null;
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

        unset($validated['image_file'], $validated['size_chart_file'], $validated['gallery_files'], $validated['remove_primary_image'], $validated['remove_size_chart'], $validated['keep_gallery_images']);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_top_deal'] = $request->boolean('is_top_deal');
        $validated['is_hot_deal'] = $request->boolean('is_hot_deal');
        $validated['is_active'] = $request->boolean('is_active', $product?->is_active ?? true);

        return $validated;
    }
}
