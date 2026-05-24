<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $partners = Partner::where('name', 'LIKE', "%{$search}%")->get();
        
        return view('admin.partners.index', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'logo_url' => 'required'
        ]);

        Partner::create($request->all());
        return redirect()->back()->with('success', 'Partner berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'logo_url' => 'required'
        ]);

        $partner = Partner::findOrFail($id);
        $partner->update($request->all());
        return redirect()->back()->with('success', 'Partner berhasil diupdate');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        return redirect()->back()->with('success', 'Partner berhasil dihapus');
    }
}