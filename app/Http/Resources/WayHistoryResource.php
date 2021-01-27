<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WayHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
       // $townshipid= $this->item->township_id;
       // echo $townshipid;die();
        return [
        'item'=>$this->item,
         'township_name'=>$this->when($this->item->township()->exists(), $this->item->township)
        
        ];
        
    }
}
