<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "types";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'type',
        'size',
        'activity_level',
        'groups'
    ];

    public function Dog()
    {
        return $this->hasMany(Dog::class);
    }
}
