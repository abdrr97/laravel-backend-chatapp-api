<?php

namespace App\Http\Resources;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'user_id' => auth()->id() == $this->user_id
                ? new UserResource(User::find($this->other_id))
                : new UserResource(User::find($this->user_id)),
            'messages' => MessageResource::collection($this->messages),
            // 'created_at' => $this->created_at,
        ];
    }
}
