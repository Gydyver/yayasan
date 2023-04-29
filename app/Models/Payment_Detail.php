<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_Detail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'payment_detail';
    protected $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'student_id',
        'nominal',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    public function payment() {
        return $this->belongsTo('App\Models\Payment');
    }
    
    public function student() {
        return $this->belongsTo('App\Models\User');
    }
}
