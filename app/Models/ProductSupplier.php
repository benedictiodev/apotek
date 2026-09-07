<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_suppliers';
    protected $guarded = ['id'];

    public function Supplier()
    {
        return $this->belongsTo(MasterSupplier::class, 'supplier_id');
    }
}
