<?php

use Illuminate\Support\Facades\Route;

// Redirect root to Swagger API Documentation
Route::get('/', function () {
    return redirect('/api/documentation');
});
