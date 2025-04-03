<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
// use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;
use App\Enums\RolesEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::create(['name' => RolesEnum::User->value]);
        $vendorRole = Role::create(['name' => RolesEnum::Vendor->value]);
        $adminRole = Role::create(['name' => RolesEnum::Admin->value]);



        $approveVendors = Permission::create(['name' => PermissionEnum::ApproveVendor->value]);
        $BuyProduct = Permission::create(['name' => PermissionEnum::BuyProducts->value]);
        $SellProduct = Permission::create(['name' => PermissionEnum::SellProducts->value]);

        $userRole->syncPermissions([$BuyProduct]);
        $vendorRole->syncPermissions([$BuyProduct, $SellProduct]);
        $adminRole->syncPermissions([$approveVendors, $BuyProduct, $SellProduct]);
    }
}
