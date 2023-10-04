<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\AuthorFilter;
use App\Http\Requests\Author\FilterRequest;
use App\Http\Requests\Author\StoreRequest;
use App\Http\Requests\Author\UpdateRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
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

        $filter = app()->make(AuthorFilter::class, ['queryParams' => array_filter($data)]);

        $author = Author::with(request('with', []))
            ->orderBy('id', 'desc')
            ->filter($filter)
            ->paginate($request->get('per_page'));

        return AuthorResource::collection($author)
            ->response();
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $author = new Author();

        $item = $author->create($request->all());

        return AuthorResource::make($item)
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
        $author = Author::findOrFail($id);

        $author->books()->detach();

        $author->delete();

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
        $author = Author::with(request('with', []))
            ->findOrFail($id);

        return AuthorResource::make($author)
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
        $author = Author::with(request('with', []))
            ->findOrFail($id);

        $author->update($request->all());

        return AuthorResource::make($author)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
