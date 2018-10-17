<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\App;

trait PlatformTrait
{
    public function isDev(): bool
    {
        return App::environment(['local', 'staging']);
    }
}