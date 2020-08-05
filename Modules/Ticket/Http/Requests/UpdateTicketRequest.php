<?php

namespace Modules\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required_without_all:description|max:255',
            'description' => 'required_without_all:title|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => "The title field can only have up to 255 characters",
            'description.max' => "The description field can only have up to 500 characters",
            '*.required_without_all' => 'You need to provide at least one of the fields',
        ];
    }
}
