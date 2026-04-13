<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function flightIndex()
    {
        return view('admin.booking.flight.index');
    }

    public function flightView(string $id)
    {
        return view('admin.booking.flight.view',['id' => $id]);
    }
}
