<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // Display a list of posts
        $posts = Post::all();
        $websites = Website::all();
        return view('createPost.index', compact('posts', 'websites'));
    }

    public function create()
    {
        // Show the form for creating a new post
        $websites = Website::all(); // Assuming you want to select from available websites
        return view('createPost.create', compact('websites'));
    }

    public function store(Request $request)
    {
        // Validate and create a new post
        $request->validate([
            'title' => 'required|string|max:255|unique:posts',
            'description' => 'required|string',
            'website_id' => 'required|exists:websites,id',
        ]);

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'website_id' => $request->website_id,
        ]);

        // Optionally, you may trigger an email to subscribers here

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }
}
