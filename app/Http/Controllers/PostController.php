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
        return response()->json($posts);
    }

    public function create()
    {
        $websites = Website::all();
        return response()->json(['websites' => $websites]);
    }

    public function store(Request $request, CreatePostInteractor $createPostInteractor)
    {
        $post = $createPostInteractor->execute(CreatePostRequest::from([
              'title' => $request->get('title'),
              'description' => $request->get('description'),
              'website_id' => $request->get('website_id')
          ]));

        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post,
        ]);
    }
}
