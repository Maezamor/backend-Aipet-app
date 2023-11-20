<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "services";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        "picture",
        "name",
        "sosial_media_1",
        "sosial_media_2",
        "sosial_media_3",
        "description",
        "city",
        "address",
        "pone",
        'lon',
        'let'
    ];
}
