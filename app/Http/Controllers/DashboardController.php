<?php

namespace App\Http\Controllers;

use Livewire\Volt\Volt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard using Livewire component.
     */
    public function index()
    {
        return view('layouts.dashboard');
    }
}
