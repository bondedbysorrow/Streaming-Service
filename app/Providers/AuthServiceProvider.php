<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // <-- DESCOMENTADO: Necesario para usar Gate
use App\Models\User;                 // <-- AÑADIDO: Necesario para el type hint en el Gate
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // --- 👇 GATE DEFINIDO AQUÍ 👇 ---
        // Define un "Gate" llamado 'view-admin-dashboard' (puedes cambiar el nombre)
        // Este Gate recibe el usuario autenticado ($user)
        // y retorna true solo si la columna 'is_admin' de ese usuario es true (o 1)
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->is_admin; // Revisa la columna que añadimos con la migración
        });

        // Puedes definir más Gates aquí para otros permisos si los necesitas
        // Gate::define('manage-tickets', function (User $user) {
        //     return $user->is_admin;
        // });
        // --- 👆 FIN GATE DEFINIDO 👆 ---
    }
}