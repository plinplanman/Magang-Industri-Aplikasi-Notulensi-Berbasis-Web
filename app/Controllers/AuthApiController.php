<?php 
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Myth\Auth\Models\UserModel;

class AuthApiController extends ResourceController
{
    // Method untuk login dengan token
    public function loginWithToken()
    {
        $key = getenv('TOKEN_SECRET'); // Secret JWT
        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token Required'
            ], 401);
        }

        $token = explode(' ', $header)[1];

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $userModel = new UserModel();
            $user = $userModel->find($decoded->uid);

            if (!$user) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'User Not Found'
                ], 404);
            }

            // Simpan status login ke sesi, jika diperlukan
            session()->set('user_id', $user->id);
            session()->set('logged_in', true);

            return $this->respond([
                'status' => 'success',
                'message' => 'Login Successful'
            ]);

        } catch (\Throwable $th) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Invalid Token'
            ], 401);
        }
    }

    // Method untuk logout dengan token
    public function logoutWithToken()
    {
        $key = getenv('TOKEN_SECRET'); // Secret JWT
        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token Required'
            ], 401);
        }

        $token = explode(' ', $header)[1];

        try {
            // Decode token untuk mendapatkan informasi user
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Pastikan token yang dikirim valid
            if (!$decoded || !isset($decoded->uid)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid Token'
                ], 401);
            }

            // Proses logout, menghapus sesi
            session()->remove('user_id');
            session()->remove('logged_in');

            return $this->respond([
                'status' => 'success',
                'message' => 'Logout Successful'
            ]);
        } catch (\Throwable $th) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Invalid Token'
            ], 401);
        }
    }
}
