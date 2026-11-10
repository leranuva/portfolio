<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Throwable;

class PublicSubmission
{
    public const TRACKING_FIELDS = ['utm_source', 'utm_medium', 'utm_campaign'];

    public static function emailRules(): array
    {
        return ['bail', 'required', 'string', 'max:254', 'not_regex:/[\r\n]/', 'email:rfc'];
    }

    public static function tracking(array $input): array
    {
        $tracking = [];

        foreach (self::TRACKING_FIELDS as $field) {
            $value = $input[$field] ?? null;
            if (is_string($value) && mb_strlen($value) <= 255) {
                $tracking[$field] = $value;
            }
        }

        return $tracking;
    }

    public static function recipientKey(mixed $email): string
    {
        return hash('sha256', is_string($email) ? strtolower(trim($email)) : '');
    }

    public static function once(string $form, array $payload, callable $store): mixed
    {
        $payload['email'] = self::recipientKey($payload['email']);
        $key = 'submission:'.$form.':'.hash('sha256', serialize($payload));

        $lock = Cache::lock($key, 600);
        if (! $lock->get()) {
            return null;
        }

        try {
            return $store();
        } catch (Throwable $exception) {
            $lock->release();
            throw $exception;
        }
    }
}
