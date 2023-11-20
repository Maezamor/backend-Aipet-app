<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "types";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'type',
        'kelompok',
        'group'
    ];

    public function Dog()
    {
        return $this->hasMany(Dog::class);
    }
}
