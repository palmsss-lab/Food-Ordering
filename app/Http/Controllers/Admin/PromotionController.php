<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(15);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string|max:500',
            'discount_percentage'  => 'required|numeric|min:1|max:100',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'is_active'            => 'boolean',
            'banner_color'         => 'nullable|string|max:7',
        ]);

        $data['is_active']     = $request->boolean('is_active', true);
        $data['banner_color']  = $data['banner_color'] ?? '#ea5a47';

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string|max:500',
            'discount_percentage'  => 'required|numeric|min:1|max:100',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'is_active'            => 'boolean',
            'banner_color'         => 'nullable|string|max:7',
        ]);

        $data['is_active']    = $request->boolean('is_active');
        $data['banner_color'] = $data['banner_color'] ?? '#ea5a47';

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted.');
    }
}
