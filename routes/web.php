<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home'));

Route::get('/listings', [ListingController::class, 'index']);