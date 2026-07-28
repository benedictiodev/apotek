<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_details';
    protected $guarded = ['id'];

    public function Uom()
    {
        return $this->belongsTo(MasterUom::class, 'uom_id');
    }
}
