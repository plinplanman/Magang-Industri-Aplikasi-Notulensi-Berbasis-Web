<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaModel extends Model
{
    protected $table = 'agendas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul',
        'nama_pelanggan',
        'lokasi',
        'tanggal_kegiatan',
        'jam_kegiatan',
        'personel_desnet',
        'lampiran',
        'link',
        'tgl_reminder'
    ];
    protected $useTimestamps = true;

    public function getAllAgendas()
    {
        return $this->findAll(); // Mengambil semua data agenda
    }

    public function getUserAgendas($userId)
    {
        return $this->db->table('agenda_users')
            ->select('agendas.*')
            ->join('agendas', 'agendas.id = agenda_users.agenda_id')
            ->where('agenda_users.user_id', $userId)
            ->orderBy('agendas.tanggal_kegiatan', 'ASC')
            ->orderBy('agendas.jam_kegiatan', 'ASC')
            ->get()
            ->getResultArray();
    }



    public function getAllAgendasThisMonth()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        return $this->where('MONTH(tanggal_kegiatan)', $currentMonth)
            ->where('YEAR(tanggal_kegiatan)', $currentYear)
            ->orderBy('tanggal_kegiatan', 'ASC')
            ->orderBy('jam_kegiatan', 'ASC')
            ->findAll();
    }

    // Menambahkan metode untuk mengambil agenda mendatang untuk pengguna tertentu
    public function getUserUpcomingAgendas($userId)
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');

        return $this->db->table('agenda_users')
            ->select('agendas.*')
            ->join('agendas', 'agendas.id = agenda_users.agenda_id')
            ->where('agenda_users.user_id', $userId)
            ->groupStart()
            ->where('tanggal_kegiatan >', $today) // Agenda setelah hari ini
            ->orGroupStart()
            ->where('tanggal_kegiatan =', $today)
            ->where('jam_kegiatan >', $currentTime) // Agenda hari ini setelah waktu sekarang
            ->groupEnd()
            ->groupEnd()
            ->orderBy('agendas.tanggal_kegiatan', 'ASC')
            ->orderBy('agendas.jam_kegiatan', 'ASC')
            ->limit(5) // Batasi hanya 5 hasil
            ->get()
            ->getResultArray();
    }

    public function getAllUpcomingAgendas()
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');

        return $this->db->table('agendas')
            ->groupStart()
            ->where('tanggal_kegiatan >', $today) // Agenda setelah hari ini
            ->orGroupStart()
            ->where('tanggal_kegiatan =', $today)
            ->where('jam_kegiatan >', $currentTime) // Agenda hari ini setelah waktu sekarang
            ->groupEnd()
            ->groupEnd()
            ->orderBy('tanggal_kegiatan', 'ASC')
            ->orderBy('jam_kegiatan', 'ASC')
            ->limit(5) // Batasi hanya 5 hasil
            ->get()
            ->getResultArray();
    }


    public function getUserCompletedAgendas($userId)
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');

        return $this->db->table('agenda_users')
            ->select('agendas.*, notulens.id as notulen_id') // Ambil notulen_id
            ->join('agendas', 'agendas.id = agenda_users.agenda_id')
            ->join('notulens', 'notulens.agenda_id = agendas.id', 'left') // Join tabel notulens
            ->where('agenda_users.user_id', $userId)
            ->groupStart()
            ->where('agendas.tanggal_kegiatan <', $today) // Agenda yang sudah lewat
            ->orGroupStart()
            ->where('agendas.tanggal_kegiatan', $today) // Agenda hari ini
            ->where('agendas.jam_kegiatan <', $currentTime) // Waktu sudah lewat
            ->groupEnd()
            ->groupEnd()
            ->orderBy('agendas.tanggal_kegiatan', 'DESC')
            ->orderBy('agendas.jam_kegiatan', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAllCompletedAgendas()
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');

        // Query untuk mengambil agenda yang sudah selesai dan termasuk notulen jika ada
        return $this->select('agendas.*, notulens.id as notulen_id')
            ->join('notulens', 'notulens.agenda_id = agendas.id', 'left') // Join dengan tabel notulens
            ->groupStart()
            ->where('tanggal_kegiatan <', $today) // Agenda sebelum hari ini
            ->orGroupStart() // Mulai grup kondisi dengan OR
            ->where('tanggal_kegiatan', $today) // Agenda hari ini
            ->where('jam_kegiatan <', $currentTime) // Waktu sudah lewat
            ->groupEnd() // Akhiri grup kondisi
            ->groupEnd() // Akhiri grup utama
            ->orderBy('tanggal_kegiatan', 'DESC')
            ->orderBy('jam_kegiatan', 'DESC')
            ->findAll();
    }
}
