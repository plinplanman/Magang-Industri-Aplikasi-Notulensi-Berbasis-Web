<?php 
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\NotifikasiNotulenController;

class NotificationFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $controller = new NotifikasiNotulenController();
        $controller->getNotifikasi();
    }
    

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan setelah request
    }
}
