<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PagesModel;
use Illuminate\Http\Request;
use App\Services\Api\ContentService;
use App\Services\Common\CmsService;
class CmsController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

   public function homePage()
   {
        session()->forget('flight_search_tabs');
        session()->forget('selected_flight');
        $popularDestinations = $this->service->getPopularDestinations();
        $specialOffers = $this->service->getSpecialOffers();
        return view('index',compact('popularDestinations','specialOffers'));
   }

    public function show(string $slug)
    {
        $page = PagesModel::where('slug', $slug)
                    ->where('status', 'active')
                    ->firstOrFail();

        return view('page', compact('page'));
    }

    public function faq()
    {
        $categories = app(CmsService::class)->getFaqsForFrontend();
        return view('faq', compact('categories'));
    }

}
