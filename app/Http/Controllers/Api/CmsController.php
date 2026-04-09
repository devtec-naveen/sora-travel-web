<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PagesModel;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function show(string $slug)
    {
        $page = PagesModel::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], config('constant.httpCode.NOT_FOUND'));
        }

        return response()->json([
            'status'  => true,
            'message' => 'Page fetched successfully.',
            'data'    => [
                'id'      => $page->id,
                'title'   => $page->page_title,
                'slug'    => $page->slug,
                'content' => $page->content,
            ],
        ], config('constant.httpCode.SUCCESS_OK'));
    }
}
