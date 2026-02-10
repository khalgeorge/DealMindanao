<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        $items = NavigationItem::orderBy('location')
            ->orderBy('position')
            ->paginate(15);

        return view('admin.navigation.index', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin.navigation.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'location' => 'required|string|max:50',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['position'] = $data['position'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        NavigationItem::create($data);

        return redirect('/admin/navigation')->with('success', 'Navigation item created successfully.');
    }

    public function edit(NavigationItem $navigation)
    {
        return view('admin.navigation.edit', [
            'item' => $navigation,
        ]);
    }

    public function update(Request $request, NavigationItem $navigation)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'location' => 'required|string|max:50',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['position'] = $data['position'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $navigation->update($data);

        return redirect('/admin/navigation')->with('success', 'Navigation item updated successfully.');
    }

    public function destroy(NavigationItem $navigation)
    {
        $navigation->delete();

        return redirect('/admin/navigation')->with('success', 'Navigation item deleted successfully.');
    }
}
