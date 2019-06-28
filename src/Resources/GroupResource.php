<?php

namespace DeadPixelStudio\Lockdown\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'has_users' => $this->has_users,
            'name' => $this->name,
            'slug' => $this->slug,
            'children' => $this->children,
            'users' => $this->users,
        ];
    }
}
