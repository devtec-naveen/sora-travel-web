<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    //===================== Email Template =============================== 

    public function emailTemplate(Request $request)
    {
        return view('admin.email-template.index');
    }

    public function viewEmailTemplate(string $id)
    {
       return view('admin.email-template.view',['id' => $id]);
    }

    //===================== Faq Category ===============================     

    public function faqCategoryList(Request $request)
    {
        return view('admin.faq-category.index');
    }

    public function faqCategoryAdd(Request $request)
    {
        return view('admin.faq-category.add');
    }

    public function faqCategoryView(string $id)
    {
       return view('admin.faq-category.view',['id' => $id]);
    }

    public function faqCategoryEdit(string $id)
    {
       return view('admin.faq-category.edit',['id' => $id]);
    }

    //===================== Faq =============================== 
    
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

    //===================== Pages =============================== 

    public function pagesList(Request $request)
    {
        return view('admin.pages.index');
    }

    public function viewPages(string $id)
    {
        return view('admin.pages.view',['id' => $id]);
    }
    
    public function EditPages(string $id)
    {
        return view('admin.pages.edit',['id' => $id]);
    }  
    
    
    public function globalSettingList()
    {
        return view('admin.global-settings.index');

    }




}
