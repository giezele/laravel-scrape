<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class JobCreationRequest
 *
 * @package App\Http\Requests
 */
class JobCreationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'urls' => 'required|array',
            'selectors' => 'required|string',
        ];
    }
}
