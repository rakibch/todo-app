<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // where app.blade.php loads your Vue SPA
})->where('any', '.*');
