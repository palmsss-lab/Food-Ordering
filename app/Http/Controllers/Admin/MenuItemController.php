<?php
// app/Http/Controllers/Admin/MenuItemController.php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\MenuItem;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Display a listing of menu items.
     */
    public function index(Request $request)
    {
        // Start with query builder
        $query = MenuItem::with('category');
        
        // Search with index-friendly query
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', $searchTerm . '%')
                  ->orWhere('name', 'LIKE', '% ' . $searchTerm . '%');
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('categories_id', $request->category);
        }
        
        // Use pagination with indexed ordering
        $menuItems = $query->orderBy('id', 'desc')->paginate(10);
        
        // Cache categories
        $categories = Cache::remember('menu_categories', 3600, function () {
            return Category::select('id', 'name')->orderBy('name')->get();
        });
        
        $archivedItems = MenuItem::onlyTrashed()->with('category')->orderBy('deleted_at', 'desc')->get();

        return view('admin.menu-items.index', compact('menuItems', 'categories', 'archivedItems'));
    }

    /**
     * Show form for creating new menu item.
     */
    public function create()
    {
        $categories = Cache::remember('menu_categories', 3600, function () {
            return Category::select('id', 'name')->orderBy('name')->get();
        });
        
        return view('admin.menu-items.create', compact('categories'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categories_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:menu_items',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Updated serving size validation - single text field
            'serving_size' => 'nullable|string|max:255', // Increased to 255 for custom text
            'allergens' => 'nullable|array',
            'allergens.*' => 'string|in:gluten,dairy,eggs,soy,nuts,shellfish,fish,sesame',
            'is_vegetarian' => 'nullable|boolean',
            'is_vegan' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'is_nut_free' => 'nullable|boolean',
            'allergen_notes' => 'nullable|string|max:500'
        ]);

        // Prepare data for creation
        $data = [
            'categories_id' => $validated['categories_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'serving_size' => $request->serving_size ?? null, // Single text field
            'is_vegetarian' => $request->has('is_vegetarian') ? 1 : 0,
            'is_vegan' => $request->has('is_vegan') ? 1 : 0,
            'is_gluten_free' => $request->has('is_gluten_free') ? 1 : 0,
            'is_nut_free' => $request->has('is_nut_free') ? 1 : 0,
            'allergen_notes' => $request->allergen_notes ?? null,
        ];
        
        // Handle allergens (convert array to JSON)
        if ($request->has('allergens') && is_array($request->allergens)) {
            $data['allergens'] = $request->allergens;
        } else {
            $data['allergens'] = null;
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-items', 'public');
            $data['image_path'] = $path;
        }

        MenuItem::create($data);
        
        // Clear caches
        Cache::forget('menu_items_count');
        Cache::forget('menu_categories');

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Show form for editing menu item.
     */
    public function edit(MenuItem $menuItem)
    {
        $categories = Cache::remember('menu_categories', 3600, function () {
            return Category::select('id', 'name')->orderBy('name')->get();
        });
        
        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'categories_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:menu_items,name,' . $menuItem->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Updated serving size validation - single text field
            'serving_size' => 'nullable|string|max:255', // Increased to 255 for custom text
            'allergens' => 'nullable|array',
            'allergens.*' => 'string|in:gluten,dairy,eggs,soy,nuts,shellfish,fish,sesame',
            'is_vegetarian' => 'nullable|boolean',
            'is_vegan' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'is_nut_free' => 'nullable|boolean',
            'allergen_notes' => 'nullable|string|max:500'
        ]);

        // Prepare data for update
        $data = [
            'categories_id' => $validated['categories_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'serving_size' => $request->serving_size ?? null, // Single text field
            'is_vegetarian' => $request->has('is_vegetarian') ? 1 : 0,
            'is_vegan' => $request->has('is_vegan') ? 1 : 0,
            'is_gluten_free' => $request->has('is_gluten_free') ? 1 : 0,
            'is_nut_free' => $request->has('is_nut_free') ? 1 : 0,
            'allergen_notes' => $request->allergen_notes ?? null,
        ];
        
        // Handle allergens (convert array to JSON)
        if ($request->has('allergens') && is_array($request->allergens)) {
            $data['allergens'] = $request->allergens;
        } else {
            $data['allergens'] = null;
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menuItem->image_path) {
                Storage::disk('public')->delete($menuItem->image_path);
            }
            
            // Store new image
            $path = $request->file('image')->store('menu-items', 'public');
            $data['image_path'] = $path;
        }
        
        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($menuItem->image_path) {
                Storage::disk('public')->delete($menuItem->image_path);
            }
            $data['image_path'] = null;
        }

        $menuItem->update($data);

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete(); // soft delete — moves to archive

        Cache::forget('menu_items');

        return redirect()->route('admin.menu-items.index')
            ->with('success', "\"{$menuItem->name}\" has been archived.");
    }

    public function restore($id)
    {
        $menuItem = MenuItem::onlyTrashed()->findOrFail($id);
        $menuItem->restore();

        Cache::forget('menu_items');

        return redirect()->route('admin.menu-items.index')
            ->with('success', "\"{$menuItem->name}\" has been restored.");
    }

    public function forceDelete($id)
    {
        $menuItem = MenuItem::onlyTrashed()->findOrFail($id);

        if ($menuItem->image_path) {
            Storage::disk('public')->delete($menuItem->image_path);
        }

        $menuItem->forceDelete();

        Cache::forget('menu_items');

        return redirect()->route('admin.menu-items.index')
            ->with('success', "\"{$menuItem->name}\" has been permanently deleted.");
    }

    /**
     * Quickly toggle a menu item between in-stock and out-of-stock.
     * Out-of-stock = stock set to 0. Restoring sets stock to 50 as a default.
     */
    public function toggleStock(MenuItem $menuItem)
    {
        if ($menuItem->stock > 0) {
            $menuItem->update(['stock' => 0]);
            $msg = "\"{$menuItem->name}\" marked as out of stock.";
        } else {
            $menuItem->update(['stock' => 50]);
            $msg = "\"{$menuItem->name}\" is back in stock (stock set to 50 — update in Edit if needed).";
        }

        Cache::forget('menu_items');

        return back()->with('success', $msg);
    }
}