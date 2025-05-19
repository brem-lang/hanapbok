<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\RegistrationResponse as RegistrationResponseContract;

class RegistrationResponse implements RegistrationResponseContract
{
    public function toResponse($request)
    {
        return redirect()->route('index');
    }
}
