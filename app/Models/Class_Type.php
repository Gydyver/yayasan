<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Class_Type extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'class_type';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
        // 'created_at',
        // 'updated_at',
    ];
    protected $dates = ['deleted_at'];

    public function classes()
    {
        // return $this->hasMany('App\Models\Classes');
        return $this->hasMany(classes::class,  'class_type_id','id');
    }
}
