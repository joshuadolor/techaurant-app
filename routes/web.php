<?php

use Illuminate\Support\Facades\Route;
/*
use Illuminate\Support\Facades\Mail;
Route::get('/test-email', function() {
    Mail::raw('Test email content', function($message) {
        $message->to('test@example.com')
                ->subject('Test Email');
    });
    return 'Email sent successfully!';
});
*/

// Catch all route for SPA
Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');

