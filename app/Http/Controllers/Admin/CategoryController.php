<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->with('parent')
                ->withCount(['products', 'children'])
                ->orderBy('position')
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category,
            'parentCategories' => Category::query()->whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::query()->create($this->validatedData($request));

        return redirect()->route('admin.categories.index')->with('status', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', [
            'category' => $category,
            'parentCategories' => Category::query()
                ->whereNull('parent_id')
                ->where('id', '!=', $category->id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validatedData($request, $category));

        return redirect()->route('admin.categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Category deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Category $category = null): array
    {
        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) use ($category) {
                    if ($category) {
                        $query->where('id', '!=', $category->id);
                    }
                }),
            ],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('categories', 'slug')->ignore($category)],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'position' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('categories', 'public');
            $validated['image'] = 'storage/'.$path;
        }

        unset($validated['image_file']);

        $validated['is_active'] = $request->boolean('is_active', $category?->is_active ?? true);

        return $validated;
    }
}
