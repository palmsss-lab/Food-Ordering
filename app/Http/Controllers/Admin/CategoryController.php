<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('menuItems')
            ->orderBy('name')
            ->paginate(15);

        $archivedCategories = Category::onlyTrashed()->withCount('menuItems')->orderBy('deleted_at', 'desc')->get();

        return view('admin.categories.index', compact('categories', 'archivedCategories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Cascade: archive all active menu items in this category
        $itemCount = $category->menuItems()->count();
        $category->menuItems()->each(fn($item) => $item->delete());
        $category->delete();

        $msg = "\"{$category->name}\" has been archived.";
        if ($itemCount > 0) {
            $msg .= " {$itemCount} menu item(s) were also archived.";
        }

        return redirect()->route('admin.categories.index')->with('success', $msg);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        // Cascade: restore menu items that belong to this category and are archived
        $itemCount = MenuItem::onlyTrashed()->where('categories_id', $category->id)->count();
        MenuItem::onlyTrashed()->where('categories_id', $category->id)->each(fn($item) => $item->restore());

        $msg = "\"{$category->name}\" has been restored.";
        if ($itemCount > 0) {
            $msg .= " {$itemCount} menu item(s) were also restored.";
        }

        return redirect()->route('admin.categories.index')->with('success', $msg);
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $name = $category->name;

        // Also force-delete archived menu items under this category
        MenuItem::onlyTrashed()->where('categories_id', $category->id)->each(fn($item) => $item->forceDelete());
        $category->forceDelete();

        return redirect()->route('admin.categories.index')
            ->with('success', "\"{$name}\" has been permanently deleted along with its archived menu items.");
    }
}