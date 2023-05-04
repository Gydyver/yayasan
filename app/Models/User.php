<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'usergroup_id',
        'phone',
        'username',
        'password',
        'monthly_fee',
        'gender',
        'birth_date',
        'created_at',
        'updated_at',
    ];
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  * @var array<int, string>
    //  */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];
    protected $dates = ['deleted_at'];


    public function usergroups()
    {
        return $this->belongsTo(UserGroup::class, 'usergroup_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class,'teacher_id','id');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class,'student_id','id');
    }
    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}
