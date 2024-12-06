<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AgendaSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 1; $i++) {
            $data = [
                'judul'            => $faker->sentence(3),
                'nama_pelanggan'   => $faker->name,
                'lokasi'           => $faker->address,
                // Set tanggal kegiatan minimal hari ini
                'tanggal_kegiatan' => $faker->dateTimeBetween('now', '+2 day')->format('Y-m-d'),
                // Set jam kegiatan agar mengikuti hari ini
                'jam_kegiatan'     => $faker->dateTimeBetween('now', '23:59:59')->format('H:i:s'),
                'personel_desnet'  => json_encode([$faker->randomDigitNotNull, $faker->randomDigitNotNull]),
                'lampiran'         => $faker->optional()->lexify('file_????.pdf'),
                'link'             => $faker->optional()->url,
                // Set tgl_reminder minimal hari ini
                'tgl_reminder'     => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'created_at'       => $faker->dateTime()->format('Y-m-d H:i:s'),
                'updated_at'       => $faker->dateTime()->format('Y-m-d H:i:s'),
            ];
        
            // Masukkan $data ke database menggunakan model Anda
        }
        

            // Insert data into the agendas table
            $this->db->table('agendas')->insert($data);
        }
    }

