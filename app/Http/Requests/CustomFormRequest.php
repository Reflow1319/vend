<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CustomFormRequest extends FormRequest
{
    /**
     * Return a formatted error message in json if
     * request type is json.
     *
     * @param array $errors
     *
     * @return JsonResponse
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse([
                'error' => array_flatten($errors),
            ], 422);
        }
    }
}
