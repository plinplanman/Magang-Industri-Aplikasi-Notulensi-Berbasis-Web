<?php

namespace App\Controllers;

use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;
use App\Models\DepartemenModel;
use Myth\Auth\Entities\User;


class UserController extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function create()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }

        $departemenModel = new \App\Models\DepartemenModel();
        $roleModel = new GroupModel();

        $data = [
            'departemens' => $departemenModel->findAll(),
            'roles' => $roleModel->findAll()
        ];
        $data['title']='Create User';
        echo view('template/header',$data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('user/create', $data);
        echo view('template/footer');
    }

    public function store()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk membuat user.');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'fullname' => 'required',
            'username' => 'required|alpha_numeric|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
            'departemen_id' => 'required|integer',  // Pastikan validasi untuk departemen_id
            'role_id' => 'required|integer'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Simpan pengguna baru
        $user = new User([
            'fullname' => $this->request->getPost('fullname'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1,
            'departemen_id' => $this->request->getPost('departemen_id')  // Simpan departemen_id ke database
        ]);

        // Pastikan data user disimpan dengan benar
        $this->userModel->save($user);
        $userId = $this->userModel->getInsertID();

        // Tambahkan user ke group
        $this->groupModel->addUserToGroup($userId, $this->request->getPost('role_id'));

        return redirect()->to('/admin')->with('success', 'User berhasil dibuat.');
    }

    public function edit($id)
    {
        // Cek jika user adalah admin atau user yang sedang login
        if (!in_groups('admin') && $id != user_id()) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk mengedit user ini.');
        }

        // Ambil data user berdasarkan ID
        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            return redirect()->to('/admin')->with('error', 'User tidak ditemukan.');
        }

        // Ambil data departemen untuk dropdown
        $departemenModel = new DepartemenModel();
        $data['departemens'] = $departemenModel->findAll();

        // Ambil data roles untuk dropdown
        $groupModel = new GroupModel();
        $data['roles'] = $groupModel->findAll();

        $data['title']='Edit User';
        echo view('template/header',$data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('user/edit', $data);
        echo view('template/footer');
    }
    public function update($id)
{
    // Cek jika user adalah admin atau user yang sedang login
    if (!in_groups('admin') && $id != user_id()) {
        return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk mengedit user ini.');
    }

    // Cek apakah user ada di database
    $user = $this->userModel->find($id);
    if (!$user) {
        return redirect()->to('/admin')->with('error', 'User tidak ditemukan.');
    }

    // Data yang akan diperbarui
    $userData = [
        'fullname' => $this->request->getPost('fullname'),

        'username' => $this->request->getPost('username'),
        'email'    => $this->request->getPost('email'),
        'departemen_id' => $this->request->getPost('departemen_id'),
    ];

    // Jika password diubah, tambahkan password baru yang dihash
    if ($this->request->getPost('password')) {
        $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    }

    // Cek jika update data user berhasil
    if (!$this->userModel->update($id, $userData)) {
        $errors = $this->userModel->errors();
        return redirect()->back()->with('error', 'Gagal memperbarui data pengguna. ' . implode(', ', $errors));
    }

    // Update role di tabel auth_groups_users
    $roleId = $this->request->getPost('role_id');

    // Gunakan Query Builder langsung dari db
    $db = \Config\Database::connect();
    $builder = $db->table('auth_groups_users');

    // Hapus role yang ada sebelumnya untuk user ini
    $builder->where('user_id', $id)->delete();

    // Tambahkan role baru
    $builder->insert([
        'user_id' => $id,
        'group_id' => $roleId
    ]);

    return redirect()->to('/admin')->with('success', 'User berhasil diperbarui.');
}

    public function delete($id)
    {
        // Cek apakah user memiliki peran 'admin' atau memiliki hak akses yang cukup
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki izin untuk menghapus user ini.');
        }

        // Pastikan user yang ingin dihapus ada di database
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin')->with('error', 'User tidak ditemukan.');
        }

        // Hapus user
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin')->with('success', 'User berhasil dihapus.');
        } else {
            return redirect()->to('/admin')->with('error', 'Gagal menghapus user. Coba lagi nanti.');
        }
    }
    public function getAll()
{
    $users = $this->userModel->findAll();

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $users,
    ]);
}

public function createApi()
{
    $data = $this->request->getJSON();

    $validation = \Config\Services::validation();
    $validation->setRules([
        'fullname' => 'required',
        'username' => 'required|alpha_numeric|min_length[3]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]',
        'departemen_id' => 'required|integer',
        'role_id' => 'required|integer',
    ]);

    if (!$validation->run((array)$data)) {
        return $this->response->setJSON([
            'status' => 'error',
            'errors' => $validation->getErrors(),
        ])->setStatusCode(400);
    }

    $userData = [
        'fullname' => $data->fullname,
        'username' => $data->username,
        'email' => $data->email,
        'password' => password_hash($data->password, PASSWORD_DEFAULT),
        'departemen_id' => $data->departemen_id,
        'active' => 1,
    ];

    $this->userModel->save($userData);
    $userId = $this->userModel->getInsertID();

    // Assign role
    $this->groupModel->addUserToGroup($userId, $data->role_id);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'User created successfully',
    ])->setStatusCode(201);
}

public function updateApi($id = null)
{
    $data = $this->request->getJSON();

    $user = $this->userModel->find($id);
    if (!$user) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'User not found',
        ])->setStatusCode(404);
    }

    $updateData = [
        'fullname' => $data->fullname,
        'username' => $data->username,
        'email' => $data->email,
        'departemen_id' => $data->departemen_id,
    ];

    if (isset($data->password) && $data->password) {
        $updateData['password'] = password_hash($data->password, PASSWORD_DEFAULT);
    }

    $this->userModel->update($id, $updateData);

    // Update role
    $db = \Config\Database::connect();
    $builder = $db->table('auth_groups_users');
    $builder->where('user_id', $id)->delete();
    $builder->insert(['user_id' => $id, 'group_id' => $data->role_id]);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'User updated successfully',
    ]);
}

public function deleteApi($id = null)
{
    $user = $this->userModel->find($id);
    if (!$user) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'User not found',
        ])->setStatusCode(404);
    }

    $this->userModel->delete($id);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'User deleted successfully',
    ]);
}

public function showApi($id = null)
{
    $user = $this->userModel->find($id);
    if (!$user) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'User not found',
        ])->setStatusCode(404);
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $user,
    ]);
}

}
