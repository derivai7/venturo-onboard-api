<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Admin',
                'access' => json_encode([
                    "user" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "customer" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "category" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "product" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "promo" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "voucher" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "discount" => [
                        "view" => true,
                        "create" => true,
                        "delete" => true
                    ],
                    "sale" => [
                        "view" => true,
                        "create" => true,
                    ]
                ]),
            ], [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Kitchen',
                'access' => json_encode([
                    "customer" => [
                        "view" => true,
                        "create" => false,
                        "update" => false,
                        "delete" => false
                    ],
                    "category" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "product" => [
                        "view" => true,
                        "create" => true,
                        "update" => true,
                        "delete" => true
                    ],
                    "sale" => [
                        "view" => true,
                        "create" => true,
                    ]
                ]),
            ],
        ]);
    }
}
