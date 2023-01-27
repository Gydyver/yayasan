<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'menu';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'url',
        'icon',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
