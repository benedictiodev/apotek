<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_customers';
    protected $guarded = ['id'];
}
