<?php

namespace App\Repositories\Backend;

use App\Models\EmailTemplateModel;
use App\Models\FaqModel;
use App\Models\FaqCategory;

class CmsRepository
{
    //=================================================== Email Template ====================================

    public function emailTemplateList($filters = [])
    {
        $query = EmailTemplateModel::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sortField'])) {
            $query->orderBy(
                $filters['sortField'],
                $filters['sortDirection'] ?? 'asc'
            );
        }

        return $query->paginate($filters['perPage'] ?? 10);
    }

    //===================================================== Faq Category ====================================

    public function faqCategoryList($filters = [])
    {
        $query = FaqCategory::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('status', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['sortField'])) {
            $query->orderBy(
                $filters['sortField'],
                $filters['sortDirection'] ?? 'asc'
            );
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($filters['perPage'] ?? 10);
    }

    public function createFaqCategory($data)
    {
        return FaqCategory::create($data);
    }

    //=================================================== Faq ====================================

    public function faqList($filters = [])
    {
        $query = FaqModel::query();

        if (!empty($filters['search'])) {
            $query->where('question', 'like', '%' . $filters['search'] . '%');
            $query->orwhere('answer', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sortField'])) {
            $query->orderBy(
                $filters['sortField'],
                $filters['sortDirection'] ?? 'asc'
            );
        }

        return $query->paginate($filters['perPage'] ?? 10);
    }

    public function insertFaqs(array $faqs)
    {
        foreach ($faqs as $faq) {
            FaqModel::create([
                'question' => $faq['question'],
                'answer'   => $faq['answer'],
                'status'   => $faq['status'] ?? 'active',
            ]);
        }
    }
}
