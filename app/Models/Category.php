<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        
        // When creating a new category
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
        
        // When updating an existing category
        static::updating(function ($category) {
            // Only update slug if the name has changed
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'categories_id');
    }
}