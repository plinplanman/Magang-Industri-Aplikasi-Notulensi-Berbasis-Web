<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Instansiasi Faker
        $faker = Factory::create();
        $userModel = new \Myth\Auth\Models\UserModel();
        $db = \Config\Database::connect();

        // Daftar departemen_id yang valid
        $departemenIds = [1, 3, 5, 6, 7, 8, 9, 10, 11];

        // Generate 2 users
        for ($i = 0; $i < 1; $i++) {
            $data = [
                'email'           => $faker->unique()->safeEmail,
                'username'        => $faker->unique()->userName,
                'departemen_id'   => $faker->randomElement($departemenIds), // Menggunakan ID departemen yang valid
                'fullname'        => $faker->name,
                'password_hash'   => password_hash('password123', PASSWORD_DEFAULT), // password dummy
                'reset_hash'      => null,
                'reset_at'        => null,
                'reset_expires'   => null,
                'activate_hash'   => null,
                'status'          => $faker->randomElement(['active', 'inactive']),
                'status_message'  => null,
                'active'          => $faker->boolean,  // nilai antara 0 atau 1
                'force_pass_reset'=> $faker->boolean,
                'deleted_at'      => null,
            ];

            // Insert data into the users table
            $userModel->skipValidation(true)->insert($data);

            // Get the ID of the inserted user
            $userId = $db->insertID();

            // Insert data into the auth_group_users table, associating the user with roles (IDs 1 3)
            $authGroupUsers = [

                ['user_id' => $userId, 'group_id' => 3]
            ];

            // Insert the associations into the auth_group_users table
            $db->table('auth_groups_users')->insertBatch($authGroupUsers);
        }
    }
}
