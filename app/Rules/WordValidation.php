<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WordValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Example: Reject words longer than 30 characters
        $key=explode(' ', $value);

        foreach ($key as $value) {
            if (strlen($value) > 30) {
                $fail('The :attribute must be a valid word.');
                return;
            }
        }
        // Example: Reject words with repeated characters more than twice
        if (preg_match('/(.)\1{5,}/', $value)) {
            $fail('The :attribute contains somany repeated characters.');
            return;
        }

        // Example: Reject words with random sequences (you can define your own logic here)
        if (preg_match('/[a-zA-Z]{10,}/', $value)) {
            $fail('The :attribute contains invalid patterns.');
            return;
        }
    }
}
