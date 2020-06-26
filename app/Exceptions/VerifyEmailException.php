<?php

namespace App\Exceptions;

use App\User;
use Illuminate\Validation\ValidationException;

class VerifyEmailException extends ValidationException
{
    /**
     * @param  User $user
     * @return static
     */
    public static function forUser($user)
    {
        // https://laravel.com/docs/7.x/localization#using-translation-strings-as-keys

        return static::withMessages([
            'email' => [__('You must :linkOpen verify :linkClose your email first.', [
                'linkOpen' => '<a href="/email/resend?email='.urlencode($user->email).'">',
                'linkClose' => '</a>',
            ])],
        ]);
    }
}
