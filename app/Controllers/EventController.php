<?php

namespace App\Controllers;

use App\Models\AgendaModel;
use Config\Database;
use Myth\Auth\Models\UserModel;

class EventController extends BaseController
{
    protected $agendaModel;
    protected $db;

    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $data['title'] = 'Calendar View';
        
        // Load view for calendar
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('agendas/calendar', $data); // Tampilkan view kalender
        echo view('template/footer');
    }

    public function getEvents() 
    {
        $user = user(); // Ambil data user yang sedang login
        $filter = $this->request->getGet('filter'); // Ambil filter dari request
    
        $db = Database::connect();
        $query = $db->table('auth_groups_users')
                    ->where('user_id', $user->id)
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                    ->select('auth_groups.name')
                    ->get();
    
        $roles = array_column($query->getResultArray(), 'name');
    
        // Logika berdasarkan filter
        if ($filter === 'mine') {
            // Ambil agenda pengguna berdasarkan user_id
            $agendas = $this->agendaModel->getUserAgendas($user->id);
        } else {
            // Ambil semua agenda jika filter adalah 'all'
            if (in_array('admin', $roles) || in_array('pimpinan', $roles)) {
                $agendas = $this->agendaModel->orderBy('tanggal_kegiatan', 'ASC')
                                              ->orderBy('jam_kegiatan', 'ASC')
                                              ->findAll();
            } elseif (in_array('staff', $roles)) {
                // Jika user adalah staff, tampilkan hanya agenda yang terkait dengan user
                $agendas = $this->agendaModel->getUserAgendas($user->id);
            } else {
                // Jika role tidak dikenali, kosongkan agenda
                $agendas = [];
            }
        }
    
        // Format data untuk FullCalendar
        $events = [];
        foreach ($agendas as $agenda) {
            $startDateTime = $agenda['tanggal_kegiatan'] . 'T' . $agenda['jam_kegiatan'];
            $events[] = [
                'id' => $agenda['id'],
                'title' => $agenda['judul'],
                'start' => $startDateTime,
                'description' => $agenda['lokasi'],
                'link' => base_url("/agenda/detail/{$agenda['id']}"),
            ];
        }
    
        return $this->response->setJSON($events);
    }
    

    
}
