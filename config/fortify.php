<?php

use Laravel\Fortify\Features;

return [

    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'lowercase_usernames' => true,

    // Redirección después del login
    'home' => '/',

    'prefix' => '',

    'domain' => null,

    'middleware' => ['web'],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],

    // Rutas de vistas de Fortify habilitadas
    'views' => true,

    // Características opcionales de Fortify
    'features' => [
        // Descomenta si quieres permitir el registro de usuarios
         Features::registration(),
        
        Features::resetPasswords(),
        // Puedes habilitar la verificación de correo si es necesario
        // Features::emailVerification(),
        
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        
        // Configuración de autenticación de dos factores
        Features::twoFactorAuthentication([
            'confirm' => true,               // Requiere confirmar MFA en cada sesión
            'confirmPassword' => true,       // Pide la contraseña antes de configurar MFA
            'window' => 1,                   // Establece la ventana de validez del código en 1 minuto
        ]),
    ],

];
