<?php

namespace App\Repositories\Backend;

use App\Models\EmailTemplateModel;
use App\Models\FaqModel;
use App\Models\FaqCategoryModel;

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
        $query = FaqCategoryModel::query();

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

        if(empty($filters)){
           return $query->get();
        }
        return $query->paginate($filters['perPage'] ?? 10);
    }

    public function createFaqCategory($data)
    {
        return FaqCategoryModel::create($data);
    }

    //=================================================== Faq ====================================

    public function faqList($filters = [])
    {
        $query = FaqModel::with('faqCategory');

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
                'c_id' => $faq['faq_category_id'],
                'question' => $faq['question'],
                'answer'   => $faq['answer'],
                'status'   => $faq['status'] ?? 'active',
            ]);
        }
    }

    public function updateFaq(int $id, array $data): bool
    {
        $faq = FaqModel::findOrFail($id);
        if (isset($data['faq_category_id'])) {
            $data['c_id'] = $data['faq_category_id'];
            unset($data['faq_category_id']);
        }
        return $faq->update($data);
    }
}
