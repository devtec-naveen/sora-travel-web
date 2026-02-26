<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function emailTemplate(Request $request)
    {
        return view('admin.email-template.index');
    }

    public function faqCategoryList(Request $request)
    {
        return view('admin.faq-category.index');
    }

    public function faqCategoryAdd(Request $request)
    {
        return view('admin.faq-category.add');
    }
    
    public function faqList(Request $request)
    {
        return view('admin.faq.index');
    }

    public function addFaq()
    {
        return view('admin.faq.add');
    }

    public function viewFaq(string $id)
    {
        return view('admin.faq.view',['id' => $id]);
    }

    public function editFaq(string $id)
    {
        return view('admin.faq.edit',['id' => $id]);
       
    }

}
