<?php

namespace App\Http\Controllers;

use App\Models\Category; // Pastikan Model Category diimport
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request) {
        $search = $request->query('search');
        
        $categories = Category::where('name', 'LIKE', "%{$search}%")->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name)
        ]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambah');
    }

    public function update(Request $request, $id) {
        $request->validate(['name' => 'required']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
    
}