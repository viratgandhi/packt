<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'max:191'],
            'image' => ['nullable', 'image'],
            'author' => ['required', 'max:191'],
            'genre' => ['required', 'max:191'],
            'description' => ['nullable'],
            'isbn' => ['nullable'],
            'published_date' => ['nullable','date'],
            'publisher' => ['nullable'],
        ];
    }
}
