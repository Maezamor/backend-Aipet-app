<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model implements Authenticatable
{
    use SoftDeletes;

    //inisialitation fungsionalitas target database
    protected $table = 'users';
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    //  set fillable to any variable will change
    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'address',
        'phone',
        'role_id',
        'picture'
    ];


    // inisilitation fo relasional

    // inisialitation fpr fungsi  Auth
    public function getAuthIdentifierName()
    {
        return 'username';
    }
    public function getAuthIdentifier()
    {
        return  $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }


    public function setRememberToken($value)
    {
        $this->token =  $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
