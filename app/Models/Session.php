<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'session';
    protected $primaryKey = 'id';

    protected $fillable = [
        'class_id',
        'name',
        'day',
        'time',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
