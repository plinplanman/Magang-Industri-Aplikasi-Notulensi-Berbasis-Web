<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data=['title'=>'Login'];
        echo view('template/header', $data);
        echo view('welcome_message');
        echo view('template/footer');
    }
}
