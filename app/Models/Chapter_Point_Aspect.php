<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter_Point_Aspect extends Model
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

    // public function chapters()
    // {
    //     return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    // }

    public function pointAspects()
    {
        return $this->belongsTo(Point_Aspect::class, 'point_aspect_id', 'id');
    }

    public function chapterPointAspectHistory()
    {
        return $this->hasMany(Point_History::class, 'point_aspect_id', 'id');
    }
}
