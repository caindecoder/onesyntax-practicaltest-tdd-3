<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Website;
use Domain\Posts\Interactors\CreatePostInteractor;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Http\RedirectResponse;
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

    public function store(Request $request, CreatePostInteractor $interactor)
    {
        $createPostRequest = new CreatePostRequest();

        $createPostRequest->title = $request->input('title');
        $createPostRequest->description = $request->input('description');
        $createPostRequest->website_id = $request->input('website_id');


        return $this->submitPost($interactor, $createPostRequest);
    }

    /**
     * @param CreatePostInteractor $interactor
     * @param CreatePostRequest $createPostRequest
     * @return RedirectResponse
     */
    public function submitPost(CreatePostInteractor $interactor, CreatePostRequest $createPostRequest): RedirectResponse
    {
        try {

            $post = $interactor->create($createPostRequest);

            return redirect()->route('posts.index')->with('success', 'Post created successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
