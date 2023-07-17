<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session_Generated extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'session_generated';
    protected $primaryKey = 'id';

    protected $fillable = [
        'session_id',
        'teacher_id',
        'session_start',
        'session_end',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];


    public function sessionGenerated()
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }

    public function teacherData()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function pointHistory()
    {
        return $this->hasMany(Point_History::class, 'session_generated_id', 'id');
    }
}
