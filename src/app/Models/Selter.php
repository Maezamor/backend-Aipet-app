<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selter extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "selters";
    public $incrementing = true;
    public $timestamps = true;
}
