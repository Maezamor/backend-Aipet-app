<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sterlisation extends Model
{
    use SoftDeletes;

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
