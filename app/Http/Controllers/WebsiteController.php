<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Domain\Websites\Interactors\CreateWebsiteInteractor;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return response()->json(Website::all());
    }

    public function create()
    {
        return response()->json(['message' => 'Create website page']);
    }

    public function store(Request $request, CreateWebsiteInteractor $createWebsiteInteractor)
    {
        $createWebsiteInteractor->execute(CreateWebsiteRequest::from([
            'name' => $request->get('name'),
            'url' => $request->get('url'),
        ]));

        return redirect()->route('website.index')
            ->with('success', 'Website created successfully.');
    }
}
