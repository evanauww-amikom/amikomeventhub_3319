<?php

namespace App\Http\Controllers;

use App\Models\Partner; 
use App\Models\Category; 
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $partners = Partner::all();
        $categories = Category::all();

        return view('welcome', compact('partners', 'categories'));
    }
}