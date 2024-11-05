<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Display a list of subscriptions
        $subscriptions = Subscription::all();
        $websites = Website::all();
        return view('createSubscription.index', compact('subscriptions', 'websites'));
    }

    public function create()
    {
        // Show the form for creating a new subscription
        $websites = Website::all(); // Assuming you want to select from available websites
        return view('createSubscription.create', compact('websites'));
    }

    public function store(Request $request)
    {
        // Validate and create a new subscription
        $request->validate([
            'email' => 'required|email|max:255|unique:subscriptions',
            'website_id' => 'required|exists:websites,id',
        ]);

        Subscription::create($request->only('email', 'website_id'));

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }
}
