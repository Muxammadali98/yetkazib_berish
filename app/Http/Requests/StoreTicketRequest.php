<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'contract_id' => 'required|integer',
            'address' => 'required|string',
            'phone' => 'required|string',
            'additional_phone' => 'nullable|string',
            'comment' => 'nullable|string',
            'client_name' => 'required|string',
            'type' => 'required|integer',
            'product_name.*' => 'required|string',
            'article.*' => 'required|string',
            'model.*' => 'required|string',
            'quantity.*' => 'required|integer',
            'car_id' => 'required|integer|exists:cars,id',
        ];
    }
}
