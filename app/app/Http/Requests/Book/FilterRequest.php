<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['string'],
            'year' => ['integer', 'digits:4'],
            'author_id' => ['string'],
            'author_lname' => ['string'],
            'author_fname' => ['string'],
            'tag_name' => ['string'],
        ];
    }
}
