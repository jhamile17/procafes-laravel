<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';

    // PK real en tu tabla
    protected $primaryKey = 'brand_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name', 'description'
        ];
    public function products() {
        return $this->hasMany(
            Product::class,
             'brand_id', 
             'brand_id');
    }
   
}
