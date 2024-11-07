<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Domain\ValidationExceptions\ValidationException;
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
        $createWebsiteRequest = new CreateWebsiteRequest();

        $createWebsiteRequest->name = $request->input('name');
        $createWebsiteRequest->url = $request->input('url');

        return $this->submitWebsite($createWebsiteInteractor, $createWebsiteRequest);

    }

    /**
     * @param CreateWebsiteInteractor $createWebsiteInteractor
     * @param CreateWebsiteRequest $createWebsiteRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitWebsite(CreateWebsiteInteractor $createWebsiteInteractor, CreateWebsiteRequest $createWebsiteRequest): \Illuminate\Http\RedirectResponse
    {
        try {
            $website = $createWebsiteInteractor->create($createWebsiteRequest);
            return redirect()->route('websites.index')->with('success', 'Website created successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
