<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'participants' => UserResource::collection($this->whenLoaded('users')),
            'last_message' => $this->when($this->relationLoaded('messages') && $this->messages->isNotEmpty(), function() {
                $lastMessage = $this->messages->last();
                return [
                    'id' => $lastMessage->id,
                    'content' => $lastMessage->content,
                    'user' => new UserResource($lastMessage->user),
                    'created_at' => $lastMessage->created_at,
                    'has_attachment' => !is_null($lastMessage->attachment_path),
                ];
            }),
            'unread_count' => $this->when($this->relationLoaded('messages'), function() {
                return $this->messages()
                    ->where('user_id', '!=', auth()->id())
                    ->whereNull('read_at')
                    ->count();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
