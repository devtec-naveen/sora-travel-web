<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqModel extends Model
{
    protected $table = "faqs";
    protected $fillable = ['c_id', 'question', 'answer','status'];


    public function faqCategory()
    {
        return $this->belongsTo(\App\Models\FaqCategoryModel::class,'c_id');
    }
}
