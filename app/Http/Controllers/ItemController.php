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
            'name.required' => 'Nama menu wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'price.required' => 'Harga wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'img.image' => 'Berkas yang diunggah harus berupa gambar.',
            'img.max' => 'Ukuran gambar tidak boleh lebih dari 2 MB.',
            'is_active.required' => 'Tentukan apakah menu berstatus aktif.',
            'is_active.boolean' => 'Status aktif harus bernilai ya atau tidak.',
        ]);

        if  ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['img'] = $imageName;
        }

        $item = Item::create($validatedData);

        return redirect()->route('items.index')->with('success', 'Menu berhasil ditambahkan.');
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
            'name.required' => 'Nama menu wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'price.required' => 'Harga wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'img.image' => 'Berkas yang diunggah harus berupa gambar.',
            'img.max' => 'Ukuran gambar tidak boleh lebih dari 2 MB.',
            'is_active.required' => 'Tentukan apakah menu berstatus aktif.',
            'is_active.boolean' => 'Status aktif harus bernilai ya atau tidak.',
        ]);

        if  ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['img'] = $imageName;
        }

        $item->update($validatedData);

        return redirect()->route('items.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $item = Item::findOrFail($id);
        $item->update(['is_active' => $validated['is_active']]);

        $message = $validated['is_active']
            ? 'Menu berhasil diaktifkan.'
            : 'Menu berhasil dinonaktifkan.';

        return redirect()->route('items.index')->with('success', $message);
    }

    // public function destroy(string $id) {
    //     $item = Item::findOrFail($id);
    //     $item->delete();
    //
    //     return redirect()->route('items.index')->with('success', 'Menu berhasil dihapus.');
    // }

}
