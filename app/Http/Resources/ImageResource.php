<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'preview_url' => $this->preview_url,
            'size' => Storage::disk('public')->size($this->path),
            'name' => str_replace('images/', '', $this->path),
        ];
    }
}
