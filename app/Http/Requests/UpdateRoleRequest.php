<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
        $roleId = $this->route('role')->id;

        return [
            'display_name' => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'icon'         => ['nullable', 'string', 'max:100'],
            'color'        => ['nullable', 'string', 'max:20'],

            // Permissions matrix
            'permissions'              => ['sometimes', 'array'],
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
        ];
    }
}
