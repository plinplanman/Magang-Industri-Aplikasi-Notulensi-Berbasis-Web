<?php

namespace App\Controllers;

use App\Models\AgendaModel;
use App\Models\AgendaUserModel;
use Config\Database;
use Myth\Auth\Models\UserModel;

class AgendaController extends BaseController
{
    protected $agendaModel;
    protected $agendaUserModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->agendaModel = new AgendaModel();
        $this->agendaUserModel = new AgendaUserModel();
        $this->userModel = new UserModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $data['title'] = 'Agenda List';

        // Ambil parameter pencarian dari query string
        $searchQuery = $this->request->getVar('search');
        $perPage = 10; // Jumlah item per halaman

        // Query data agenda dengan pencarian
        $agendaQuery = $this->agendaModel;

        if ($searchQuery) {
            $agendaQuery = $agendaQuery->groupStart()
                ->like('judul', $searchQuery)
                ->orLike('nama_pelanggan', $searchQuery)
                ->orLike('lokasi', $searchQuery)
                ->groupEnd();
        }

        $agendas = $agendaQuery->paginate($perPage, 'agendas');

        // Inisialisasi koneksi database
        $db = \Config\Database::connect();

        foreach ($agendas as &$agenda) {
            $agenda['users'] = $db->table('agenda_users')
                ->select('users.username, users.fullname, departemens.nama_departemen, auth_groups.name')
                ->join('users', 'users.id = agenda_users.user_id')
                ->join('departemens', 'departemens.departemen_id = users.departemen_id')
                ->join('auth_groups_users agu', 'agu.user_id = users.id')
                ->join('auth_groups', 'auth_groups.id = agu.group_id')
                ->where('agenda_users.agenda_id', $agenda['id'])
                ->get()->getResult();
        }

        $data['agendas'] = $agendas;
        $data['pager'] = $this->agendaModel->pager;
        $data['search'] = $searchQuery; // Simpan pencarian untuk input di view

        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('agendas/index', $data);
        echo view('template/footer');
    }


    public function create()
    {
        $data['users'] = $this->userModel->findAll();
        $data['title'] = 'Create Agenda';
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('agendas/create', $data);
        echo view('template/footer');
    }

    public function store()
    {
        $this->agendaModel->save([
            'judul' => $this->request->getPost('judul'),
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal_kegiatan' => $this->request->getPost('tanggal_kegiatan'),
            'jam_kegiatan' => $this->request->getPost('jam_kegiatan'),
            'personel_desnet' => json_encode($this->request->getPost('personel_desnet')),
            'lampiran' => $this->uploadLampiran(),
            'link' => $this->request->getPost('link'),
            'tgl_reminder' => date('Y-m-d', strtotime($this->request->getPost('tanggal_kegiatan') . ' -1 day')),
        ]);

        return redirect()->to('/agenda');
    }
    public function edit($id = 0)
    {
        $data['title'] = 'Edit Agenda';
        $data['agenda'] = $this->agendaModel->find($id);

        if (empty($data['agenda'])) {
            return redirect()->to('/agenda')->with('error', 'Agenda tidak ditemukan');
        }

        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('agendas/edit', $data);
        echo view('template/footer');
    }

    public function update($id)
    {
        $this->agendaModel->update($id, [
            'judul' => $this->request->getPost('judul'),
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal_kegiatan' => $this->request->getPost('tanggal_kegiatan'),
            'jam_kegiatan' => $this->request->getPost('jam_kegiatan'),
            'personel_desnet' => json_encode($this->request->getPost('personel_desnet')),
            'lampiran' => $this->uploadLampiran(),
            'link' => $this->request->getPost('link'),
            'tgl_reminder' => date('Y-m-d', strtotime($this->request->getPost('tanggal_kegiatan') . ' -1 day')),
        ]);

        return redirect()->to('/agenda');
    }

    public function delete($id)
    {
        $agenda = $this->agendaModel->find($id);

        if ($agenda) {
            $this->agendaModel->delete($id);
            return redirect()->to('/agenda')->with('message', 'Agenda berhasil dihapus');
        } else {
            return redirect()->to('/agenda')->with('error', 'Agenda tidak ditemukan');
        }
    }

    private function uploadLampiran()
    {
        $file = $this->request->getFile('lampiran');
        if ($file && $file->isValid()) {
            // Tentukan folder tujuan
            $uploadPath = FCPATH . 'agenda_images';

            // Buat folder jika belum ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Dapatkan nama file baru yang unik
            $newName = $file->getRandomName();

            // Pindahkan file ke folder public/agenda_images
            $file->move($uploadPath, $newName);

            // Kembalikan nama file untuk disimpan di database
            return $newName;
        }
        return null;
    }

    public function detail($id = 0)
    {
        $db = \Config\Database::connect();

        $data['title'] = 'Agenda Detail';

        // Mendapatkan detail agenda berdasarkan ID
        $data['agenda'] = $this->agendaModel->find($id);

        if (empty($data['agenda'])) {
            return redirect()->to('/agenda')->with('error', 'Agenda tidak ditemukan');
        }

        // Inisialisasi koneksi database
        $db = \Config\Database::connect();

        // Ambil daftar user terkait dari tabel agenda_users berdasarkan agenda_id
        $data['agenda']['users'] = $db->table('agenda_users')
            ->select('users.username, users.fullname, departemens.nama_departemen, auth_groups.name')
            ->join('users', 'users.id = agenda_users.user_id')
            ->join('departemens', 'departemens.departemen_id = users.departemen_id')
            ->join('auth_groups_users agu', 'agu.user_id = users.id') // Alias untuk auth_groups_users
            ->join('auth_groups', 'auth_groups.id = agu.group_id')
            ->where('agenda_users.agenda_id', $id)
            ->get()->getResult();


        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('agendas/detail', $data);
        echo view('template/footer');
    }

    // Method untuk API: Menampilkan Semua Agenda (GET)
    public function getAllApi()
    {
        $agendas = $this->agendaModel->findAll();
        return $this->response->setJSON($agendas);
    }

    // Method untuk API: Menambah Agenda Baru (POST)
    public function storeApi()
    {
        $data = $this->request->getJSON();  // Mengambil data JSON dari body request
        
        // Validasi data
        if (!$data || empty($data->judul)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Data tidak lengkap']);
        }

        // Menyimpan agenda baru
        $this->agendaModel->save([
            'judul' => $data->judul,
            'nama_pelanggan' => $data->nama_pelanggan,
            'lokasi' => $data->lokasi,
            'tanggal_kegiatan' => $data->tanggal_kegiatan,
            'jam_kegiatan' => $data->jam_kegiatan,
            'personel_desnet' => json_encode($data->personel_desnet),
            'lampiran' => null,  // Asumsi lampiran dikirim lewat upload
            'link' => $data->link,
            'tgl_reminder' => date('Y-m-d', strtotime($data->tanggal_kegiatan . ' -1 day')),
        ]);

        return $this->response->setStatusCode(201)->setJSON(['message' => 'Agenda berhasil disimpan']);
    }

    // Method untuk API: Menampilkan Agenda Berdasarkan ID (GET)
    public function showApi($id)
    {
        $agenda = $this->agendaModel->find($id);
        if ($agenda) {
            return $this->response->setJSON($agenda);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Agenda tidak ditemukan']);
        }
    }

    // Method untuk API: Mengupdate Agenda Berdasarkan ID (PUT)
    public function updateApi($id)
    {
        $data = $this->request->getJSON();  // Mengambil data JSON dari body request

        // Validasi data
        if (!$data || empty($data->judul)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Data tidak lengkap']);
        }

        $updated = $this->agendaModel->update($id, [
            'judul' => $data->judul,
            'nama_pelanggan' => $data->nama_pelanggan,
            'lokasi' => $data->lokasi,
            'tanggal_kegiatan' => $data->tanggal_kegiatan,
            'jam_kegiatan' => $data->jam_kegiatan,
            'personel_desnet' => json_encode($data->personel_desnet),
            'lampiran' => null,  // Asumsi lampiran dikirim lewat upload
            'link' => $data->link,
            'tgl_reminder' => date('Y-m-d', strtotime($data->tanggal_kegiatan . ' -1 day')),
        ]);

        if ($updated) {
            return $this->response->setJSON(['message' => 'Agenda berhasil diperbarui']);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Agenda tidak ditemukan']);
        }
    }

    // Method untuk API: Menghapus Agenda Berdasarkan ID (DELETE)
    public function deleteApi($id)
    {
        $agenda = $this->agendaModel->find($id);

        if ($agenda) {
            $this->agendaModel->delete($id);
            return $this->response->setJSON(['message' => 'Agenda berhasil dihapus']);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Agenda tidak ditemukan']);
        }
    }
}
