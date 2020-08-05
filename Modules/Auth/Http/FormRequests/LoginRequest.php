<?php

namespace Modules\Auth\Http\FormRequests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|max:50',
            'password' => 'required|max:50',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $failedResponseContent = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'Either the username or the password field is invalid',
            ],
        ];

        $response = new JsonResponse($failedResponseContent, 422);

        throw new ValidationException($validator, $response);
    }
}
