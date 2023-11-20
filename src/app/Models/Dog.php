<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dog extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "dogs";
    public $incrementing = true;
    public $timestamps = true;
    //Access Variable
    protected $fillable = [
        "name",
        "age",
        "rescue_story",
        "character",
        "sterilisasi",
        "picture",
        "gender",
        "type_id",
        "selter_id",
        "steril_id"
    ];
    //relasion definition

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function selter()
    {
        return $this->belongsTo(Selter::class);
    }

    public function sterlisation()
    {
        return $this->belongsTo(Sterlisation::class);
    }
}
