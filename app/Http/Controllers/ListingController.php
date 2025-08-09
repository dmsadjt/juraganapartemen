<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Inertia\Inertia;

class ListingController extends Controller
{
    public function index() {
        $units = Unit::latest()->get();

        return Inertia::render('Listing/Index', [
            'units' => $units,
        ]);
    }
}
