<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Essential_Data extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'essential_data';
    protected $primaryKey = 'id';

    protected $fillable = [
        'semester_start',
        'semester_end',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];
}
