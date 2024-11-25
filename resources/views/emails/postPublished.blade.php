<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post Published</title>
</head>
<body>
<h1>New Post Published!</h1>
<p><strong>Title:</strong> {{ $post->title }}</p>
<p><strong>Description:</strong> {{ $post->description }}</p>
<p><a href="{{ $post->website->url }}">Visit Website</a></p>
</body>
</html>
