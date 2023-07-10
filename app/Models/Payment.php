<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'payment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transfer_time',
        'link_prove',
        'payment_billing',
        'notes',
        'notes_admin',
        'verified',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];


    public function payment_detail()
    {
        return $this->hasMany(Payment_Detail::class, 'payment_id', 'id');
    }

    // public function payment_others()
    // {
    //     // return $this->hasMany('App\Models\Payment_Others');
    //     return $this->hasMany(Payment_Other::class, 'payment_id', 'id');
    // }
}
