<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTandatanganNotulensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'notulen_id'  => ['type' => 'INT', 'unsigned' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'departemen'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'tandatangan' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('notulen_id', 'notulens', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tandatangan_notulens');
    }

    public function down()
    {
        $this->forge->dropTable('tandatangan_notulens');
    }
}
