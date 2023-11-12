<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "roles";
    public $incrementing = true;
    public $timestamps = true;
}
