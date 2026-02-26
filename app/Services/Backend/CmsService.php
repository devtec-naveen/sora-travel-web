<?php

namespace App\Services\Backend;

use App\Repositories\Backend\CmsRepository;

class CmsService
{
    protected $repo;

    public function __construct(CmsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getfaqCategoryList($request)
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
    public function getemailTemplateList($request)
    {
        $emailTemplateList = $this->repo->emailTemplateList($request);
        return $emailTemplateList;
    }

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
}
