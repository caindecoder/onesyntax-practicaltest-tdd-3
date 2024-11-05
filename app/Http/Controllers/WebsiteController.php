<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        // Display a list of websites
        $websites = Website::all();
        return view('createWebsite.index', compact('websites'));
    }

    public function create()
    {
        // Show the form for creating a new website
        return view('createWebsite.create');
    }

    public function store(Request $request)
    {
        // Validate and create a new website
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        Website::create($request->only('name', 'url'));

        return redirect()->route('websites.index')->with('success', 'Website created successfully.');
    }
}
