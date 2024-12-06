<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartmensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'departemen_id'=>[
            'type'=>'INT',
            'constraint'=>5,
            'unsigned'=>true,
            'auto_increment'=>true
        ],
            'nama_departemen'=>[
                'type'=>'VARCHAR',
                'constraint'=>'100',
            ]
        ]);
        $this->forge->addKey('departemen_id',true);
        $this->forge->createTable('departemens');
    }

    public function down()
    {
        $this->forge->dropTable('departemens');
    }
}
