<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Helpers\ValidationHelper;

class IndonesianPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!ValidationHelper::isValidPhoneNumber($value)) {
            $fail('Nomor telepon tidak valid. Gunakan format Indonesia (08xx atau +628xx).');
        }
    }
}
