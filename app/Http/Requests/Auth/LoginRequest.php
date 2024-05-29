<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request
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
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'max:191'],
            // 'is_public' => ['sometimes', 'boolean'],
        ];
    }

    // public function prepareForValidation()
    // {
    //     $input = $this->all();

    //     if (isset($input['is_public'])) {
    //         $input['is_public'] = $this->toBoolean($input['is_public']);
    //     }

    //     $this->replace($input);
    // }
}
