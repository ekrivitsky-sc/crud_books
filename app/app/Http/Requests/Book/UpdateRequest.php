<?php

namespace App\Http\Requests\Book;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', Rule::unique(Book::class, 'name')
                ->using(function ($q) {
                    $q->where('id', '!=', $this->id);
                })
            ],
            'year' => ['required', 'integer', 'digits:4'],
            'img_path' => ['string', 'nullable'],
            'authors' => ['array'],
            'authors.*' => ['exists:authors,id'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id']
        ];
    }
}
