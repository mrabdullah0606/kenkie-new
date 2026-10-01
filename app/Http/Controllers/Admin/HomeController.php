<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\HomeBanner;
use App\Models\HomeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = HomeSetting::getSettings();
        $banners = HomeBanner::query()->orderBy('position')->get();
        try {
            $slides = HeroSlide::query()->orderBy('position')->get();
        } catch (\Throwable) {
            $slides = collect();
        }

        return view('admin.home.index', [
            'settings' => $settings,
            'banners' => $banners,
            'slides' => $slides,
        ]);
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'hero_button_text' => ['nullable', 'string', 'max:100'],
            'hero_button_url' => ['nullable', 'string', 'max:255'],
            'hero_image_file' => ['nullable', 'image', 'max:10240'],
        ]);

        $settings = HomeSetting::getSettings();

        if ($request->hasFile('hero_image_file')) {
            $path = $request->file('hero_image_file')->store('banners', 'public');
            $validated['hero_image'] = 'storage/'.$path;
        }

        unset($validated['hero_image_file']);

        $settings->update($validated);

        return redirect()->route('admin.home.index')->with('status', 'Hero banner settings updated successfully.');
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'banner_image_file' => ['nullable', 'image', 'max:10240'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('banner_image_file')) {
            $path = $request->file('banner_image_file')->store('banners', 'public');
            $validated['image'] = 'storage/'.$path;
        } else {
            $validated['image'] = 'assets/images/banner/kenkie-promo-home.jpg';
        }

        unset($validated['banner_image_file']);
        $validated['position'] = (int) ($validated['position'] ?? HomeBanner::query()->count());
        $validated['is_active'] = true;

        HomeBanner::query()->create($validated);

        return redirect()->route('admin.home.index')->with('status', 'Promo banner card added successfully.');
    }

    public function updateBanner(Request $request, HomeBanner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'banner_image_file' => ['nullable', 'image', 'max:10240'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('banner_image_file')) {
            $path = $request->file('banner_image_file')->store('banners', 'public');
            $validated['image'] = 'storage/'.$path;
        }

        unset($validated['banner_image_file']);
        $validated['is_active'] = $request->boolean('is_active');

        $banner->update($validated);

        return redirect()->route('admin.home.index')->with('status', 'Promo banner card updated successfully.');
    }

    public function destroyBanner(HomeBanner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('admin.home.index')->with('status', 'Promo banner card removed successfully.');
    }

    public function storeSlide(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'slide_image_file' => ['nullable', 'image', 'max:10240'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('slide_image_file')) {
            $path = $request->file('slide_image_file')->store('banners', 'public');
            $validated['image'] = 'storage/'.$path;
        } else {
            $validated['image'] = 'assets/images/banner/kenkie-hero-banner.jpg';
        }

        unset($validated['slide_image_file']);
        $validated['position'] = (int) ($validated['position'] ?? HeroSlide::query()->count());
        $validated['is_active'] = true;

        HeroSlide::query()->create($validated);

        return redirect()->route('admin.home.index')->with('status', 'Hero slide added successfully.');
    }

    public function updateSlide(Request $request, HeroSlide $slide): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'slide_image_file' => ['nullable', 'image', 'max:10240'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('slide_image_file')) {
            $path = $request->file('slide_image_file')->store('banners', 'public');
            $validated['image'] = 'storage/'.$path;
        }

        unset($validated['slide_image_file']);
        $validated['is_active'] = $request->boolean('is_active');

        $slide->update($validated);

        return redirect()->route('admin.home.index')->with('status', 'Hero slide updated successfully.');
    }

    public function destroySlide(HeroSlide $slide): RedirectResponse
    {
        $slide->delete();

        return redirect()->route('admin.home.index')->with('status', 'Hero slide removed successfully.');
    }
}
