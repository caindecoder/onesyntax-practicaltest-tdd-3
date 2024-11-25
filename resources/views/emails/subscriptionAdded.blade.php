<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Subscription Added</title>
</head>
<body>
<h1>New Subscription Added!</h1>
<p><strong>eMail:</strong> {{ $subscription->email }}</p>
<p><strong>Website:</strong> {{ $subscription->website }}</p>
<p><a href="{{ $subscription->website->url }}">Visit Website</a></p>
</body>
</html>
