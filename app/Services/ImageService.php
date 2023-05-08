<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function store(array $images, int $postId): void
    {
        foreach ($images as $image)
        {
            $name = md5(Carbon::now() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('/images', $image, $name);
            Image::create([
                'url' => url(Storage::url($path)),
                'path' => $path,
                'post_id' => $postId
            ]);
        }
    }
}
