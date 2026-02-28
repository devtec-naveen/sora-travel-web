<?php

namespace App\Repositories\Backend;

use App\Models\PopularDestinationModel;

class PopularDestinationRepository
{
    public function getList(array $filters = [])
    {
        $query = PopularDestinationModel::query();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sortField'])) {
            $query->orderBy(
                $filters['sortField'],
                $filters['sortDirection'] ?? 'asc'
            );
        } else {
            $query->latest();
        }

        return $query->paginate($filters['perPage'] ?? 10);
    }

    public function findById(int $id): ?PopularDestinationModel
    {
        return PopularDestinationModel::find($id);
    }

    public function create(array $data): PopularDestinationModel
    {
        return PopularDestinationModel::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $destination = $this->findById($id);

        if (!$destination) {
            return false;
        }

        return $destination->update($data);
    }

    public function delete(int $id): bool
    {
        $destination = $this->findById($id);

        if (!$destination) {
            return false;
        }

        return $destination->delete();
    }

    public function getAll()
    {
        return PopularDestinationModel::latest()->paginate(10);
    }
}
