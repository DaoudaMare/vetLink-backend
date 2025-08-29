<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->message,
            'user' => new UserResource($this->whenLoaded('user')),
            'conversation_id' => $this->conversation_id,
            'attachment' => $this->when($this->attachment_path, [
                'filename' => basename($this->attachment_path),
                'path' => $this->attachment_path,
                'type' => $this->attachment_type,
                'download_url' => route('chat.download', ['message' => $this->id]),
            ]),
            'read_at' => $this->is_read ? $this->updated_at : null,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
