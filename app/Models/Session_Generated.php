<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session_Generated extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'session_generated';
    protected $primaryKey = 'id';

    protected $fillable = [
        'session_id',
        'teacher_id',
        'session_start',
        'session_end',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
