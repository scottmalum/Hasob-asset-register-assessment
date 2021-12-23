<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateAssignAssetRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'asset_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'due_date' => 'required|date',
            'location_id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];
    }
    /**
     * Get the validation messages for the rules that apply to the request.
     *
     * @return array
     */


    /**
     * If validator fails return the exception in json form
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()
            ->json(['errors' => $validator->errors()], 422));
    }

    /**
     * Called after validation of the form request
     *
     * @return array
     */
    public function validated()
    {
        return parent::validated();
    }
}
