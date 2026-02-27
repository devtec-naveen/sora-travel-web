<?php

namespace App\Services\Backend;

use App\Repositories\Backend\SpecialOffersRepository;
use App\Services\Common\FileService;
use Illuminate\Http\Request;

class SpecialOffersService
{
    protected $repo;
    protected $fileService;

    public function __construct(SpecialOffersRepository $repo, FileService $fileService)
    {
        $this->repo = $repo;
        $this->fileService = $fileService;
    }

    public function getSpecialOfferList(array $filters = [])
    {
        return $this->repo->getList($filters);
    }

    public function create(Request $request)
    {
        $data = $request->only([
            'title',
            'start_date_time',
            'end_date_time',
            'status'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $this->fileService->upload($request->file('image'), 'special_offer', 'offer');
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }

        return $this->repo->create($data);
    }

    public function update(int $id, Request $request)
    {
        $offer = $this->repo->findById($id);

        if (!$offer) {
            return false;
        }

        $data = $request->only([
            'title',
            'start_date_time',
            'end_date_time',
            'status'
        ]);

        if ($request->hasFile('image')) {

            if ($offer->image) {
                $this->fileService->remove($offer->image);
            }

            $imagePath = $this->fileService->upload(
                $request->file('image'),
                'special_offer',
                'offer'
            );

            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }

        return $this->repo->update($id, $data);
    }

    public function find(int $id)
    {
        return $this->repo->findById($id);
    }
}
