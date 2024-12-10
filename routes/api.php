<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;


Route::middleware('api')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('post.index');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscription.store');

    Route::get('/websites', [WebsiteController::class, 'index'])->name('website.index');
    Route::post('/websites', [WebsiteController::class, 'store'])->name('website.store');;
});
