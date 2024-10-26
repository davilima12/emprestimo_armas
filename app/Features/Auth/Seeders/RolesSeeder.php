<?php

declare(strict_types=1);

namespace App\Features\Auth\Seeders;

use App\Features\Auth\Enums\Permissions;
use App\Features\Auth\Enums\Roles;
use App\Features\Auth\Models\Permission;
use App\Features\Auth\Models\Role;
use App\Features\Auth\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $this->createRoles();
        $this->createPermissions();
        $this->createRolesPermissions();
    }

    private function createRolesPermissions(): void
    {
        $this->assignPermissionsToRole(Roles::SUPER_ADMIN, [
            Permissions::CREATE_USER,
            Permissions::LIST_USERS,
            Permissions::FILTER_USERS,
            Permissions::CREATE_ADMIN,
            Permissions::VIEW_PROFILE,
            Permissions::UPLOAD_MEDIA,
            Permissions::CREATE_PRODUCT,
            Permissions::LIST_PRODUCTS,
            Permissions::CREATE_SALES,
            Permissions::CREATE_FINANCIAL_MOVEMENT,
            Permissions::CREATE_PROFIT_MARGIN,
            Permissions::VIEW_DASHBORAD,
            Permissions::UPDATE_PRODUCT_IMAGES,
        ]);
        $this->assignPermissionsToRole(Roles::ADMIN, [
            Permissions::CREATE_USER,
            Permissions::FILTER_USERS,
            Permissions::VIEW_PROFILE,
            Permissions::UPLOAD_MEDIA,
            Permissions::CREATE_PRODUCT,
            Permissions::LIST_PRODUCTS,
            Permissions::CREATE_SALES,
            Permissions::CREATE_FINANCIAL_MOVEMENT,
            Permissions::CREATE_PROFIT_MARGIN,
            Permissions::VIEW_DASHBORAD,
            Permissions::UPDATE_PRODUCT_IMAGES,
        ]);
        $this->assignPermissionsToRole(Roles::GERENTE, [
            Permissions::VIEW_PROFILE,
            Permissions::UPLOAD_MEDIA,
            Permissions::CREATE_PRODUCT,
            Permissions::LIST_PRODUCTS,
            Permissions::CREATE_SALES,
            Permissions::VIEW_DASHBORAD,
            Permissions::CREATE_PROFIT_MARGIN,
            Permissions::UPDATE_PRODUCT_IMAGES,
        ]);
        $this->assignPermissionsToRole(Roles::VENDEDOR, [
            Permissions::VIEW_PROFILE,
            Permissions::CREATE_SALES,
        ]);
        $this->assignPermissionsToRole(Roles::MARKETING, [
            Permissions::UPDATE_PRODUCT_IMAGES,
            Permissions::LIST_PRODUCTS,
            Permissions::VIEW_PROFILE,
        ]);
    }

    private function createRoles(): void
    {
        foreach ($this->getRoles() as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                $role
            );
        }
    }

    private function createPermissions(): void
    {
        foreach ($this->getPermissions() as $role) {
            Permission::updateOrCreate(
                ['id' => $role['id']],
                $role
            );
        }
    }

    /**
     * @param  Permissions[]  $permissions
     */
    private function assignPermissionsToRole(Roles $role, array $permissions): void
    {
        foreach ($permissions as $permission) {
            $this->assingPermissionToRole($permission, $role);
        }
    }

    public function assingPermissionToRole(Permissions $permission, Roles $role): void
    {
        RolePermission::where('role_id', $role->id())
            ->where('permission_id', $permission->id())
            ->firstOrCreate([
                'role_id' => $role->id(),
                'permission_id' => $permission->id(),
            ]);
    }

    public function getPermissions(): array
    {
        return [
            ['id' => Permissions::CREATE_USER->id(), 'name' => Permissions::CREATE_USER->value],
            ['id' => Permissions::LIST_USERS->id(), 'name' => Permissions::LIST_USERS->value],
            ['id' => Permissions::FILTER_USERS->id(), 'name' => Permissions::FILTER_USERS->value],
            ['id' => Permissions::CREATE_ADMIN->id(), 'name' => Permissions::CREATE_ADMIN->value],
            ['id' => Permissions::VIEW_PROFILE->id(), 'name' => Permissions::VIEW_PROFILE->value],
            ['id' => Permissions::UPLOAD_MEDIA->id(), 'name' => Permissions::UPLOAD_MEDIA->value],
            ['id' => Permissions::CREATE_PRODUCT->id(), 'name' => Permissions::CREATE_PRODUCT->value],
            ['id' => Permissions::LIST_PRODUCTS->id(), 'name' => Permissions::LIST_PRODUCTS->value],
            ['id' => Permissions::CREATE_SALES->id(), 'name' => Permissions::CREATE_SALES->value],
            ['id' => Permissions::CREATE_FINANCIAL_MOVEMENT->id(), 'name' => Permissions::CREATE_FINANCIAL_MOVEMENT->value],
            ['id' => Permissions::CREATE_PROFIT_MARGIN->id(), 'name' => Permissions::CREATE_PROFIT_MARGIN->value],
            ['id' =>  Permissions::VIEW_DASHBORAD->id(), 'name' => Permissions::VIEW_DASHBORAD->value],
            ['id' =>  Permissions::UPDATE_PRODUCT_IMAGES->id(), 'name' => Permissions::UPDATE_PRODUCT_IMAGES->value],

        ];
    }

    public function getRoles(): array
    {
        return [
            [
                'id' => Roles::SUPER_ADMIN->id(),
                'name' => Roles::SUPER_ADMIN->value,
                'display_name' => Roles::SUPER_ADMIN->displayName(),
                'description' => Roles::SUPER_ADMIN->description(),
            ],
            [
                'id' => Roles::ADMIN->id(),
                'name' => Roles::ADMIN->value,
                'display_name' => Roles::ADMIN->displayName(),
                'description' => Roles::ADMIN->description(),
            ],
            [
                'id' => Roles::GERENTE->id(),
                'name' => Roles::GERENTE->value,
                'display_name' => Roles::GERENTE->displayName(),
                'description' => Roles::GERENTE->description(),
            ],
            [
                'id' => Roles::VENDEDOR->id(),
                'name' => Roles::VENDEDOR->value,
                'display_name' => Roles::VENDEDOR->displayName(),
                'description' => Roles::VENDEDOR->description(),
            ],
            [
                'id' => Roles::CLIENTE->id(),
                'name' => Roles::CLIENTE->value,
                'display_name' => Roles::CLIENTE->displayName(),
                'description' => Roles::CLIENTE->description(),
            ],

            [
                'id' => Roles::MARKETING->id(),
                'name' => Roles::MARKETING->value,
                'display_name' => Roles::MARKETING->displayName(),
                'description' => Roles::MARKETING->description(),
            ],
        ];
    }
}
