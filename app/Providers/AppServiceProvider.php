<?php

namespace App\Providers;

use App\Models\Fichero;
use App\Policies\FicheroPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Fichero::class => FicheroPolicy::class, // Asegúrate de que esta línea esté presente
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
