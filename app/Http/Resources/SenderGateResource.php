<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SenderGateResource extends JsonResource
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
            'id'=>$this->id,
            'gate_name' => $this->name,
        ];
    }
}
