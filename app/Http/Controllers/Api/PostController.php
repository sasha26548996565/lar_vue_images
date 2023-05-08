<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Response;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\Api\Post\UpdateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(): PostResource
    {
        return new PostResource(Post::orderBy('id', 'DESC')->first());
    }

    public function update(UpdateRequest $request, Post $post): Response
    {
        $params = $request->validated();
        $images = $params['images'] ?? null;
        $deleteImageIds = $params['deleteImageIds'] ?? null;
        $deleteImageUrls = $params['deleteImageUrls'] ?? null;
        unset($params['images'], $params['deleteImageIds'], $params['deleteImageUrls']);
        $post->update($params);
        if ($deleteImageIds)
        {
            $this->imageService->delete($post->images, $deleteImageIds);
        }

        if ($deleteImageUrls)
        {
            $this->imageService->deleteUrls($deleteImageUrls);
        }

        if ($images)
        {
            $this->imageService->store($images, $post->id);
        }
        return response(['status' => 'Ok']);
    }
}
