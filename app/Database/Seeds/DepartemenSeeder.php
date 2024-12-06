<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['departemen_id' => 1, 'nama_departemen' => 'Jaringan'],
            ['departemen_id' => 3, 'nama_departemen' => 'Finance'],
            ['departemen_id' => 5, 'nama_departemen' => 'Hukum'],
            ['departemen_id' => 6, 'nama_departemen' => 'Perlengkapan'],
            ['departemen_id' => 7, 'nama_departemen' => 'Keuangan'],
            ['departemen_id' => 8, 'nama_departemen' => 'Administrasi'],
            ['departemen_id' => 9, 'nama_departemen' => 'Server'],
            ['departemen_id' => 10, 'nama_departemen' => 'Magang'],
            ['departemen_id' => 11, 'nama_departemen' => 'Sales Boy']
        ];

        // Insert data into the departemens table
        $this->db->table('departemens')->insertBatch($data);
    }
}
