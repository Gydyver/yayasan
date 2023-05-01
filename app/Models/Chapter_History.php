<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChapterHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'chapter_history';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'chapter_id',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
