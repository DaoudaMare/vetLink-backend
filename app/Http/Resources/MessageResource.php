<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'conversation_id' => $this->conversation_id,
            'attachment' => $this->when($this->attachment_path, [
                'filename' => $this->attachment_name,
                'path' => $this->attachment_path,
                'type' => $this->attachment_type,
                'download_url' => route('chat.download', ['message' => $this->id]),
            ]),
            'read_at' => $this->read_at,
            'is_read' => !is_null($this->read_at),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
