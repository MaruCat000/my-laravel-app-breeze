<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TweetController;

use App\Http\Controllers\ReplyController;

use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return view('index');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [TweetController::class, 'index'])->name('dashboard');

    Route::get('/tweets/create', [TweetController::class, 'create'])->name('tweets.create');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');

    Route::get('/tweets/{tweet}', [TweetController::class, 'show'])->name('tweets.show');

    Route::get('/replys/create/{tweet}',[ReplyController::class,'create'])->name('replys.create');

    Route::post('/replys/{tweet}',[ReplyController::class,'store'])->name('replys.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


require __DIR__.'/auth.php';
