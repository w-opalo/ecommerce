<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = [
            [
                'name' => 'Electronics',
                'slung' => 'electronics',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fashion',
                'slung' => 'fashion',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Home, Garden & Tools',
                'slung' => Str::slug('Home, Garden & Tools'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Books & Audio',
                'slung' => Str::slug('Books & Audio'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health & Beauty',
                'slung' => Str::slug('Health & Beauty'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('departments')->insert($department);
    }
}
