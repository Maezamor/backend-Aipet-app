<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Selter extends Model
{
    use SoftDeletes;

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
