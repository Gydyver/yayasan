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
        'billing_id',
        'student_id',
        'nominal',
        'notes',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    public function billings()
    {
        return $this->belongsTo(Billing::class, 'billing_id', 'id');
    }

    public function payment_detail()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function studentPayment()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    // public function studentBilling() {
    //     return $this->belongsTo(Billing::class, ['student_id', 'month', 'year'], ['id', 'month', 'year']);
    //     // 'student_id','id'  
    // }

    //  public function studentBilling() {
    //     return $this->belongsTo(User::class, 'student_id','id');
    // }

    // public function studentBillingwithPayment() {
    //     return $this->belongsTo(User::class, 'student_id','id');
    // }
}
