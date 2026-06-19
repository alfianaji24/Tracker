<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Billing;
use App\Models\Pelanggan;
use App\Models\TarifAir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ========== PERMISSIONS ==========
        $permissions = [
            // Users
            ['name' => 'View Users', 'slug' => 'view-users', 'description' => 'Can view users list'],
            ['name' => 'Create User', 'slug' => 'create-user', 'description' => 'Can create new user'],
            ['name' => 'Edit User', 'slug' => 'edit-user', 'description' => 'Can edit user'],
            ['name' => 'Delete User', 'slug' => 'delete-user', 'description' => 'Can delete user'],

            // Roles
            ['name' => 'View Roles', 'slug' => 'view-roles', 'description' => 'Can view roles list'],
            ['name' => 'Create Role', 'slug' => 'create-role', 'description' => 'Can create new role'],
            ['name' => 'Edit Role', 'slug' => 'edit-role', 'description' => 'Can edit role'],
            ['name' => 'Delete Role', 'slug' => 'delete-role', 'description' => 'Can delete role'],

            // Permissions
            ['name' => 'View Permissions', 'slug' => 'view-permissions', 'description' => 'Can view permissions list'],
            ['name' => 'Create Permission', 'slug' => 'create-permission', 'description' => 'Can create new permission'],
            ['name' => 'Edit Permission', 'slug' => 'edit-permission', 'description' => 'Can edit permission'],
            ['name' => 'Delete Permission', 'slug' => 'delete-permission', 'description' => 'Can delete permission'],

            // Tarif Air
            ['name' => 'View Tarif Air', 'slug' => 'view-tarif-air', 'description' => 'Can view tarif air list'],
            ['name' => 'Create Tarif Air', 'slug' => 'create-tarif-air', 'description' => 'Can create new tarif air'],
            ['name' => 'Edit Tarif Air', 'slug' => 'edit-tarif-air', 'description' => 'Can edit tarif air'],
            ['name' => 'Delete Tarif Air', 'slug' => 'delete-tarif-air', 'description' => 'Can delete tarif air'],

            // Billings
            ['name' => 'View Billings', 'slug' => 'view-billings', 'description' => 'Can view billings list'],
            ['name' => 'Create Billing', 'slug' => 'create-billing', 'description' => 'Can create new billing'],
            ['name' => 'Edit Billing', 'slug' => 'edit-billing', 'description' => 'Can edit billing'],
            ['name' => 'Delete Billing', 'slug' => 'delete-billing', 'description' => 'Can delete billing'],

            // Pelanggans
            ['name' => 'View Pelanggans', 'slug' => 'view-pelanggans', 'description' => 'Can view pelanggans list'],
            ['name' => 'Create Pelanggan', 'slug' => 'create-pelanggan', 'description' => 'Can create new pelanggan'],
            ['name' => 'Edit Pelanggan', 'slug' => 'edit-pelanggan', 'description' => 'Can edit pelanggan'],
            ['name' => 'Delete Pelanggan', 'slug' => 'delete-pelanggan', 'description' => 'Can delete pelanggan'],
        ];

        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[] = Permission::create($perm);
        }

        // ========== PERMISSION GROUPS ==========
        $userGroupPerms = array_filter($permissionModels, fn($p) => in_array($p->slug, ['view-users', 'create-user', 'edit-user', 'delete-user']));
        PermissionGroup::create([
            'name' => 'User Management',
            'description' => 'All user management permissions'
        ])->permissions()->attach(array_column($userGroupPerms, 'id'));

        $roleGroupPerms = array_filter($permissionModels, fn($p) => in_array($p->slug, ['view-roles', 'create-role', 'edit-role', 'delete-role']));
        PermissionGroup::create([
            'name' => 'Role Management',
            'description' => 'All role management permissions'
        ])->permissions()->attach(array_column($roleGroupPerms, 'id'));

        $tarifGroupPerms = array_filter($permissionModels, fn($p) => in_array($p->slug, ['view-tarif-air', 'create-tarif-air', 'edit-tarif-air', 'delete-tarif-air']));
        PermissionGroup::create([
            'name' => 'Tarif Air Management',
            'description' => 'All tarif air management permissions'
        ])->permissions()->attach(array_column($tarifGroupPerms, 'id'));

        $billingGroupPerms = array_filter($permissionModels, fn($p) => in_array($p->slug, ['view-billings', 'create-billing', 'edit-billing', 'delete-billing']));
        PermissionGroup::create([
            'name' => 'Billing Management',
            'description' => 'All billing management permissions'
        ])->permissions()->attach(array_column($billingGroupPerms, 'id'));

        $pelangganGroupPerms = array_filter($permissionModels, fn($p) => in_array($p->slug, ['view-pelanggans', 'create-pelanggan', 'edit-pelanggan', 'delete-pelanggan']));
        PermissionGroup::create([
            'name' => 'Pelanggan Management',
            'description' => 'All pelanggan management permissions'
        ])->permissions()->attach(array_column($pelangganGroupPerms, 'id'));

        // ========== ROLES ==========
        // Super Admin Role - All permissions
        $adminRole = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Administrator with full access'
        ]);
        $adminRole->permissions()->attach(array_column($permissionModels, 'id'));

        // Staff Role - Limited permissions
        $staffRole = Role::create([
            'name' => 'Staff',
            'slug' => 'staff',
            'description' => 'Staff with limited access'
        ]);
        $staffPermissions = array_filter($permissionModels, fn($p) => in_array($p->slug, [
            'view-billings',
            'create-billing',
            'edit-billing',
            'view-pelanggans',
            'create-pelanggan',
            'edit-pelanggan'
        ]));
        $staffRole->permissions()->attach(array_column($staffPermissions, 'id'));

        // ========== TARIF AIR DATA ==========
        // Tarif Rumah Tangga
        TarifAir::create([
            'kode' => 'RT',
            'golongan' => 'Rumah Tangga',
            'deskripsi' => 'Tarif standar untuk rumah tangga',
            'tarif_blok_1' => 5750,
            'tarif_blok_2' => 6750,
            'tarif_blok_3' => 7500,
            'tarif_blok_4' => 8000,
            'biaya_pemeliharaan' => 25000,
            'minimal_pakai_m3' => 5
        ]);

        // Tarif Bisnis
        TarifAir::create([
            'kode' => 'BS',
            'golongan' => 'Pelanggan Bisnis',
            'deskripsi' => 'Tarif untuk pelanggan bisnis',
            'tarif_blok_1' => 8500,
            'tarif_blok_2' => 10000,
            'tarif_blok_3' => 10500,
            'tarif_blok_4' => 11500,
            'biaya_pemeliharaan' => 50000,
            'minimal_pakai_m3' => 5
        ]);

        // ========== USERS ==========
        // Admin User
        User::create([
            'name' => 'Admin PDAM',
            'email' => 'admin@pdam.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Staff User
        User::create([
            'name' => 'Staff PDAM',
            'email' => 'staff@pdam.com',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
            'is_active' => true,
        ]);
    }
}
