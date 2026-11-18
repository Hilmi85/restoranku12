<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    public function index() {
        $items = Item::orderBy('name', 'asc')->get();

        return view('admin.item.index', compact('items'));
    }

    public function create() {
        $categories = Category::orderBy('cat_name', 'asc')->get();

        return view('admin.item.create', compact('categories'));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'img' => 'required|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ],
        [
            'name.required' => 'The item name is required.',
            'description.string' => 'The description must be a string.',
            'price.required' => 'The price is required.',
            'category_id.required' => 'The category is required.',
            'img.image' => 'The uploaded file must be an image.',
            'img.max' => 'The image size must not exceed 2MB.',
            'is_active.required' => 'Please specify if the item is active or not.',
            'is_active.boolean' => 'The is_active field must be true or false.',
        ]);

        if  ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['img'] = $imageName;
        }

        $item = Item::create($validatedData);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(string $id) {
        $item = Item::findOrFail($id);
        $categories = Category::orderBy('cat_name', 'asc')->get();

        return view('admin.item.edit', compact('item', 'categories'));
    }

    public function update(Request $request, string $id) {
        $item = Item::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ],
        [
            'name.required' => 'The item name is required.',
            'description.string' => 'The description must be a string.',
            'price.required' => 'The price is required.',
            'category_id.required' => 'The category is required.',
            'img.image' => 'The uploaded file must be an image.',
            'img.max' => 'The image size must not exceed 2MB.',
            'is_active.required' => 'Please specify if the item is active or not.',
            'is_active.boolean' => 'The is_active field must be true or false.',
        ]);

        if  ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['img'] = $imageName;
        }

        $item->update($validatedData);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }
}
