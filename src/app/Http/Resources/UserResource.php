<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // mengambil request
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'picture' => $this->picture,
            //if there token 
            'token' => $this->whenNotNull($this->token)
        ];
    }
}
