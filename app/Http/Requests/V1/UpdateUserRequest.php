<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if ($method == 'PUT') {
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'phone_number' => ['nullable', 'string', 'max:20'],
                'job' => ['nullable', 'string', 'max:255'],
                'company' => ['nullable', 'string', 'max:255'],
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
                'phone_number' => ['nullable', 'string', 'max:20'],
                'job' => ['nullable', 'string', 'max:255'],
                'company' => ['nullable', 'string', 'max:255'],
            ];
        }
    }
}
