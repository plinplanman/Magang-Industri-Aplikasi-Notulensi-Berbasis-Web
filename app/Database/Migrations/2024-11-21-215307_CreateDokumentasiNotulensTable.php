<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDokumentasiNotulensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'notulen_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('notulen_id', 'notulens', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dokumentasi_notulens');
    }

    public function down()
    {
        $this->forge->dropTable('dokumentasi_notulens');
    }
}
