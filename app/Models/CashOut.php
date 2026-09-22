<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashOut extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_out';
    protected $guarded = ['id'];
}
