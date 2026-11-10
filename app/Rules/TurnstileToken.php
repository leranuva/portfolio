<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class TurnstileToken implements ValidationRule
{
    public const ACTION = 'portfolio_contact';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = Http::asForm()->connectTimeout(2)->timeout(4)->withoutRedirecting()
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('contact.turnstile.secret'),
                    'response' => $value,
                ]);

            if ($response->successful() && $response->json('success') === true
                && $response->json('action') === self::ACTION
                && $response->json('hostname') === parse_url(config('app.url'), PHP_URL_HOST)) {
                return;
            }
        } catch (ConnectionException) {
            // Keep the form protected when Siteverify cannot be reached.
        }

        $fail('Please complete the security check and try again.');
    }
}
