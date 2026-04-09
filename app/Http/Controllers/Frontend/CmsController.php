<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PagesModel;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function show(string $slug)
    {
        $page = PagesModel::where('slug', $slug)
                    ->where('status', 'active')
                    ->firstOrFail();

        return view('page', compact('page'));
    }
}
