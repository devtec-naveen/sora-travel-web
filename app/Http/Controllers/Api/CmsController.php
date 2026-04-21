<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\CmsService;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    protected $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function show(string $slug)
    {
        $page = \App\Models\PagesModel::where('slug', $slug)
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

    public function faq()
    {
        $categories = $this->cmsService->getFaqsForFrontend();

        if ($categories->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No FAQs found.',
                'data'    => [],
            ], config('constant.httpCode.NOT_FOUND'));
        }

        $data = $categories->map(function ($category) {
            return [
                'id'   => $category->id,
                'name' => $category->name,
                'faqs' => $category->faqs->map(function ($faq) {
                    return [
                        'id'       => $faq->id,
                        'question' => $faq->question,
                        'answer'   => $faq->answer,
                    ];
                }),
            ];
        });

        return response()->json([
            'status'  => true,
            'message' => 'FAQs fetched successfully.',
            'data'    => $data,
        ], config('constant.httpCode.SUCCESS_OK'));
    }
}