<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point_History extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'point_history';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'session_generated_id',
        'chapter_point_aspect_id',
        'grade_id',
        'point',
        'teacher_notes',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
