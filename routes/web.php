<?php

use Illuminate\Support\Facades\Route;

Route::get('/{page}', 'MainController@index')->where('page', '.*');
