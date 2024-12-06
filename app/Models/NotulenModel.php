<?php

namespace App\Models;

use CodeIgniter\Model;

class NotulenModel extends Model
{
    protected $table = 'notulens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['agenda_id', 'isi_notulens', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    

}
