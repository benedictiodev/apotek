<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashIn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_in';
    protected $guarded = ['id'];
}
