<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdditionalNoticeRequest extends FormRequest
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
            'title_uz' => 'required|string',
            'title_ru' => 'required|string',
            'message_uz' => 'required|string',
            'message_ru' => 'required|string',
            'image' => 'image|mimes:jpg,png,jpeg|max:20480',
            'regions' => 'required|array'
        ];
    }
}
