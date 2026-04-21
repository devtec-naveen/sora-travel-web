<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategoryModel extends Model
{
    protected $table = "faq_categories";

    protected $fillable = ['name', 'status'];

    public function faqs()
    {
        return $this->hasMany(\App\Models\FaqModel::class, 'c_id');
    }
}
