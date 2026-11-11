<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComment extends FormRequest
{
    public function authorize(): bool
    {
        return true; // đã có middleware auth ở route
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'content.min'      => 'Bình luận tối thiểu 3 ký tự.',
            'content.max'      => 'Bình luận tối đa 1000 ký tự.',
        ];
    }
}
