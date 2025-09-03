<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TxtFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): void  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value->getClientOriginalExtension() !== 'txt') {
            $fail("The {$attribute} must have a .txt extension.");

            return;
        }

        $mime = $value->getMimeType();
        if (!in_array($mime, ['text/plain', 'application/x-empty', 'application/x-ndjson'])) {
            $fail("The {$attribute} must be a valid text file.");
        }
    }
}
