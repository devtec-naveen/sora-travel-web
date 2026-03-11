<?php

namespace App\Repositories\Api;

use App\Models\SpecialOffersModel;
use App\Models\PopularDestinationModel;

class ContentRepository
{
    public function getSpecialOffers()
    {
        $base = config('constant.image_base_url');
        $folder = 'special_offer/';
        return SpecialOffersModel::where('status', 'active')
            ->latest()
            ->get()
            ->map(function ($item) use ($base, $folder) {
                $item->image = $item->image
                    ? $base . $folder . $item->image
                    : null;

                return $item;
            });
    }

    public function getPopularDestinations()
    {
        $base = config('constant.image_base_url');
        $folder = 'popular_destination/';
        return PopularDestinationModel::where('status', 'active')
            ->latest()
            ->get()
            ->map(function ($item) use ($base, $folder) {
                $item->image = $item->image
                    ? $base . $folder . $item->image
                    : null;

                return $item;
            });
    }
}
