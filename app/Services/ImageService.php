<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image as ImageIntervention;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function store(array $images, int $postId): void
    {
        foreach ($images as $image)
        {
            $name = $this->getImageName($image->getClientOriginalName(), $image->getClientOriginalExtension());
            $path = Storage::disk('public')->putFileAs('/images', $image, $name);
            $preview_url = '/images/prev_' . $name;
            ImageIntervention::make($image)->fit(100, 100)->save(storage_path('app/public/' . $preview_url));
            Image::create([
                'url' => url(Storage::url($path)),
                'preview_url' => url(Storage::url($preview_url)),
                'path' => $path,
                'post_id' => $postId
            ]);
        }
    }

    public function getImageName(string $name, string $extension): string
    {
        return md5(Carbon::now() . $name) . '.' . $extension;
    }
}
