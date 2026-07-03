<?php

namespace App\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules(),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * Deliberately NOT unique — multiple accounts (e.g. a person's arbo
     * account and their linked employer account) can share the same email.
     * Login uses `username`, which is the unique identifier.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function emailRules(): array
    {
        return ['required', 'string', 'email', 'max:255'];
    }
}
