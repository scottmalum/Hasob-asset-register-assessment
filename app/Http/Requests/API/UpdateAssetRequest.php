<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAssetRequest extends FormRequest
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
            'name' => 'string|min:3|max:150',
            'serial' => 'numeric',
            'description' => 'string|min:10|max:250',
            'quantity' => 'numeric|min:1',
            'purchase_price' => 'numeric',
            'purchase_date' => 'date',
            'warranty_exp_date' => 'string',
            'picture_url' => 'image|mimes:jpg,png',
            'category_id' => 'numeric',
            'vendor_id' => 'numeric',
            'location_id' => 'numeric',
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
