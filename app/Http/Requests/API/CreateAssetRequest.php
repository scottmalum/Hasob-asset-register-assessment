<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateAssetRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:150',
            'serial' => 'required|numeric',
            'description' => 'required|string|min:10|max:250',
            'quantity' => 'numeric|min:1',
            'purchase_price' => 'required|numeric',
            'purchase_date' => 'required|date',
            'warranty_exp_date' => 'required|string',
            'picture_url' => 'image|mimes:jpg,png',
            'category_id' => 'required|numeric',
            'vendor_id' => 'required|numeric',
            'location_id' => 'required|numeric',
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
