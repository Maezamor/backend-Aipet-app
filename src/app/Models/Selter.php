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

    protected $fillable = [
        "name",
        "picture",
        "address",
        "description",
        "sosial_media_1",
        "sosial_media_2",
        "sosial_media_3",
        "city",
        "phone",
        "lon",
        "let"
    ];

    public function Dog()
    {
        return $this->hasMany(Dog::class);
    }
}
