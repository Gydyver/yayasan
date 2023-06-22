<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_Other extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'payment_other';
    protected $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'nominal',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    public function payment_others()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
