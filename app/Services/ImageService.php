<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
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
            $path = Storage::disk('public')->putFileAs('images', $image, $name);
            $preview_url = 'images/prev_' . $name;
            ImageIntervention::make($image)->fit(100, 100)->save(storage_path('app/public/' . $preview_url));
            Image::create([
                'url' => url('storage/' . $path),
                'preview_url' => url('storage/' . $preview_url),
                'path' => $path,
                'post_id' => $postId
            ]);
        }
    }

    public function delete(Collection $images, array $deleteImageIds): void
    {
        foreach ($images as $image)
        {
            if (in_array($image->id, $deleteImageIds))
            {
                Storage::disk('public')->delete($image->path);
                Storage::disk('public')->delete(str_replace('images/', 'images/prev_', $image->path));
                $image->delete();
            }
        }
    }

    public function deleteUrls(array $images): void
    {
        foreach ($images as $image)
        {
            $removeStr = request()->root() . '/storage/';
            $path = str_replace($removeStr, '', $image);
            Storage::disk('public')->delete($path);
        }
    }

    public function getImageName(string $name, string $extension): string
    {
        return md5(Carbon::now() . $name) . '.' . $extension;
    }
}
