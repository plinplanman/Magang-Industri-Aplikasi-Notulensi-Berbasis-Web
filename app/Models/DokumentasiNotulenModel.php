<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumentasiNotulenModel extends Model
{
    protected $table = 'dokumentasi_notulens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['notulen_id', 'image', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
