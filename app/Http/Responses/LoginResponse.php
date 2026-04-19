<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $dashboardRoute = match ($user->role) {
            'administrator' => 'admin.dashboard',
            'operator'      => 'operator.dashboard',
            'kades'         => 'kades.dashboard',
            'perangkat'     => 'perangkat.dashboard',
            'resepsionis'   => 'resepsionis.dashboard',
            default         => 'dashboard',
        };

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(route($dashboardRoute));
    }
}
