@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Website</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('websites.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Website Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">Website URL</label>
                <input type="url" class="form-control" id="url" name="url" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Website</button>
        </form>

        <h2 class="mt-5">Existing Websites</h2>
        <ul>
            @foreach ($websites as $website)
                <li>{{ $website->name }} - <a href="{{ $website->url }}">{{ $website->url }}</a></li>
            @endforeach
        </ul>
    </div>
@endsection
