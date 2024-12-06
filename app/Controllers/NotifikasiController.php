<?php

namespace App\Controllers;

use App\Models\AgendaModel;
use App\Models\AgendaUserModel;
use Myth\Auth\Models\UserModel;
use CodeIgniter\Email\Email;

class NotifikasiController extends BaseController
{
    protected $agendaModel;
    protected $agendaUserModel;
    protected $userModel;
    protected $email;
    protected $session; // Tambahkan ini untuk menyimpan session

    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->agendaUserModel = new AgendaUserModel();
        $this->userModel = new UserModel();
        $this->email = \Config\Services::email();
        $this->session = \Config\Services::session(); // Inisialisasi session
    }

    /**
     * Mengirim notifikasi email berdasarkan tgl_reminder
     */
    public function kirimReminder()
    {
        // Mendapatkan agenda yang tgl_reminder-nya adalah hari ini
        $today = date('Y-m-d');
        $agendas = $this->agendaModel->where('tgl_reminder', $today)->findAll();

        foreach ($agendas as $agenda) {
            // Mendapatkan user terkait berdasarkan agenda_users
            $agendaUsers = $this->agendaUserModel->where('agenda_id', $agenda['id'])->findAll();

            foreach ($agendaUsers as $agendaUser) {
                // Mendapatkan data user dari tabel users menggunakan Myth\Auth
                $user = $this->userModel->find($agendaUser['user_id']);

                if ($user) {
                    // Atur email
                    $this->email->setFrom('paulpogbaBB12345@gmail.com', 'Paul Pogba');
                    $this->email->setTo($user->email); // Gunakan notasi objek
                    $this->email->setSubject('Reminder Agenda: ' . $agenda['judul']);
                    $this->email->setMessage("
                        <h3>Reminder Agenda</h3>
                        <p>Halo, {$user->username}</p> <!-- Gunakan notasi objek -->
                        <p>Berikut detail agenda yang perlu Anda hadiri:</p>
                        <ul>
                            <li>Judul: {$agenda['judul']}</li>
                            <li>Nama Pelanggan: {$agenda['nama_pelanggan']}</li>
                            <li>Lokasi: {$agenda['lokasi']}</li>
                            <li>Tanggal Kegiatan: {$agenda['tanggal_kegiatan']}</li>
                            <li>Jam Kegiatan: {$agenda['jam_kegiatan']}</li>
                            <li>Link: {$agenda['link']}</li>
                        </ul>
                        <p>Mohon jangan lupa untuk hadir tepat waktu.</p>
                    ");

                    // Kirim email dan log hasil
                    if ($this->email->send()) {
                        // Set flashdata untuk berhasil
                        $this->session->setFlashdata('success', 'Notifikasi berhasil dikirim ke email personil meeting  ');
                    } else {
                        // Set flashdata untuk gagal
                        $this->session->setFlashdata('error', 'Gagal mengirim notifikasi ke: ' . $user->email);
                        log_message('error', $this->email->printDebugger(['headers', 'subject', 'body']));
                    }
                }
            }
        }

        // Redirect atau tampilkan view
        return redirect()->to('/agenda'); // Sesuaikan dengan URL tujuan Anda
    }

    public function kirimReminderApi()
    {
        $today = date('Y-m-d');
        $agendas = $this->agendaModel->where('tgl_reminder', $today)->findAll();

        if (empty($agendas)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No agendas found for today'
            ]);
        }

        $failedEmails = [];
        $successEmails = [];

        foreach ($agendas as $agenda) {
            $agendaUsers = $this->agendaUserModel->where('agenda_id', $agenda['id'])->findAll();

            foreach ($agendaUsers as $agendaUser) {
                $user = $this->userModel->find($agendaUser['user_id']);

                if ($user) {
                    $this->email->setFrom('paulpogbaBB12345@gmail.com', 'Paul Pogba');
                    $this->email->setTo($user->email);
                    $this->email->setSubject('Reminder Agenda: ' . $agenda['judul']);
                    $this->email->setMessage("
                        <h3>Reminder Agenda</h3>
                        <p>Halo, {$user->username}</p>
                        <p>Berikut detail agenda yang perlu Anda hadiri:</p>
                        <ul>
                            <li>Judul: {$agenda['judul']}</li>
                            <li>Nama Pelanggan: {$agenda['nama_pelanggan']}</li>
                            <li>Lokasi: {$agenda['lokasi']}</li>
                            <li>Tanggal Kegiatan: {$agenda['tanggal_kegiatan']}</li>
                            <li>Jam Kegiatan: {$agenda['jam_kegiatan']}</li>
                            <li>Link: {$agenda['link']}</li>
                        </ul>
                        <p>Mohon jangan lupa untuk hadir tepat waktu.</p>
                    ");

                    if ($this->email->send()) {
                        $successEmails[] = $user->email;
                    } else {
                        $failedEmails[] = $user->email;
                        log_message('error', $this->email->printDebugger(['headers', 'subject', 'body']));
                    }
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Email notification process completed',
            'details' => [
                'success' => $successEmails,
                'failed' => $failedEmails,
            ]
        ]);
    }
}
