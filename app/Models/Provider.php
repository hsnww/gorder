<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function warehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }
    public function products()
    {
        return $this->hasMany(ProductWarehouse::class, 'provider_id');
    }

}
