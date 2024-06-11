<?php

namespace Database\Seeders;

use App\Models\RoleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    private $role;

    public function __construct()
    {
        $this->role = new RoleModel();
    }

    public function getId(string $name)
    {
        return $this->role->getIdByName($name);
    }

    public function run()
    {
        DB::table('user_auth')->insert([
            [
                'id' => Uuid::uuid4()->toString(),
                'user_roles_id' => $this->getId('Admin'),
                'name' => 'Bahtiar Rifa\'i',
                'email' => 'bahtiarderifai@gmail.com',
                'password' => Hash::make('12345678'),
                'updated_security' => date('Y-m-d H:i:s')
            ], [
                'id' => Uuid::uuid4()->toString(),
                'user_roles_id' => $this->getId('Kitchen'),
                'name' => 'Eko Jolodong',
                'email' => 'eko7@gmail.com',
                'password' => Hash::make('12345678'),
                'updated_security' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}

