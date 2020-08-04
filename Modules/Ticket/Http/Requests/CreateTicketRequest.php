<?php

namespace Modules\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => "The title field is required for the ticket",
            'title.max' => "The title field can only have up to 255 characters",
            'description.required' => "The description field is required for the ticket",
            'description.max' => "The description field can only have up to 500 characters",
        ];
    }
}
