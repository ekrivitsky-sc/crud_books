<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $requestWith = $request->with ?? [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->img_path,
            'year' => $this->year,
            $this->mergeWhen(in_array('authors', $requestWith), [
                'authors' => AuthorResource::collection($this->authors),
            ]),
            $this->mergeWhen(in_array('tags', $requestWith), [
                'tags' => TagResource::collection($this->tags),
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
