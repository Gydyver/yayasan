<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'grade';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'lowest_poin',
        'highest_poin',
        'description',
        'notes',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
