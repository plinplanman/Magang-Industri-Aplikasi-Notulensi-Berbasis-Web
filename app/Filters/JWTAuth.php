<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Myth\Auth\Models\UserModel;

class JwtAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader) {
            return redirect()->to('/auth/login');
        }

        // Extract Bearer Token
        $matches = [];
        preg_match('/Bearer (.+)/', $authHeader, $matches);

        if (empty($matches)) {
            return redirect()->to('/auth/login');
        }

        $token = $matches[1];
        $key = 'your_secret_key'; // Ganti dengan kunci rahasia Anda

        try {
            // Decode the JWT Token (menggunakan Key instance)
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->sub;

            // Verifikasi pengguna berdasarkan userId
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if (!$user) {
                return redirect()->to('/auth/login');
            }

            // Pass user data for use in controllers if necessary
            $request->user = $user;
        } catch (\Exception $e) {
            // Log error jika diperlukan
            log_message('error', 'JWT decode error: ' . $e->getMessage());
            return redirect()->to('/auth/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada tindakan setelah request
    }
}
