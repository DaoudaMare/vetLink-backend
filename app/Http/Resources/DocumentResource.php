<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserResource;
use App\Http\Resources\OrganizationResource;

class DocumentResource extends JsonResource
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
            'name' => $this->name,
            'size' => $this->getFileSize(),
            'size_human' => $this->getHumanFileSize(),
            'type' => $this->getFileExtension(),
            'user' => new UserResource($this->whenLoaded('user')),
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'download_url' => url('/api/documents/' . $this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get file size in bytes
     */
    private function getFileSize(): int
    {
        try {
            return Storage::disk('private')->size($this->path) ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get human readable file size
     */
    private function getHumanFileSize(): string
    {
        $size = $this->getFileSize();
        
        if ($size === 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($size, 1024));
        
        return round($size / (1024 ** $power), 2) . ' ' . $units[$power];
    }

    /**
     * Get file extension
     */
    private function getFileExtension(): string
    {
        return strtoupper(pathinfo($this->name, PATHINFO_EXTENSION));
    }
}
