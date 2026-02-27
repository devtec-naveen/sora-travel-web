<?php

namespace App\Repositories\Backend;

use App\Models\SpecialOffersModel;

class SpecialOffersRepository
{
    public function getList(array $filters = [])
    {
        $query = SpecialOffersModel::query();
        
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

    public function findById(int $id): ?SpecialOffersModel
    {
        return SpecialOffersModel::find($id);
    }

    public function create(array $data): SpecialOffersModel
    {
        return SpecialOffersModel::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $offer = $this->findById($id);

        if (!$offer) {
            return false;
        }

        return $offer->update($data);
    }

    public function getAll()
    {
        return SpecialOffersModel::latest()->paginate(10);
    }
}
