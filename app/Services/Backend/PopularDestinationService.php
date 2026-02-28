<?php

namespace App\Services\Backend;

use App\Models\PopularDestinationModel;
use App\Repositories\Backend\PopularDestinationRepository;
use App\Services\Common\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PopularDestinationService
{
    protected PopularDestinationRepository $repo;
    protected FileService $fileService;

    protected string $folder = 'popular_destination';

    public function __construct(PopularDestinationRepository $repo, FileService $fileService)
    {
        $this->repo = $repo;
        $this->fileService = $fileService;
    }

    public function getPopularDestinationList(array $filters)
    {
        return $this->repo->getList($filters);
    }

    public function create(array $data)
    {
        if (!empty($data['title'])) {
            $slug = Str::slug($data['title']);
            $exists = PopularDestinationModel::where('slug', $slug)->exists();
            if ($exists) {
                return [
                    'status' => false,
                    'title' => 'already_used',
                    'message' => 'This Title already used'
                ];
            }
            $data['slug'] = $slug;
        }

        if (!empty($data['image'])) {
            $imagePath = $this->fileService->upload($data['image'], $this->folder, 'destination');
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }

        return $this->repo->create($data);
    }

    public function update(int $id, Request $request)
    {
        $destination = $this->repo->findById($id);
        if (!$destination) {
            return false;
        }
        $data = $request->only(['title', 'status']);
        if ($request->hasFile('image')) {
            if ($destination->image) {
                $this->fileService->remove($destination->image, $this->folder);
            }
            $data['image'] = $this->fileService->upload($request->file('image'), $this->folder);
        }
        return $this->repo->update($id, $data);
    }
}
