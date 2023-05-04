<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use HasFactory;
    use SoftDeletes;
 
    protected $table = 'billing';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'billing',
        'month',
        'year',
        'created_at',
        'updated_at',
    ];
   	protected $dates = ['deleted_at'];
 
    public function students()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

}
