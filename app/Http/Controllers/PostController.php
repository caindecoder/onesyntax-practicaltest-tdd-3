<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Website;
use Domain\Posts\Interactors\CreatePostInteractor;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        $websites = Website::all();
        return view('createPost.index', compact('posts', 'websites'));
    }

    public function create()
    {
        $websites = Website::all();
        return view('createPost.create', compact('websites'));
    }

    public function store(Request $request, CreatePostInteractor $createPostInteractor)
    {
          $createPostInteractor->execute(CreatePostRequest::from([
              'title' => $request->get('title'),
              'description' => $request->get('description'),
              'website_id' => $request->get('website_id')
          ]));

          return redirect()->route('posts.index')->with('success', 'Post created successfully.');

    }
}
