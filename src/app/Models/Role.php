<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "roles";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'role'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'role_id');
    }
}
