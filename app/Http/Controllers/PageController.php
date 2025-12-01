<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function page_index()
    {
        return view('admin.utilities.pages.index', [
            'pages' => Page::orderBy('route_name')->get()
        ]);
    }

    public function page_create()
    {
        // Just render the create view (no $page passed)
        return view('admin.utilities.pages.create');
    }

    public function page_store(Request $request)
    {
        $data = $request->validate([
            'route_name'       => 'required|string',
            'slug'             => 'required|string',
            'title'            => 'nullable|string',
            'h1'               => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'banner_image'     => 'nullable|image|max:5120',
        ]);

        // JSON → array
        $raw = (string) $request->input('settings_json', '');
        $settings = [];
        if ($raw !== '') {
            $settings = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['settings_json' => 'Settings JSON is invalid: '.json_last_error_msg()]);
            }
        }
        $data['settings'] = $settings;

        // Checkbox normalize
        $data['is_active'] = $request->boolean('is_active');

        // Handle banner upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('banners', 'public');
            $data['banner_image'] = $path; // store path; accessor makes URL
        }

        $page = Page::create($data);

        return redirect()
            ->route('utilities.page_edit', $page)
            ->with('success', 'Page created.');
    }

    public function page_edit(Page $page)
    {
        return view('admin.utilities.pages.edit', compact('page'));
    }

    public function page_update(Page $page, Request $request)
    {
        $data = $request->validate([
            'route_name'       => 'required|string',
            'slug'             => 'required|string',
            'title'            => 'nullable|string',
            'h1'               => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'banner_image'     => 'nullable|image|max:5120',
        ]);

        // JSON → array
        $raw = (string) $request->input('settings_json', '');
        $settings = [];
        if ($raw !== '') {
            $settings = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['settings_json' => 'Settings JSON is invalid: '.json_last_error_msg()]);
            }
        }
        $data['settings'] = $settings;

        // Checkbox normalize
        $data['is_active'] = $request->boolean('is_active');

        // Handle banner upload (and delete old)
        if ($request->hasFile('banner_image')) {
            if ($page->banner_image && Storage::disk('public')->exists($page->banner_image)) {
                Storage::disk('public')->delete($page->banner_image);
            }
            $path = $request->file('banner_image')->store('banners', 'public');
            $data['banner_image'] = $path;
        }

        $page->update($data);

        return redirect()
            ->route('utilities.page_edit', $page)
            ->with('success', 'Page updated.');
    }
}
