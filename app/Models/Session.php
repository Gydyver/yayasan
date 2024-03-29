<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'session';
    protected $primaryKey = 'id';

    protected $fillable = [
        'class_id',
        'name',
        'day',
        'time_start',//belum dirubah di class diagram
        'time_end',//belum dirubah di class diagram
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    
    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function sessionGenerated()
    {
        return $this->hasMany(Session_Generated::class, 'session_id', 'id');
    }

    // public function sessionGenerated()
    // {
    //     return $this->belongsTo(Classes::class, 'class_id', 'id');
    // }


}
