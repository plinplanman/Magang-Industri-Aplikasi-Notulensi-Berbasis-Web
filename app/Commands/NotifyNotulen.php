<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Controllers\NotifikasiNotulenController;

class NotifyNotulen extends BaseCommand
{
    protected $group       = 'Notifications';
    protected $name        = 'notify:notulen';
    protected $description = 'Generate notifications for today\'s agendas.';

    public function run(array $params)
    {
        $controller = new NotifikasiNotulenController();
        $controller->getNotifikasi();
        CLI::write('Notifications processed successfully!', 'green');
    }
}
