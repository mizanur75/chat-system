<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        return [
            'message' => $this->message['content'],
            'id' => $this->id,
            'type' => $this->type,
            'read_at' => $this->read_time($this),
            'send_at' => $this->created_at->diffForHumans()
        ];
    }

    private function read_time($_this){
        $read_at = $_this->type == 0 ? $_this->read_at: null;
        return $read_at ? $read_at->diffForHumans(): null;
    }
}
