<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'usergroup';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    //1 = Superadmin
    //2 = Staff/Guru
    //3 = Peserta Didik

    public function users()
    {
        // return $this->hasMany('App\Models\Classes');
        return $this->hasMany(User::class,  'usergroup_id','id');
    }
}
