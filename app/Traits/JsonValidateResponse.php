<?php

namespace App\Traits;

use App\Facades\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Validation\ValidationException;

/**
 * Trait JsonValidateResponse
 * @author sheenazien8
 */
trait JsonValidateResponse
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            Response::clientError(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            Response::clientError(['message' => 'This action is unauthorized.'], JsonResponse::HTTP_UNAUTHORIZED)
        );
    }
}
