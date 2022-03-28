<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;


class PostResource extends JsonResource
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
            'post_id' => $this->id, 
            'category' => new Categoryresource($this->category), 
            'post_title' => $this->post_title, 
            'post_author' => $this->post_author, 
            'post_image' => $this->post_image, 
            'post_date' => $this->post_date, 
            'post_content' => $this->post_content, 
            'post_tags' => $this->post_tags, 
            'post_status' => $this->post_status, 
            'created_at' => $this->created_at->diffForHumans(), 
            'updated_at' => $this->updated_at->diffForHumans(), 
        ];
    }
}
