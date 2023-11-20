<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sterlisation extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "sterlisations";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];

    public function dog()
    {
        return $this->hasMany(Dog::class);
    }
}
