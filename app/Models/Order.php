<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function Orders()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
