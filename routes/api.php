<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);

    Route::get('/user', function (Request $request) {
    return $request->user();
    });

    Route::get('abilities', function(Request $request) { 
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    }); 
});