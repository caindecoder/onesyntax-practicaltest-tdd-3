<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Website;
use Domain\Subscriptions\Interactors\CreateSubscriptionInteractor;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();
        $websites = Website::all();
        return view('createSubscription.index', compact('subscriptions', 'websites'));
    }

    public function create()
    {

        $websites = Website::all();
        return view('createSubscription.create', compact('websites'));
    }

    public function store(Request $request, CreateSubscriptionInteractor $interactor)
    {
        $createSubscriptionRequest = new CreateSubscriptionRequest();
        $createSubscriptionRequest->email = $request->input('email');
        $createSubscriptionRequest->website_id = $request->input('website_id');

        return $this->submitSubscription($interactor, $createSubscriptionRequest);
    }

    /**
     * @param CreateSubscriptionInteractor $interactor
     * @param CreateSubscriptionRequest $createSubscriptionRequest
     * @return RedirectResponse
     */
    public function submitSubscription(CreateSubscriptionInteractor $interactor, CreateSubscriptionRequest $createSubscriptionRequest): RedirectResponse
    {
        try {

            $subscription = $interactor->create($createSubscriptionRequest);

            return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
