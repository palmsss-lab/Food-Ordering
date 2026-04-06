<?php
// app/Models/MenuItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';
    
    protected $fillable = [
        'categories_id',
        'name',
        'description',
        'price',
        'stock',
        'image_path',
        'serving_size', // Now a single text field for custom serving descriptions
        'allergens',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'is_nut_free',
        'allergen_notes'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'allergens' => 'array',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean',
        'is_nut_free' => 'boolean'
    ];

    protected $appends = [
        'formatted_price',
        'stock_status',
        'serving_display',
        'allergen_badges',
        'dietary_badges',
        'allergen_icons'
    ];

    /**
     * Get the category that owns the menu item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    // ==================== FORMATTERS ====================
    
    /**
     * Get formatted price with peso sign.
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    /**
     * Get serving display - returns custom text as-is
     * Examples: "Good for 2-3 people", "Serves 4-6", "Family size", "200g per serving"
     */
    public function getServingDisplayAttribute()
    {
        return $this->serving_size;
    }

    /**
     * Get allergen badges for display
     */
    public function getAllergenBadgesAttribute()
    {
        if (!$this->allergens || empty($this->allergens)) {
            return [];
        }
        
        $allergenMap = [
            'gluten' => ['name' => 'Gluten', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '🌾'],
            'dairy' => ['name' => 'Dairy', 'color' => 'bg-blue-100 text-blue-800', 'icon' => '🥛'],
            'eggs' => ['name' => 'Eggs', 'color' => 'bg-purple-100 text-purple-800', 'icon' => '🥚'],
            'soy' => ['name' => 'Soy', 'color' => 'bg-green-100 text-green-800', 'icon' => '🫘'],
            'nuts' => ['name' => 'Nuts', 'color' => 'bg-amber-100 text-amber-800', 'icon' => '🥜'],
            'shellfish' => ['name' => 'Shellfish', 'color' => 'bg-red-100 text-red-800', 'icon' => '🦐'],
            'fish' => ['name' => 'Fish', 'color' => 'bg-teal-100 text-teal-800', 'icon' => '🐟'],
            'sesame' => ['name' => 'Sesame', 'color' => 'bg-stone-100 text-stone-800', 'icon' => '🌿'],
        ];
        
        $badges = [];
        foreach ($this->allergens as $allergen) {
            if (isset($allergenMap[$allergen])) {
                $badges[] = $allergenMap[$allergen];
            }
        }
        
        return $badges;
    }

    /**
     * Get dietary badges (vegetarian, vegan, gluten-free, nut-free)
     */
    public function getDietaryBadgesAttribute()
    {
        $badges = [];
        
        if ($this->is_vegetarian) {
            $badges[] = ['name' => 'Vegetarian', 'color' => 'bg-green-100 text-green-800', 'icon' => '🌱'];
        }
        if ($this->is_vegan) {
            $badges[] = ['name' => 'Vegan', 'color' => 'bg-emerald-100 text-emerald-800', 'icon' => '🌿'];
        }
        if ($this->is_gluten_free) {
            $badges[] = ['name' => 'Gluten-Free', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '🚫'];
        }
        if ($this->is_nut_free) {
            $badges[] = ['name' => 'Nut-Free', 'color' => 'bg-orange-100 text-orange-800', 'icon' => '🥜'];
        }
        
        return $badges;
    }

    /**
     * Get allergen icons as text (for simple display)
     */
    public function getAllergenIconsAttribute()
    {
        if (!$this->allergens || empty($this->allergens)) {
            return null;
        }
        
        $icons = [
            'gluten' => '🌾',
            'dairy' => '🥛',
            'eggs' => '🥚',
            'soy' => '🫘',
            'nuts' => '🥜',
            'shellfish' => '🦐',
            'fish' => '🐟',
            'sesame' => '🌿',
        ];
        
        $display = [];
        foreach ($this->allergens as $allergen) {
            if (isset($icons[$allergen])) {
                $display[] = $icons[$allergen];
            }
        }
        
        return implode(' ', $display);
    }

    // ==================== STOCK METHODS ====================
    
    /**
     * Get stock status with label and class.
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock > 10) {
            return [
                'label' => 'In Stock',
                'class' => 'success',
                'color' => 'green'
            ];
        } elseif ($this->stock > 0) {
            return [
                'label' => 'Low Stock',
                'class' => 'warning',
                'color' => 'yellow'
            ];
        } else {
            return [
                'label' => 'Out of Stock',
                'class' => 'danger',
                'color' => 'red'
            ];
        }
    }

    /**
     * Check if item is in stock.
     */
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Check if item is low stock.
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock > 0 && $this->stock <= 10;
    }

    /**
     * Get the total value of stock on hand.
     */
    public function getStockValueAttribute()
    {
        return $this->price * $this->stock;
    }

    /**
     * Get formatted stock value.
     */
    public function getFormattedStockValueAttribute()
    {
        return '₱' . number_format($this->stock_value, 2);
    }

    /**
     * Get short description (truncated).
     */
    public function getShortDescriptionAttribute($length = 50)
    {
        return strlen($this->description) > $length 
            ? substr($this->description, 0, $length) . '...' 
            : $this->description;
    }

    /**
     * Get image URL or default placeholder.
     */
    public function getImageAttribute()
    {
        return $this->image_path ?? 'https://via.placeholder.com/300x200?text=No+Image';
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope a query to search by name.
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }
        
        return $query->where('name', 'LIKE', $term . '%')
                     ->orWhere('name', 'LIKE', '% ' . $term . '%');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        if (empty($categoryId)) {
            return $query;
        }
        
        return $query->where('categories_id', $categoryId);
    }

    /**
     * Scope a query to only include available items.
     */
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to only include low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)
                     ->where('stock', '<=', 10);
    }

    /**
     * Scope a query to only include out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Scope a query to only include vegetarian items.
     */
    public function scopeVegetarian($query)
    {
        return $query->where('is_vegetarian', true);
    }

    /**
     * Scope a query to only include vegan items.
     */
    public function scopeVegan($query)
    {
        return $query->where('is_vegan', true);
    }

    /**
     * Scope a query to only include gluten-free items.
     */
    public function scopeGlutenFree($query)
    {
        return $query->where('is_gluten_free', true);
    }

    /**
     * Scope a query to only include nut-free items.
     */
    public function scopeNutFree($query)
    {
        return $query->where('is_nut_free', true);
    }

    /**
     * Scope a query to filter by allergen.
     */
    public function scopeWithoutAllergen($query, $allergen)
    {
        return $query->where(function($q) use ($allergen) {
            $q->whereNull('allergens')
              ->orWhereJsonDoesntContain('allergens', $allergen);
        });
    }

    // ==================== CACHE METHODS ====================
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when menu item is saved/updated/deleted
        static::saved(function () {
            Cache::forget('menu_items_count');
            Cache::forget('low_stock_count');
        });

        static::deleted(function () {
            Cache::forget('menu_items_count');
            Cache::forget('low_stock_count');
        });
    }

    /**
     * Get the count of menu items with caching.
     */
    public static function getCachedCount()
    {
        return Cache::remember('menu_items_count', 3600, function () {
            return self::count();
        });
    }

    /**
     * Get the count of low stock items with caching.
     */
    public static function getCachedLowStockCount()
    {
        return Cache::remember('low_stock_count', 1800, function () {
            return self::lowStock()->count();
        });
    }

    // ==================== VALIDATION ====================
    
    /**
     * Get validation rules.
     */
    public static function getValidationRules($id = null)
    {
        $uniqueRule = $id ? 'unique:menu_items,name,' . $id : 'unique:menu_items';
        
        return [
            'categories_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|' . $uniqueRule,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_path' => 'nullable|url|max:500',
            'serving_size' => 'nullable|string|max:255', // Increased to 255 for custom text
            'allergens' => 'nullable|array',
            'allergens.*' => 'string|in:gluten,dairy,eggs,soy,nuts,shellfish,fish,sesame',
            'is_vegetarian' => 'boolean',
            'is_vegan' => 'boolean',
            'is_gluten_free' => 'boolean',
            'is_nut_free' => 'boolean',
            'allergen_notes' => 'nullable|string|max:500',
        ];
    }

    // ==================== HELPER METHODS ====================
    
    /**
     * Get available allergen options for selection.
     */
    public static function getAllergenOptions()
    {
        return [
            'gluten' => ['label' => 'Gluten', 'icon' => '🌾', 'description' => 'Contains wheat, barley, rye'],
            'dairy' => ['label' => 'Dairy', 'icon' => '🥛', 'description' => 'Contains milk, cheese, butter'],
            'eggs' => ['label' => 'Eggs', 'icon' => '🥚', 'description' => 'Contains eggs'],
            'soy' => ['label' => 'Soy', 'icon' => '🫘', 'description' => 'Contains soy products'],
            'nuts' => ['label' => 'Nuts', 'icon' => '🥜', 'description' => 'Contains tree nuts or peanuts'],
            'shellfish' => ['label' => 'Shellfish', 'icon' => '🦐', 'description' => 'Contains shrimp, crab, lobster'],
            'fish' => ['label' => 'Fish', 'icon' => '🐟', 'description' => 'Contains fish products'],
            'sesame' => ['label' => 'Sesame', 'icon' => '🌿', 'description' => 'Contains sesame seeds/oil'],
        ];
    }

    /**
     * Get example serving size suggestions.
     */
    public static function getServingSizeExamples()
    {
        return [
            'Good for 2-3 people',
            'Serves 4-6 adults',
            'Family size',
            'Individual serving',
            '200g per serving',
            'Good for sharing',
            'Perfect for one',
            'Serves 2 with rice',
        ];
    }
}