<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setJSON(['status' => 'error', 'message' => 'Unauthorized'])
                ->setStatusCode(401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        // Validasi token
        $auth = service('authentication');
        if (!$auth->check($token)) {
            return service('response')
                ->setJSON(['status' => 'error', 'message' => 'Invalid or expired token'])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada proses khusus setelah request
    }
}
