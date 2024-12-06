<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Myth\Auth\Models\UserModel;

class AdminController extends BaseController
{

    protected $db, $builder,  $UserModel;


    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->builder =   $this->db->table('users');
        $this->UserModel=new UserModel();
    }
    public function index()
{
    // Pencarian
    $searchQuery = $this->request->getVar('search') ?? '';

    // Tentukan jumlah item per halaman
    $itemsPerPage = 10;

    // Ambil data dari tabel users dengan pencarian dan paginasi
    if ($searchQuery) {
        $users = $this->UserModel
            ->select('users.id as userid, users.username, users.email, auth_groups.name, departemens.nama_departemen')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('departemens', 'departemens.departemen_id = users.departemen_id')
            ->where('users.deleted_at', null)
            ->groupStart() // Awal grup untuk kondisi LIKE
                ->like('users.username', $searchQuery)
                ->orLike('users.email', $searchQuery)
                ->orLike('users.fullname', $searchQuery) // Asumsikan ada field fullname
                ->orLike('auth_groups.name', $searchQuery)
            ->groupEnd() // Akhir grup untuk kondisi LIKE
            ->paginate($itemsPerPage, 'users'); // Menggunakan grup 'users' untuk pagination
    } else {
        $users = $this->UserModel
            ->select('users.id as userid, users.username, users.email, auth_groups.name, departemens.nama_departemen')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('departemens', 'departemens.departemen_id = users.departemen_id')
            ->where('users.deleted_at', null)
            ->paginate($itemsPerPage, 'users'); // Menggunakan grup 'users' untuk pagination
    }

    // Set data untuk view
    $data = [
        'title' => 'User List',
        'users' => $users,
        'pager' => $this->UserModel->pager,
        'search' => $searchQuery // Simpan query pencarian
        
    ];

    // Load view
    echo view('template/header', $data);
    echo view('template/top_menu');
    echo view('template/side_menu');
    echo view('admin/index', $data);
    echo view('template/footer');
}

    public function detail($id = 0)
    {
        $data['title'] = 'User Detail';

        $this->builder->select('users.id as userid,username,email,fullname,user_image,name');
        $this->builder->join('auth_groups_users', ' auth_groups_users.user_id=users.id');
        // Melakukan join dengan tabel departemens berdasarkan departemen_id
        $this->builder->select('users.*, departemens.nama_departemen');
        $this->builder->join('departemens', 'departemens.departemen_id = users.departemen_id');

        $this->builder->join('auth_groups', ' auth_groups.id=auth_groups_users.group_id');
        $this->builder->where('users.id', $id);
        $query =   $this->builder->get();

        $data['user'] = $query->getRow();

        if (empty($data['user'])) {
            return redirect()->to('/admin');
        }

        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('admin/detail', $data);
        echo view('template/footer');
    }

    public function getUsersJson()
{
    // Ambil query pencarian jika ada
    $searchQuery = $this->request->getVar('search') ?? '';

    // Tentukan jumlah item per halaman
    $itemsPerPage = 10;

    // Ambil data dari tabel users dengan pencarian dan paginasi
    if ($searchQuery) {
        $users = $this->UserModel
            ->select('users.id as userid, users.username, users.email, auth_groups.name as group_name, departemens.nama_departemen')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('departemens', 'departemens.departemen_id = users.departemen_id')
            ->where('users.deleted_at', null)
            ->groupStart()
                ->like('users.username', $searchQuery)
                ->orLike('users.email', $searchQuery)
                ->orLike('auth_groups.name', $searchQuery)
                ->orLike('departemens.nama_departemen', $searchQuery)
            ->groupEnd()
            ->paginate($itemsPerPage, 'users');
    } else {
        $users = $this->UserModel
            ->select('users.id as userid, users.username, users.email, auth_groups.name as group_name, departemens.nama_departemen')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('departemens', 'departemens.departemen_id = users.departemen_id')
            ->where('users.deleted_at', null)
            ->paginate($itemsPerPage, 'users');
    }

    // Dapatkan pager untuk metadata pagination
    $pager = $this->UserModel->pager;

    // Format data response
    $response = [
        'status' => 'success',
        'data' => $users,
        'pagination' => [
            'currentPage' => $pager->getCurrentPage('users'),
            'totalPages' => $pager->getPageCount('users'),
            'perPage' => $itemsPerPage,
            'totalItems' => $pager->getTotal('users'),
        ]
    ];

    return $this->response->setJSON($response);
}

}
