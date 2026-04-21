<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SupportTicketModel;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        return view('help-center');
    }

    public function detail($id)
    {
        $id = decodeId($id);
        SupportTicketModel::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('help-center-detail', ['ticketId' => $id]);
    }
}
