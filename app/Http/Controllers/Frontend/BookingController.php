<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function myBooking()
    {
        return view('booking.listing');
    }

    public function myFlightViewBooking(string $id)
    {
        return view('booking.view',['id' => $id]);
    }
    
}
