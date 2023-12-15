<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adoption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dog_id',
        'user_id'
    ];
}
