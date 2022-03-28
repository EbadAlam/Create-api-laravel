<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'user_id' => $this->id,
            'username' => $this->username,
            'user_fullname' => ucfirst($this->user_firstname) . ucfirst($this->user_lastname),
            'email' => $this->email,
            'user_image' => $this->user_image,
            'user_role' => $this->user_role,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
