<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccess extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'useraccess';
    protected $primaryKey = 'id';

    protected $fillable = [
        'usergroup_id',
        'menu_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
