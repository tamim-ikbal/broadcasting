<?php

use App\Events\MessageSent;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', IndexController::class)->name('home');

    Route::get('chats/', [ChatController::class, 'index'])->name('chat.index');
    Route::get('chats/{id}', [ChatController::class, 'messages'])->name('chat.messages');

    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

});
