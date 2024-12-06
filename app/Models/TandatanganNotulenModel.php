<?php

namespace App\Models;

use CodeIgniter\Model;

class TandatanganNotulenModel extends Model
{
    protected $table = 'tandatangan_notulens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['notulen_id', 'nama', 'departemen', 'tandatangan', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
