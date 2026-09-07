<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    protected $guarded = ['id'];

    public function ProductDetail()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }

    public function Category()
    {
        return $this->belongsTo(MasterProductCategory::class, 'product_category_id');
    }

    public function BaseUom() 
    {
        return $this->belongsTo(MasterUom::class, 'base_uom_id');
    }

    public function Stock()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }
}
