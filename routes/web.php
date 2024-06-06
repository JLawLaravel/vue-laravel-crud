<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/{any?}', 'dashboard') 
    ->where('any', '.*'); 