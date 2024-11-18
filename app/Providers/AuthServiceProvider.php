<?php

namespace App\Providers;

use App\Models\Fichero;
use App\Models\User;
use App\Policies\FicheroPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Las políticas del modelo para la aplicación.
     *
     * @var array
     */
    protected $policies = [
        Fichero::class => FicheroPolicy::class,
    ];

    /**
     * Registra las políticas y los Gates.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('upload', function (User $user) {
            return $user->hasPermission('upload');
        });

        Gate::define('delete', function (User $user, Fichero $file) {
            return $user->hasPermission('delete') || $user->id === $file->user_id;
        });

        Gate::define('restore', function (User $user) {
            return $user->hasPermission('restore');
        });

        Gate::define('forceDelete', function (User $user) {
            return $user->hasPermission('forceDelete');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
        
        Gate::before(function (User $user, $ability) {
            // Si el usuario es administrador, concede acceso automáticamente a todas las habilidades
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}