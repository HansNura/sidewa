<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', 'unique:roles,slug', 'regex:/^[a-z0-9\-]+$/'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'icon'         => ['nullable', 'string', 'max:100'],
            'color'        => ['nullable', 'string', 'max:20'],
            'is_system'    => ['sometimes', 'boolean'],

            // Permissions matrix: permissions[module_id][can_view] = 1/0
            'permissions'            => ['sometimes', 'array'],
            'permissions.*.can_view'   => ['sometimes', 'boolean'],
            'permissions.*.can_create' => ['sometimes', 'boolean'],
            'permissions.*.can_edit'   => ['sometimes', 'boolean'],
            'permissions.*.can_delete' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'display_name.required' => 'Nama role wajib diisi.',
            'slug.required'         => 'Slug role wajib diisi.',
            'slug.unique'           => 'Slug sudah digunakan oleh role lain.',
            'slug.regex'            => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ];
    }
}
