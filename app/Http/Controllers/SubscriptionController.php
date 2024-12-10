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

    public function store(Request $request, CreateSubscriptionInteractor $interactor)
    {
        $createSubscriptionRequest = new CreateSubscriptionRequest();
        $createSubscriptionRequest->email = $request->input('email');
        $createSubscriptionRequest->website_id = $request->input('website_id');

        return $this->submitSubscription($interactor, $createSubscriptionRequest);
    }

    public function submitSubscription(CreateSubscriptionInteractor $interactor, CreateSubscriptionRequest $createSubscriptionRequest): JsonResponse
    {
        try {
            $subscription = $interactor->execute($createSubscriptionRequest);
            return response()->json([
                'message' => 'Subscription created successfully.',
                'subscription' => $subscription,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
