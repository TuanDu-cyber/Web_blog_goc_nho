<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
    // Tạo slug từ tên trước khi validate để kiểm tra trùng
    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => Str::slug((string) $this->input('name'))]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            // khi sửa, bỏ qua chính danh mục đang sửa
            'slug' => ['required', Rule::unique('categories', 'slug')->ignore($this->route('category'))],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.max'      => 'Tên danh mục tối đa 50 ký tự.',
            'slug.required' => 'Tên danh mục không hợp lệ.',
            'slug.unique'   => 'Danh mục này đã tồn tại.',
        ];
    }
}
