<?php

namespace App\Core\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RequestValidatorService
{
    /**
     * Validate the request
     *
     * @param Request $request
     * @param array $rules
     * @return array validated data
     * @throws ValidationException if validation fails
     */
    public function validate(Request $request, array $rules): array
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }
}
