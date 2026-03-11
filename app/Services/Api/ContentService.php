<?php

namespace App\Services\Api;
use App\Repositories\Api\ContentRepository;

class ContentService
{
    protected $repo;

    public function __construct(ContentRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getSpecialOffers()
    {
        return $this->repo->getSpecialOffers();
    }

    public function getPopularDestinations()
    {
        return $this->repo->getPopularDestinations();
    }
}