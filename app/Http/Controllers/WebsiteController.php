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
        $websites = Website::all();
        return view('createWebsite.index', compact('websites'));
    }

    public function create()
    {
        return view('createWebsite.create');
    }

    public function store(Request $request, CreateWebsiteInteractor $createWebsiteInteractor)
    {
        $createWebsiteInteractor->execute(CreateWebsiteRequest::from([
            'name' => $request->get('name'),
            'url' => $request->get('url'),
        ]));

        return redirect()->route('websites.index')->with('success', 'Website created successfully.');

    }
}
