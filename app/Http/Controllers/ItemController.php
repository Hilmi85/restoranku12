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
}
