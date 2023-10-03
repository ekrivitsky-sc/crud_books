<?php

namespace App\Http\Requests\Author;

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
            'lname' => ['string'],
            'fname' => ['string'],
        ];
    }
}
