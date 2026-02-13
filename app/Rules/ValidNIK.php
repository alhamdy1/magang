<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Helpers\ValidationHelper;

class ValidNIK implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!ValidationHelper::isValidNIK($value)) {
            $fail('NIK tidak valid. Pastikan NIK terdiri dari 16 digit angka yang valid.');
        }
    }
}
