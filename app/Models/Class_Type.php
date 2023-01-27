<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class class_type extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'class_type';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
        // 'created_at',
        // 'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
