<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "genders";
    public $incrementing = true;
    public $timestamps = true;
}
