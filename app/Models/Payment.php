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
        'billing_id',
        'transfer_time',
        'link_prove',
        'payment_billing',
        'notes',
        'verified',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    public function billings() {
        return $this->belongsTo(Billing::class, 'billing_id', 'id');
    }
    public function payment_detail() {
        return $this->hasMany(Payment_Detail::class, 'payment_id', 'id');
    }
    // public function payment_others() {
    //     return $this->hasMany('App\Models\Payment_Others');
    // }
}
