<?php 
namespace App\Controllers;

use App\Models\AgendaModel;
use App\Models\AgendaUserModel;
use Myth\Auth\Models\UserModel; // Untuk mendapatkan data pengguna yang login

class NotifikasiNotulenController extends BaseController
{
    protected $agendaModel;
    protected $agendaUserModel;
    protected $userModel;

    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->agendaUserModel = new AgendaUserModel();
        $this->userModel = new UserModel();
    }

    /**
     * Mengambil agenda yang relevan untuk notifikasi berdasarkan pengguna yang login
     */
    public function getNotifikasi()
{
    // Mendapatkan agenda yang memiliki tgl_reminder hari ini
    $today = date('Y-m-d');
    $agendas = $this->agendaModel->where('tgl_reminder', $today)->findAll();

    // Menyiapkan data notifikasi
    $notifications = [];
    foreach ($agendas as $agenda) {
        $notifications[] = [
            'id' => $agenda['id'],  // Menambahkan ID agenda ke dalam notifikasi
            'judul' => $agenda['judul'],
            'tanggal_kegiatan' => $agenda['tanggal_kegiatan'],
            'jam_kegiatan' => $agenda['jam_kegiatan'],
        ];
    }

    // Menyimpan notifikasi ke session
    session()->set('notifications', $notifications);
}

}
