<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RateLimiter;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password'], $this->boolean('remember_me')))) {
            RateLimiter::hit($this->throttleKey(), config('throttle.login.retry', 5 * 60));

            throw new UnauthorizedHttpException('', __('auth.failed'));
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     *  Ensure this login request is not rate limited.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), config('throttle.login.max_attempt', 5)
        )) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw new TooManyRequestsHttpException($seconds, __('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]));
    }

    /**
     * Get rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
