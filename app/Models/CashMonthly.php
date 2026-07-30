<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashMonthly extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cash_monthly';
    protected $guarded = ['id'];
}
