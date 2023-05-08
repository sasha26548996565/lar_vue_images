<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\Api\Post\StoreRequest;
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
        return new PostResource(Post::first());
    }

    public function store(StoreRequest $request): PostResource
    {
        $params = $request->validated();
        $images = $params['images'];
        unset($params['images']);
        $post = Post::create($params);
        $this->imageService->store($images, $post->id);
        return new PostResource($post);
    }
}
