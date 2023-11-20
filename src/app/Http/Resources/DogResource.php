<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "age" => $this->age,
            "rescue_story" => $this->rescue_story,
            "character" => $this->character,
            "picture" => $this->picture,
            "type_id" => $this->type_id,
            "gender" => $this->gender,
            "steril_id" => $this->steril_id,
            "selter_id" => $this->selter_id,
        ];
    }
}
