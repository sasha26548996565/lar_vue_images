<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('post.')->prefix('post')->group(function () {
    Route::get('/', 'PostController@index');
    Route::post('/store', 'PostController@store');
});

Route::name('image.')->prefix('image')->group(function () {
    Route::post('/store', 'ImageController@store');
});
