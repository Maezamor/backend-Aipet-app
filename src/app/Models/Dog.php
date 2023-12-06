<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dog extends Model
{
    use SoftDeletes;
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
        return $this->belongsTo(Sterlisation::class, 'steril_id', 'id');
    }
}
