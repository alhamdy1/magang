<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSQLInjection implements ValidationRule
{
    /**
     * Suspicious SQL patterns
     */
    protected array $patterns = [
        '/(\bselect\b|\binsert\b|\bupdate\b|\bdelete\b|\bdrop\b|\bunion\b|\bexec\b|\bexecute\b)/i',
        '/(--)/',
        '/(\/\*|\*\/)/',
        '/(\bor\b\s+\d+\s*=\s*\d+)/i',
        '/(\band\b\s+\d+\s*=\s*\d+)/i',
        '/(\'|\")(\s*)(or|and)(\s*)(\'|\"|\d)/i',
        '/(\bwaitfor\b\s+\bdelay\b)/i',
        '/(\bbenchmark\b\s*\()/i',
        '/(\bsleep\b\s*\()/i',
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail('Input mengandung karakter yang tidak diizinkan.');
                return;
            }
        }
    }
}
