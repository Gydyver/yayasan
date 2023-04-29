<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paymnet extends Model
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

    public function billing() {
        return $this->belongsTo('App\Models\Billing');
    }
    public function payment_details() {
        return $this->hasMany('App\Models\Payment_Detail');
    }
    public function payment_others() {
        return $this->hasMany('App\Models\Payment_Others');
    }
}
