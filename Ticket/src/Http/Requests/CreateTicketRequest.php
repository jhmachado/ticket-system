<?php

namespace Ticket\Http\Requests;

final class CreateTicketRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => "A title is required for the ticket",
            'title.max' => "A title can only have up to 255 characters",
        ];
    }
}
