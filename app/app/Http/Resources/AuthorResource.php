<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'fname' => $this->fname,
            'lname' => $this->lname,
            $this->mergeWhen(in_array('books', $requestWith), [
                'books' => BookResource::collection($this->books),
            ]),
        ];
    }
}
