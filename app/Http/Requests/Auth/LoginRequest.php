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
            'remember_me' => ['sometimes', 'boolean'],
        ];
    }

    public function prepareForValidation()
    {
        $input = $this->all();

        if (isset($input['remember_me'])) {
            $input['remember_me'] = $this->toBoolean($input['remember_me']);
        }

        $this->replace($input);
    }
}
