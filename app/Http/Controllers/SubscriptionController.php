<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Website;
use Domain\Subscriptions\Interactors\CreateSubscriptionInteractor;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('website')->get();
        return response()->json($subscriptions);
    }

    public function create()
    {
        $websites = Website::all();
        return response()->json(['websites' => $websites]);
    }

    public function store(Request $request, CreateSubscriptionInteractor $createSubscriptionInteractor)
    {
        $createSubscriptionInteractor->execute(CreateSubscriptionRequest::from([
            'email' => $request->get('email'),
            'website_id' => $request->get('website_id')
        ]));

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription created successfully.');
    }
}
