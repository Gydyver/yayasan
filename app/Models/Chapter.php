<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'chapter';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
    public function classes()
    {
        // return $this->hasMany('App\Models\Classes');
        return $this->hasMany(Classes::class, 'chapter_id','id');
    }
    // public function chapterPointAspects()
    // {
    //     return $this->hasMany(Chapter_Point_Aspect::class, 'chapter_id','id')->with('pointAspects');
    //     // return $this->hasMany(Chapter_Point_Aspect::class, 'chapter_id','id');
    // }
    
    public function chapterPointAspects()
    {
        // return $this->belongsToMany(Regions::class, 'regions_stores', 'stores_id', 'regions_id');
        return $this->belongsToMany(Point_Aspect::class, 'chapter_point_aspect', 'chapter_id', 'point_aspect_id');
        // return $this->hasMany(Chapter_Point_Aspect::class, 'chapter_id','id');
    }
}
