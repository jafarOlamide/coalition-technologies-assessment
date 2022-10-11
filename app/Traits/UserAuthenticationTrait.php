<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

trait UserAuthenticationTrait 
{
    protected function createAuthenticationToken(User $user)
    {
        $token = $user->createToken($user->email)->accessToken;

        return $token;
    }
}