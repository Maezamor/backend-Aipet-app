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
        "picture",
        "type_id",
        "gender_id",
        "selter_id",
    ];
    //relasion definition

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, "type_id", "id");
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, "dender_id", "id");
    }
    public function selter(): BelongsTo
    {
        return $this->belongsTo(Selter::class, "selter_id", "id");
    }
}
