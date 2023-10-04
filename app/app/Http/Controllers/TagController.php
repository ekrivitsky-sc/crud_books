<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\TagFilter;
use App\Http\Requests\Tag\FilterRequest;
use App\Http\Requests\Tag\StoreRequest;
use App\Http\Requests\Tag\UpdateRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @param FilterRequest $request
     *
     * @return JsonResponse
     *
     * @throws BindingResolutionException
     */
    public function index(FilterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $filter = app()->make(TagFilter::class, ['queryParams' => array_filter($data)]);

        $books = Tag::with(request('with', []))
            ->orderBy('id', 'desc')
            ->filter($filter)
            ->paginate($request->get('per_page'));

        return TagResource::collection($books)
            ->response();
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $tag = new Tag();
        $item = $tag->create($request->all());

        return TagResource::make($item)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $tag = Tag::findOrFail($id);

        $tag->books()->detach();

        $tag->delete();

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $tag = Tag::with(request('with', []))
            ->findOrFail($id);

        return TagResource::make($tag)
            ->response();
    }

    /**
     * @param $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, UpdateRequest $request): JsonResponse
    {
        $tag = Tag::with(request('with', []))
            ->findOrFail($id);

        $tag->update($request->all());

        return TagResource::make($tag)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
