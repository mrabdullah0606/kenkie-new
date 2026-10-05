<?php

namespace App\Http\Controllers;

use App\Models\BankOffer;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\HomeBanner;
use App\Models\HomeSetting;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function liveSearch(Request $request): JsonResponse
    {
        $query = trim((string) ($request->query('q') ?? $request->query('search') ?? ''));

        if (mb_strlen($query) < 1) {
            return response()->json([
                'products' => [],
                'total' => 0,
                'viewAllUrl' => route('shop.category'),
            ]);
        }

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhereHas('category', function ($catQuery) use ($query) {
                        $catQuery->where('name', 'like', "%{$query}%");
                    });
            })
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->take(8)
            ->get()
            ->map(function (Product $product) {
                $imageUrl = $product->image ? asset($product->image) : asset('assets/images/product/category/1.jpg');

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'url' => route('products.show', $product->slug),
                    'price' => number_format((float) $product->price, 2),
                    'image' => $imageUrl,
                    'category_name' => $product->category?->name,
                    'stock' => $product->stock,
                    'is_in_stock' => $product->stock > 0,
                ];
            });

        $totalCount = Product::query()
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhereHas('category', function ($catQuery) use ($query) {
                        $catQuery->where('name', 'like', "%{$query}%");
                    });
            })
            ->count();

        return response()->json([
            'products' => $products,
            'total' => $totalCount,
            'viewAllUrl' => route('shop.category', ['search' => $query]),
        ]);
    }

    public function home(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->with(['products' => fn ($q) => $q->where('is_active', true)->take(10)])
            ->orderBy('position')
            ->get();

        $categoriesBySlug = $categories->keyBy('slug');

        $homeSettings = HomeSetting::getSettings();
        try {
            $heroSlides = HeroSlide::query()->where('is_active', true)->orderBy('position')->get();
        } catch (\Throwable) {
            $heroSlides = collect();
        }
        $homeBanners = HomeBanner::query()->where('is_active', true)->orderBy('position')->get();
        $bankOffers = BankOffer::query()->where('is_active', true)->orderBy('position')->get();

        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->take(12)
            ->get();

        $topDealProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_top_deal', true)
            ->orderBy('name')
            ->take(12)
            ->get();

        if ($topDealProducts->isEmpty()) {
            $topDealProducts = $featuredProducts->take(6);
        }

        $hotDealProduct = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_hot_deal', true)
            ->first() ?? $topDealProducts->first() ?? $featuredProducts->first() ?? Product::query()->where('is_active', true)->first();

        $topSellingProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('is_top_deal')
            ->take(10)
            ->get();

        if ($topSellingProducts->isEmpty()) {
            $topSellingProducts = $featuredProducts->take(8);
        }

        $newArrivals = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->latest('id')
            ->take(10)
            ->get();

        $specialOffers = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereNotNull('regular_price')
            ->whereColumn('regular_price', '>', 'price')
            ->take(10)
            ->get();

        if ($specialOffers->isEmpty()) {
            $specialOffers = $topDealProducts->take(8);
        }

        return view('website.pages.home', [
            'categories' => $categories,
            'categoriesBySlug' => $categoriesBySlug,
            'homeSettings' => $homeSettings,
            'heroSlides' => $heroSlides,
            'homeBanners' => $homeBanners,
            'bankOffers' => $bankOffers,
            'featuredProducts' => $featuredProducts,
            'topDealProducts' => $topDealProducts,
            'topSellingProducts' => $topSellingProducts,
            'newArrivals' => $newArrivals,
            'specialOffers' => $specialOffers,
            'featuredDealProduct' => $hotDealProduct,
            'homeFurnitureProducts' => $categoriesBySlug->get('home-furniture-diy')?->products ?? collect(),
            'gardenPatioProducts' => $categoriesBySlug->get('garden-patio')?->products ?? collect(),
            'healthBeautyProducts' => $categoriesBySlug->get('health-beauty')?->products ?? collect(),
            'soundVisionProducts' => $categoriesBySlug->get('sound-vision')?->products ?? collect(),
            'techProducts' => $categoriesBySlug->get('computers-tablets-networking')?->products ?? collect(),
            'petProducts' => $categoriesBySlug->get('pet-supplies')?->products ?? collect(),
        ]);
    }

    public function category(Request $request): View
    {
        $categorySlug = $request->query('category');
        $selectedCategorySlugs = (array) ($request->query('categories') ?: ($categorySlug ? [$categorySlug] : []));
        $search = $request->query('search', $request->query('q'));
        $sort = $request->query('sort', 'featured');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $rating = $request->query('rating');
        $discount = $request->query('discount');

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('position')
            ->get();

        $selectedCategory = $categorySlug
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $productsQuery = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when(! empty($selectedCategorySlugs), function ($query) use ($selectedCategorySlugs) {
                $query->where(function ($q) use ($selectedCategorySlugs) {
                    $q->whereHas('category', function ($catQ) use ($selectedCategorySlugs) {
                        $catQ->whereIn('slug', $selectedCategorySlugs)
                            ->orWhereHas('parent', fn ($pQ) => $pQ->whereIn('slug', $selectedCategorySlugs));
                    });
                });
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when(is_numeric($minPrice), fn ($query) => $query->where('price', '>=', (float) $minPrice))
            ->when(is_numeric($maxPrice), fn ($query) => $query->where('price', '<=', (float) $maxPrice));

        match ($sort) {
            'low' => $productsQuery->orderBy('price', 'asc'),
            'high' => $productsQuery->orderBy('price', 'desc'),
            'aToz' => $productsQuery->orderBy('name', 'asc'),
            'zToa' => $productsQuery->orderBy('name', 'desc'),
            'popular' => $productsQuery->orderByDesc('is_featured')->orderByDesc('id'),
            default => $productsQuery->orderByDesc('is_featured')->orderBy('name', 'asc'),
        };

        $products = $productsQuery->paginate(12)->withQueryString();

        $priceMinBound = (float) (Product::query()->where('is_active', true)->min('price') ?? 0);
        $priceMaxBound = (float) (Product::query()->where('is_active', true)->max('price') ?? 500);

        return view('website.pages.category', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $selectedCategory,
            'selectedCategories' => $selectedCategorySlugs,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'priceMinBound' => $priceMinBound,
            'priceMaxBound' => max($priceMaxBound, 100),
            'rating' => $rating,
            'discount' => $discount,
        ]);
    }

    public function featuredProduct(): RedirectResponse
    {
        $product = Product::query()->where('is_active', true)->orderByDesc('is_featured')->first();

        return $product
            ? redirect()->route('products.show', $product->slug)
            : redirect()->route('shop.category');
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('website.pages.product', [
            'product' => $product->load(['category', 'activeVariations']),
            'relatedProducts' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->orderByDesc('is_featured')
                ->take(4)
                ->get(),
        ]);
    }

    public function about(): View
    {
        return view('website.content.about-us');
    }

    public function privacyPolicy(): View
    {
        return view('website.content.privacy-policy');
    }

    public function returnPolicy(): View
    {
        return view('website.content.return-policy');
    }

    public function contact(): View
    {
        return view('website.content.contact-us');
    }
}
