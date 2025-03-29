<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpiar la caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear usuario por defecto
        $user = User::firstOrCreate([
            'email' => 'carlosandres0741@gmail.com',
        ], [
            'name' => 'Carlos Gomez',
            'password' => bcrypt('carlos#12345'),
        ]);

        // Asegurar que el rol `super_admin` existe
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // Asignar el rol al usuario
        $user->assignRole($role);

        // Llamar a otros seeders
        $this->call([
            ShieldSeeder::class, // Seeder de Filament Shield
        ]);
    }
}
