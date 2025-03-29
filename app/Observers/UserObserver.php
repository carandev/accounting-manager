<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Se limpia el cache de los permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Asegurar que el rol `super_admin` existe
        $role = Role::firstOrCreate([
            'name' => 'panel_user',
            'guard_name' => 'web',
        ]);

        // Asignar el rol al usuario
        $user->assignRole($role);
    }
}
