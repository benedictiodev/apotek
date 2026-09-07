<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchaseDetail extends Model
{
    use HasFactory;

    protected $table = 'product_purchase_details';
    protected $guarded = ['id'];

    public function Product() 
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Stock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stok_id');
    }
}
