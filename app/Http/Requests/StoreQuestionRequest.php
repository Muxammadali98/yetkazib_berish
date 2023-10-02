<?php

namespace App\Http\Requests;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
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
            'title_uz' => 'required',
            'title_ru' => 'required',
            'pdf_title_uz' => 'required',
            'pdf_title_ru' => 'required',
            'text_uz' => Rule::when($this->type == Question::TYPE_WITH_A_BUTTON, 'required'),
            'text_ru' => Rule::when($this->type == Question::TYPE_WITH_A_BUTTON, 'required'),
        ];
    }
}
