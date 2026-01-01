<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $user = (new UserResource($this))->toArray($request);
        // Añadimos campos extra 
        return array_merge($user, ['email' => $this->email, 'created_at' => $this->created_at, ]);
    }
}
