<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage users');
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:50|unique:users,matricule',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ];
    }
}
