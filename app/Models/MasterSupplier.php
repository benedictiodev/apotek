<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSupplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_suppliers';
    protected $guarded = ['id'];
}
