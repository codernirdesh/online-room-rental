<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordNotContainUserInfo implements ValidationRule
{
    protected string $name;
    protected string $email;

    /**
     * Minimum substring length to consider a "large part" of the user info.
     */
    protected int $minSubstringLength = 4;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $passwordLower = strtolower($value);

        // Check if the entire email is used as the password
        if ($passwordLower === strtolower($this->email)) {
            $fail('The :attribute must not be your email address.');
            return;
        }

        // Check against the full name and each word in the name
        $nameParts = array_filter(preg_split('/[\s\-\_\.]+/', $this->name));

        foreach ($nameParts as $part) {
            $partLower = strtolower($part);
            if (strlen($partLower) >= $this->minSubstringLength && str_contains($passwordLower, $partLower)) {
                $fail('The :attribute must not contain your name or a large part of it.');
                return;
            }
        }

        // Check against the email local part (before @)
        $emailLocal = strtolower(strstr($this->email, '@', true) ?: $this->email);

        if (strlen($emailLocal) >= $this->minSubstringLength && str_contains($passwordLower, $emailLocal)) {
            $fail('The :attribute must not contain your email address or a large part of it.');
            return;
        }

        // Also check significant substrings of the email local part
        // (e.g. if email is "johndoe123@...", check "john", "johndoe", etc.)
        if (strlen($emailLocal) > $this->minSubstringLength) {
            for ($len = $this->minSubstringLength; $len <= strlen($emailLocal); $len++) {
                for ($start = 0; $start + $len <= strlen($emailLocal); $start++) {
                    $sub = substr($emailLocal, $start, $len);
                    // Only flag substrings that are at least half the local part length
                    if ($len >= ceil(strlen($emailLocal) / 2) && str_contains($passwordLower, $sub)) {
                        $fail('The :attribute must not contain your email address or a large part of it.');
                        return;
                    }
                }
            }
        }
    }
}
