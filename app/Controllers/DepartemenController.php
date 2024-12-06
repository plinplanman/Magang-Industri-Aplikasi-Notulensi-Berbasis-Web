<?php

namespace App\Controllers;

use App\Models\DepartemenModel;
use CodeIgniter\Controller;

class DepartemenController extends Controller
{
    protected $departemenModel;

    public function __construct()
    {
        $this->departemenModel = new DepartemenModel();
    }

    public function index()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }
        // Ambil query pencarian dari input, set default ke string kosong jika kosong
        $searchQuery = $this->request->getVar('search') ?? '';

        // Tentukan jumlah item per halaman
        $itemsPerPage = 10;

        // Ambil data dari tabel departemens dengan pencarian dan paginasi
        if ($searchQuery) {
            $departemens = $this->departemenModel
                ->like('nama_departemen', $searchQuery)
                ->paginate($itemsPerPage, 'departemens');
        } else {
            $departemens = $this->departemenModel->paginate($itemsPerPage, 'departemens');
        }

        // Data untuk dikirimkan ke view
        $data = [
            'title' => 'Departemen List',
            'departemens' => $departemens,
            'pager' => $this->departemenModel->pager, // Set pager
            'search' => $searchQuery // Simpan query pencarian
        ];

        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('departemens/index', $data);
        echo view('template/footer');
    }


    public function create()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }

        $data = ['title' => 'Create Departemen'];
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        return view('departemens/create', $data);
        echo view('template/footer');
    }

    public function store()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }
        $this->departemenModel->save([
            'nama_departemen' => $this->request->getPost('nama_departemen'),
        ]);

        return redirect()->to('/departemens');
    }

    public function edit($id)
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }
        $data = [
            'title' => 'Edit Departemen',
            'departemen' => $this->departemenModel->find($id)
        ];
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        return view('departemens/edit', $data);
        echo view('template/footer');
    }

    public function update($id)
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }
        $this->departemenModel->update($id, [
            'nama_departemen' => $this->request->getPost('nama_departemen'),
        ]);

        return redirect()->to('/departemens');
    }

    public function delete($id)
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }
        $this->departemenModel->delete($id);
        return redirect()->to('/departemens');
    }

    // Menampilkan semua data departemen
    public function getAll()
    {
        // Ambil semua data departemen
        $departemens = $this->departemenModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $departemens
        ]);
    }

    // Menampilkan data departemen berdasarkan ID
    public function showApi($id)
    {
        $departemen = $this->departemenModel->find($id);

        if (!$departemen) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Departemen tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $departemen
        ]);
    }

    // Menambahkan departemen baru melalui API
    public function storeApi()
    {
        $input = $this->request->getJSON();

        // Validasi input
        if (!isset($input->nama_departemen)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama departemen harus diisi'
            ]);
        }

        $this->departemenModel->save([
            'nama_departemen' => $input->nama_departemen
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Departemen berhasil ditambahkan'
        ]);
    }

    // Memperbarui departemen melalui API
    public function updateApi($id)
    {
        $input = $this->request->getJSON();

        // Validasi input
        if (!isset($input->nama_departemen)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama departemen harus diisi'
            ]);
        }

        // Cek jika departemen ada
        $departemen = $this->departemenModel->find($id);
        if (!$departemen) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Departemen tidak ditemukan'
            ]);
        }

        $this->departemenModel->update($id, [
            'nama_departemen' => $input->nama_departemen
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Departemen berhasil diperbarui'
        ]);
    }

    // Menghapus departemen melalui API
    public function deleteApi($id)
    {
        // Cek apakah departemen ada
        $departemen = $this->departemenModel->find($id);
        if (!$departemen) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Departemen tidak ditemukan'
            ]);
        }

        $this->departemenModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Departemen berhasil dihapus'
        ]);
    }
}
