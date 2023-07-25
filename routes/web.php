<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Jersipedia API',
        'author' => 'Abdur Rahim',
    ]);
});
