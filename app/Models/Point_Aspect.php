<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point_Aspect extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'point_aspect';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];


    public function pointAspects()
    {
        return $this->hasMany(Chapter_Point_Aspect::class, 'point_aspect_id', 'id');
    }


    public function chapterPointAspects()
    {
        // return $this->belongsToMany(Regions::class, 'regions_stores', 'stores_id', 'regions_id');
        return $this->belongsToMany(Chapter::class, 'chapter_point_aspect',  'point_aspect_id', 'chapter_id');
        // return $this->hasMany(Chapter_Point_Aspect::class, 'chapter_id','id');
    }
}
