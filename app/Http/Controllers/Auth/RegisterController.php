<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\UserAuthenticationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends ApiController
{

    use UserAuthenticationTrait;

    public function __invoke(RegistrationRequest $request)
    {
        $data = $request->all();
        
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password'])
        ]);

        $token = $this->createAuthenticationToken($user);

        return $this->respondCreated([
            'user'              => new UserResource($user),
            'token'             => $token,
            'token_expiration'  => "10 mins"
        ]);
    }
}
    