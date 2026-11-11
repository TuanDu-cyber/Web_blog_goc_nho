<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q'        => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'tag'      => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.string'        => 'Từ khóa tìm kiếm không hợp lệ.',
            'q.max'           => 'Từ khóa tìm kiếm tối đa 100 ký tự.',
            'category.string' => 'Chuyên mục không hợp lệ.',
            'tag.string'      => 'Tag không hợp lệ.',
        ];
    }
}
