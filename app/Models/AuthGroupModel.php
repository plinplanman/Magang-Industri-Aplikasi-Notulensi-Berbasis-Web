<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthGroupModel extends Model
{
    protected $table = 'auth_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description'];
    protected $returnType = 'array';
}
