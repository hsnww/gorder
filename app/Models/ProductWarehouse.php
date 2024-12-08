<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWarehouse extends Model
{
    protected $fillable = [
        'product_id',
        'provider_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

}
