<?php

namespace App\Http\Controllers\Client;

use App\Models\Category;
use App\Http\Controllers\Controller; 
use App\Models\MenuItem;
use Illuminate\Support\Str;

class MenuItemsController extends Controller
{

    public function index()
    {
        // Fetch all categories with number of items
        $categories = Category::withCount('menuItems')->get();

        // Get the first category by default (e.g., Soup)
        $activeCategory = $categories->first();

        // Get menu items for the active category, or empty collection if none
        $menuItems = $activeCategory ? $activeCategory->menuItems : collect();

        // Return the view with all necessary data
        return view('client.content.menu-section', compact('categories', 'menuItems', 'activeCategory'));
    }


}
