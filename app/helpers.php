<?php

use Illuminate\Support\Facades\Hash;

if (! function_exists('hash_password')) {
    /**
     * Hashes a password using the configured driver and an optional pepper.
     */
    function hash_password(string $password): string
    {
        $pepper = config('app.auth_pepper');

        return Hash::make($password.$pepper);
    }
}

if (! function_exists('check_password')) {
    /**
     * Checks a password against a hash using the configured driver and pepper.
     */
    function check_password(string $password, string $hash): bool
    {
        $pepper = config('app.auth_pepper');

        return Hash::check($password.$pepper, $hash);
    }
}
