<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'menu';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'url',
        'icon',
        'menuparent_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    //each category might have one parent
    public function parent() {
        return $this->belongsToOne(static::class, 'menuparent_id');
    }
    
    //each category might have multiple children
    public function children() {
        return $this->hasMany(static::class, 'menuparent_id')->orderBy('name', 'asc');
    }
}
