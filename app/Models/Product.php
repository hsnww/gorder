<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name_en',
        'name_ar',
        'price',
        'photo',
        'barcode',
    ];


    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }


    public function warehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }

}
