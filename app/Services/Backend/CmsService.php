<?php

namespace App\Services\Backend;

use App\Models\FaqCategoryModel;
use App\Models\FaqModel;
use App\Models\EmailTemplateModel;
use App\Models\PagesModel;
use App\Repositories\Backend\CmsRepository;

class CmsService
{
    protected $repo;

    public function __construct(CmsRepository $repo)
    {
        $this->repo = $repo;
    }

    //=============================== Email Template ============================== 

    public function getemailTemplateList($request)
    {
        $emailTemplateList = $this->repo->emailTemplateList($request);
        return $emailTemplateList;
    }

    public function getEmailTemplateById(int $id): EmailTemplateModel
    {
        return EmailTemplateModel::findOrFail($id);
    }

    //=============================== Faq Category List ============================== 

    public function getfaqCategoryList($request = null)
    {
        $emailTemplateList = $this->repo->faqCategoryList($request);
        return $emailTemplateList;
    }

    public function saveFaqCategory($request)
    {
        try {
            $data = ['name' => $request['name']];
            $this->repo->createFaqCategory($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getFaqCategoryById(int $id): FaqCategoryModel
    {
        return FaqCategoryModel::findOrFail($id);
    }

    public function updateFaqCategory(int $id, array $data): bool
    {
        return $this->repo->updateFaqCategory($id, $data);
    }

    //=============================== Faq List ============================== 

    public function getfaqList($request)
    {
        $emailTemplateList = $this->repo->faqList($request);
        return $emailTemplateList;
    }

    public function saveFaq($request)
    {
        try {
            $this->repo->insertFaqs($request);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getFaqById(int $id): FaqModel
    {
        return FaqModel::with('faqCategory')->findOrFail($id);
    }

    public function updateFaq(int $id, array $data): bool
    {
        return $this->repo->updateFaq($id, $data);
    }

    //=============================== Pages ==============================

    public function getpageList($request = null)
    {
        $emailTemplateList = $this->repo->pageList($request);
        return $emailTemplateList;
    }

    public function getPagesById(int $id): PagesModel
    {
        return PagesModel::findOrFail($id);
    }

    public function updatePage($id, array $data)
    {
        return $this->repo->updatePages($id, $data);
    }

    //=============================== Global Settings ==============================


    public function getGroupedSettings()
    {
        return $this->repo->getAllGrouped();
    }

    public function getValues()
    {
        return $this->repo->getAllValues();
    }

    public function updateChangedSettings($values, $originalValues)
    {
        foreach ($values as $id => $value) {

            if (!array_key_exists($id, $originalValues)) {
                continue;
            }

            if ($originalValues[$id] != $value) {
                $this->repo->updateValue($id, $value);
            }
        }
    }
}
