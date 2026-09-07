<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPurchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_purchases';
    protected $guarded = ['id'];

    public function Supplier()
    {
        return $this->belongsTo(MasterSupplier::class, 'supplier_id');
    }

    public function PurchaseDetail()
    {
        return $this->hasMany(ProductPurchaseDetail::class, 'product_purchase_id');
    }
}
