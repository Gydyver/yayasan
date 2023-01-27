<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'class';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'teacher_id',
        'chapter_id',
        'class_type_id',
        'closed',
        'class_start',
        'class_end',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
