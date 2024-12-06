<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AgendaUserModel;
use App\Models\AgendaModel;
use Myth\Auth\Models\UserModel;

class AgendaUserController extends BaseController
{
    protected $agendaUserModel;
    protected $agendaModel;
    protected $userModel;
    protected $dokumentasiModel;

    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->agendaUserModel = new AgendaUserModel();
        $this->userModel = new UserModel();
    }

    public function create()
{
    // Ambil agenda_id dari URL
    $agendaId = $this->request->getGet('agenda_id');
    if (!$agendaId) {
        return redirect()->to('/agenda')->with('error', 'Agenda tidak ditemukan.');
    }

    // Ambil data agenda berdasarkan ID
    $agenda = $this->agendaModel->find($agendaId);
    if (!$agenda) {
        return redirect()->to('/agenda')->with('error', 'Agenda tidak ditemukan.');
    }

    // Query untuk join tabel users, departemens, auth_groups_users, dan auth_groups
    $users = $this->userModel->select('users.id, users.fullname, departemens.nama_departemen as departemen, auth_groups.name as group')
        ->join('departemens', 'departemens.departemen_id = users.departemen_id', 'left')
        ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
        ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
        ->findAll();

    $data = [
        'title' => 'Tambah Personil',
        'users' => $users, // Hasil join query
        'agenda_id' => $agendaId, // ID Agenda untuk form
        'agenda_title' => $agenda['judul'] // Judul agenda untuk ditampilkan
    ];

    echo view('template/header', $data);
    echo view('template/top_menu');
    echo view('template/side_menu');
    echo view('agendas/personil/create', $data);
    echo view('template/footer');
}

    public function store()
    {
        // Validate CSRF and form input
        if (!$this->validate(['agenda_id' => 'required', 'user_id' => 'required'])) {
            return redirect()->back()->with('error', 'Agenda dan user harus dipilih.')->withInput();
        }

        $agendaId = $this->request->getPost('agenda_id');
        $userIds = $this->request->getPost('user_id');

        if ($agendaId && is_array($userIds)) {
            foreach ($userIds as $userId) {
                $this->agendaUserModel->insert([
                    'agenda_id' => $agendaId,
                    'user_id' => $userId,
                ]);
            }
            return redirect()->to('/agenda')->with('success', 'Personil berhasil ditambahkan.');
        }
        return redirect()->back()->with('error', 'Agenda dan user harus dipilih.');
    }
    public function edit($agendaId)
{
    $personil = $this->agendaUserModel->where('agenda_id', $agendaId)->findAll();

    if (!$personil) {
        return redirect()->to('/agenda')->with('error', 'Personil tidak ditemukan.');
    }

    // Query untuk join tabel users, departemens, auth_groups_users, dan auth_groups
    $users = $this->userModel->select('users.id, users.fullname, departemens.nama_departemen as departemen, auth_groups.name as group')
        ->join('departemens', 'departemens.departemen_id = users.departemen_id', 'left')
        ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
        ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
        ->findAll();

    $data = [
        'title' => 'Edit Personil Meeting',
        'agenda_id' => $agendaId,
        'users' => $users, // Hasil join query
        'agendaUsers' => $personil
    ];

    echo view('template/header', $data);
    echo view('template/top_menu');
    echo view('template/side_menu');
    echo view('agendas/personil/edit', $data);
    echo view('template/footer');
}
public function update($agendaId)
{
    $userIds = $this->request->getPost('user_id');

    if ($agendaId && $userIds && is_array($userIds)) {
        // Hapus semua data personil terkait agenda untuk mencegah duplikasi
        $this->agendaUserModel->where('agenda_id', $agendaId)->delete();

        foreach ($userIds as $userId) {
            $this->agendaUserModel->insert([
                'agenda_id' => $agendaId,
                'user_id' => $userId
            ]);
        }
        return redirect()->to('/agenda')->with('success', 'Personil berhasil diperbarui.');
    }
    return redirect()->to('/agenda')->with('error', 'Agenda dan pengguna harus dipilih.');
}
public function delete($id)
{
    if ($this->request->isAJAX()) {
        if ($this->agendaUserModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Personil berhasil dihapus.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus personil.']);
    }
    return redirect()->to('/agenda')->with('error', 'Gagal menghapus personil.');
}

// REST API: Get All Dokumentasi (GET)
public function getAllApi()
{
    $data = $this->dokumentasiModel->findAll();
    return $this->response->setJSON($data);
}

// REST API: Create Dokumentasi (POST)
public function createApi()
{
    $json = $this->request->getJSON();
    $this->dokumentasiModel->insert([
        'notulen_id' => $json->notulen_id,
        'image'      => $json->image,
    ]);

    return $this->response->setJSON(['message' => 'Dokumentasi created successfully.']);
}

// REST API: Delete Dokumentasi (DELETE)
public function deleteApi($id)
{
    if ($this->dokumentasiModel->delete($id)) {
        return $this->response->setJSON(['message' => 'Dokumentasi deleted successfully.']);
    }

    return $this->response->setStatusCode(404)->setJSON(['error' => 'Data not found.']);
}

}
