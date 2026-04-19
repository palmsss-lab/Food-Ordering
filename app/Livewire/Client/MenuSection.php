<?php

namespace App\Livewire\Client;

use App\Models\Category;
use App\Models\MenuItem;
use Livewire\Component;

class MenuSection extends Component
{
    public int $activeCategory = 0;

    public function mount(): void
    {
        $first = Category::whereHas('menuItems')->first();
        $this->activeCategory = $first?->id ?? 0;
    }

    public function setCategory(int $id): void
    {
        $this->activeCategory = $id;
    }

    public function render()
    {
        $categories = Category::withCount('menuItems')
            ->whereHas('menuItems')
            ->with(['menuItems' => fn($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        if ($categories->isNotEmpty() && !$categories->contains('id', $this->activeCategory)) {
            $this->activeCategory = $categories->first()->id;
        }

        // Re-fetch active items fresh from DB to always get latest stock
        $activeItems = $this->activeCategory
            ? MenuItem::where('categories_id', $this->activeCategory)->orderBy('name')->get()
            : collect();

        return view('livewire.client.menu-section', compact('categories', 'activeItems'));
    }
}
