<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PagesModel;
use Illuminate\Http\Request;
use App\Services\Api\ContentService;
class CmsController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

   public function homePage()
   {
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
}
