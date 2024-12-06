<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        // Izinkan asal dari aplikasi Flutter (sesuaikan jika Anda menggunakan IP atau domain lain)
'allowedOrigins' => ['http://localhost:8080', 'http://127.0.0.1:8080'],

        // Anda juga bisa menambahkan pola regex jika domain dinamis
        'allowedOriginsPatterns' => [],

        // Jika membutuhkan kredensial seperti cookies, set ke true
        'supportsCredentials' => true,

        // Izinkan semua header umum yang digunakan aplikasi Flutter
        'allowedHeaders' => ['Content-Type', 'Authorization', 'X-Requested-With'],

        // Header yang diekspos ke aplikasi client
        'exposedHeaders' => [],

        // Metode HTTP yang diizinkan
        'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

        // Waktu cache untuk preflight request
        'maxAge' => 7200,
    ];
}
