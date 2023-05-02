<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChapterPointAspect extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'chapter_point_aspect';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'chapter_id',
        'point_aspect_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    
}
