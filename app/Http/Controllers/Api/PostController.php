<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\StoreRequest;
use App\Models\Post;
use App\Services\ImageService;

class PostController extends Controller
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(StoreRequest $request)
    {
        $params = $request->validated();
        $images = $params['images'];
        unset($params['images']);
        $post = Post::create($params);
        $this->imageService->store($images, $post->id);
        return response()->json(['message' => 'success']);
    }
}
