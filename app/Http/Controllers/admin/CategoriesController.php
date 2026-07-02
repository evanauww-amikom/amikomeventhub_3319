<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $categories = Category::where('name', 'LIKE', "%{$search}%")->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // FIXED: slug ikut diupdate juga
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}