<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PopularDestinationModel;
use App\Models\SpecialOffersModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $userCount = User::where('role',1)->count();
        $popularDestination = PopularDestinationModel::count();
        $specialOffers = SpecialOffersModel::count();
        return view('admin.dashboard',compact('userCount','popularDestination','specialOffers'));
    }
}
