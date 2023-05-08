<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\ImageService;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\Image\StoreRequest;

class ImageController extends Controller
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $image = $request->validated()['image'];
        $name = $this->imageService->getImageName($image->getClientOriginalName(), $image->getClientOriginalExtension());
        $path = Storage::disk('public')->putFileAs('/images/content', $image, $name);
        return response()->json(['url' => url('/storage/' . $path)]);
    }
}
