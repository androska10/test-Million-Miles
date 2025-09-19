<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    public function index(Request $request): View
    {
        $cars = Car::latest(10);        

        return view('index', compact('cars'));
    }
}
