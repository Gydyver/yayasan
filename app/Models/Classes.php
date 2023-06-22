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
        'class_type_id',
        'closed',
        'class_start',
        'class_end',
        'created_at',
        'updated_at',
    ];

    public function teachers()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function studentClasses()
    {
        // return $this->hasMany('App\Models\Classes');
        return $this->hasMany(User::class,  'usergroup_id', 'id');
    }

    public function classTypes()
    {
        return $this->belongsTo(Class_Type::class, 'class_type_id', 'id');
    }

    public function sessions()
    {
        // return $this->hasMany('App\Models\Classes');
        return $this->hasMany(Session::class, 'class_id', 'id');
    }

    // public function sessionWithGeneratedData()
    // {
    //     // return $this->hasMany('App\Models\Classes');
    //     return $this->hasMany(Session::class, 'class_id','id')->with('sessionGenerated');
    // }

    // public function teacher_id() {
    //     return $this->belongsTo('App\Models\User');
    // }
    // public function chapter_id() {
    //     return $this->belongsTo('App\Models\Chapter');
    // }

    protected $dates = ['deleted_at'];
}
