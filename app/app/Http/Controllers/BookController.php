<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\BookFilter;
use App\Http\Requests\Book\FilterRequest;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
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

        $filter = app()->make(BookFilter::class, ['queryParams' => array_filter($data)]);

        $books = Book::with(request('with', []))
            ->filter($filter)
            ->paginate($request->get('per_page'));

        return BookResource::collection($books)
            ->response();
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $params = $request->all();

        if($request->hasFile('image')){
            $fileName = sprintf('%s.%s', time(), $request->image->extension());
            $request->image->storeAs('public/books', $fileName);

            $params['img_path'] = sprintf("/storage/books/%s", $fileName);

            unset($params['image']);
        }

        $book = new Book();

        $item = $book->create($params);

        if ($item) {
            if (isset($params['authors'])) {
                $authors = $params['authors'];
                $item->authors()->sync($authors);
            }

            if (isset($params['tags'])) {
                $tags = $params['tags'];
                $item->tags()->sync($tags);
            }
        }

        return BookResource::make($item)
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
        $book = Book::findOrFail($id);

        $book->authors()->detach();
        $book->tags()->detach();

        $book->delete();

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
        $book = Book::with(request('with', []))
            ->findOrFail($id);

        return BookResource::make($book)
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
        $params = $request->all();

        if($request->hasFile('image')){
            $fileName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/books', $fileName);

            $params['img_path'] = sprintf("/storage/books/%s", $fileName);

            unset($params['image']);
        }

        $book = Book::with(request('with', []))
            ->findOrFail($id);

        $book->update($params);

        $authors = $request->get('authors');
        $book->authors()->sync($authors);

        $tags = $request->get('tags');
        $book->tags()->sync($tags);

        return BookResource::make($book)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
