<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'nik'       => ['nullable', 'string', 'size:16', Rule::unique('users', 'nik')->ignore($userId)],
            'role'      => ['required', Rule::in(array_keys(User::ROLES))],
            'is_active' => ['sometimes', 'boolean'],
            'password'  => ['nullable', 'string', 'min:8'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan oleh pengguna lain.',
            'nik.size'       => 'NIK harus terdiri dari 16 digit.',
            'nik.unique'     => 'NIK sudah terdaftar.',
            'role.required'  => 'Role sistem wajib dipilih.',
            'role.in'        => 'Role yang dipilih tidak valid.',
            'password.min'   => 'Password minimal 8 karakter.',
        ];
    }
}
