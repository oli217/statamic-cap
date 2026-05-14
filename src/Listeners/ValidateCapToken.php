<?php

namespace StatamicCap\Listeners;

use Illuminate\Validation\ValidationException;
use LaravelCap\Facades\Cap;
use Statamic\Events\FormSubmitted;

class ValidateCapToken
{
    public function handle(FormSubmitted $event): void
    {
        $tokenField = config('statamic-cap.token_field', 'cap-token');
        $token = request()->input($tokenField);

        if (! Cap::verify((string) $token)) {
            throw ValidationException::withMessages([
                $tokenField => [__('statamic-cap::messages.validation_failed')],
            ]);
        }
    }
}
