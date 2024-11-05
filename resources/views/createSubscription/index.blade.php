@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Subscription</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('subscriptions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="website_id" class="form-label">Select Website</label>
                <select class="form-select" id="website_id" name="website_id" required>
                    @foreach ($websites as $website)
                        <option value="{{ $website->id }}">{{ $website->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>

        <h2 class="mt-5">Existing Subscriptions</h2>
        <ul>
            @foreach ($subscriptions as $subscription)
                <li>{{ $subscription->email }} - {{ $subscription->website->name }}</li>
            @endforeach
        </ul>
    </div>
@endsection
