<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
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
    'phone'
   ];


   // inisilitation fo relasional

}
