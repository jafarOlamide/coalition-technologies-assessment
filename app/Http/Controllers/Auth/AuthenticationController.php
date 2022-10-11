<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\UserAuthenticationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends ApiController
{
    use UserAuthenticationTrait;

    public function store(LoginRequest $request){
        
        $data = $request->all();
        
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password ?? null)) {
            throw ValidationException::withMessages(['email' => 'Invalid login credentials']);
        }

        $token = $this->createAuthenticationToken($user);

        return $this->respond([
            'user'              => new UserResource($user),
            'token'             => $token,
            'token_expiration'  => "10 mins"
        ]);
    }
}
