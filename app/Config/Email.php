<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'paulpogbaBB12345@gmail.com';
    public string $fromName   = 'Paul Pogba';

    public string $protocol   = 'smtp';  // Gunakan SMTP
    public string $SMTPHost   = 'smtp.gmail.com'; // Host SMTP Gmail
    public string $SMTPUser   = 'paulpogbaBB12345@gmail.com';  // Email Gmail
    public string $SMTPPass   = 'lswy hodh sjwn shrg';  // Password email Gmail
    public int    $SMTPPort   = 587;  // Port untuk TLS
    public string $SMTPCrypto = 'tls';  // Menggunakan TLS
    public bool   $SMTPKeepAlive = false;
    public int    $SMTPTimeout = 60;  // Timeout SMTP
    public string $mailType    = 'html';  // Kirim email sebagai HTML
    public string $charset     = 'UTF-8';
    public string $CRLF        = "\r\n";
    public string $newline     = "\r\n";
}
