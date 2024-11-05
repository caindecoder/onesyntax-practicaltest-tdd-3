@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create New Post</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="website_id">Website</label>
                <select name="website_id" class="form-control" required>
                    <option value="">Select a website</option>
                    @foreach($websites as $website)
                        <option value="{{ $website->id }}">{{ $website->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>

        <h2 class="mt-5">Existing Posts</h2>
        <ul>
            @foreach ($posts as $post)
                <li>{{ $post->title }} - {{ $post->description }}</li>
            @endforeach
        </ul>

    </div>
@endsection
