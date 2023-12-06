<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "group",
        "sterlisasi",
        "age",
        "rescue_story_1",
        "rescue_story_2",
        "rescue_story_3",
        "request_story"
    ];
}
