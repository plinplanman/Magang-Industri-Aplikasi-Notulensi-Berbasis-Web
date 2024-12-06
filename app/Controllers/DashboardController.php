<?php

namespace App\Controllers;

use App\Models\AgendaModel;
use App\Models\NotulenModel;
use Myth\Auth\Models\GroupModel; // Untuk mengambil grup user

class DashboardController extends BaseController
{
    protected $agendaModel;
    protected $notulenModel;

    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->notulenModel = new NotulenModel();
    }

    public function index()
    {
        if (session()->get('role') === 'admin') {
            // Admin melihat semua agenda yang selesai
            $completedAgendas = $this->agendaModel->getAllCompletedAgendas();
        } else {
            // Staff atau pimpinan melihat agenda mereka sendiri
            $userId = session()->get('user_id');
            $completedAgendas = $this->agendaModel->getUserCompletedAgendas($userId);
        }

        $header['title'] = 'Dashboard';
        $user = user(); // Mendapatkan user yang sedang login
        $db = \Config\Database::connect();

        // Dapatkan roles user
        $query = $db->table('auth_groups_users')
            ->where('user_id', $user->id)
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->select('auth_groups.name')
            ->get();

        $roles = array_column($query->getResultArray(), 'name');

        // Logika untuk data berdasarkan role
        $filter = $this->request->getGet('filter') ?? 'all'; // Inisialisasi $filter dengan default 'all'

        if (in_array('admin', $roles)) {
            // Admin bisa melihat semua data atau data terkait user tertentu
            if ($filter === 'mine') {
                $thisMonthAgendas = $this->agendaModel->getUserAgendas($user->id);
                $upcomingAgendas = $this->agendaModel->getUserUpcomingAgendas($user->id);
                $completedAgendas = $this->agendaModel->getUserCompletedAgendas($user->id);
            } else {
                $thisMonthAgendas = $this->agendaModel->getAllAgendasThisMonth();
                $upcomingAgendas = $this->agendaModel->getAllUpcomingAgendas();
                $completedAgendas = $this->agendaModel->getAllCompletedAgendas();
            }
        } elseif (in_array('pimpinan', $roles) || in_array('staff', $roles)) {
            // Pimpinan dan Staff hanya bisa melihat data terkait
            $thisMonthAgendas = $this->agendaModel->getUserAgendas($user->id);
            $upcomingAgendas = $this->agendaModel->getUserUpcomingAgendas($user->id);
            $completedAgendas = $this->agendaModel->getUserCompletedAgendas($user->id);
        } else {
            // Tidak ada role yang dikenali, kosongkan data
            $thisMonthAgendas = [];
            $upcomingAgendas = [];
            $completedAgendas = [];
        }

        // Kirim data ke view
        return view('template/header', $header)
            . view('template/top_menu')
            . view('template/side_menu')
            . view('dashboard', [
                'thisMonthAgendas' => $thisMonthAgendas,
                'upcomingAgendas' => $upcomingAgendas,
                'completedAgendas' => $completedAgendas,
                'roles' => $roles, // Kirim roles ke view
                'filter' => $filter // Kirim filter ke view jika diperlukan
            ])
            . view('template/footer');
    }
    public function getAgendasJson()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        
        $user = user(); // Mendapatkan user yang sedang login
        $db = \Config\Database::connect();

        // Dapatkan roles user
        $query = $db->table('auth_groups_users')
            ->where('user_id', $user->id)
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->select('auth_groups.name')
            ->get();

        $roles = array_column($query->getResultArray(), 'name');

        // Filter data berdasarkan roles
        if (in_array('admin', $roles)) {
            $agendas = $this->agendaModel->findAll(); // Admin mendapatkan semua agenda
        } elseif (in_array('pimpinan', $roles) || in_array('staff', $roles)) {
            $agendas = $this->agendaModel->getUserAgendas($user->id); // Pimpinan/Staff mendapatkan agenda mereka sendiri
        } else {
            $agendas = []; // Tidak ada role yang dikenali, kosongkan data
        }

        // Kembalikan data dalam format JSON
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $agendas
        ]);
    }
}
