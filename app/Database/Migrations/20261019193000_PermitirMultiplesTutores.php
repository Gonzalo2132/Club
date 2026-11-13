<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PermitirMultiplesTutores extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('socios_tutores')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'auto_increment' => true,
                ],
                'id_socio' => [
                    'type' => 'INT',
                ],
                'id_tutor' => [
                    'type' => 'INT',
                ],
                'fecha_borrado' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey(['id_socio', 'id_tutor']);
            $this->forge->addKey('id_tutor');
            $this->forge->createTable('socios_tutores');
        }

        if ($this->db->fieldExists('id_tutor', 'socios')) {
            $this->db->query(
                'INSERT IGNORE INTO socios_tutores (id_socio, id_tutor)
                 SELECT id, id_tutor FROM socios WHERE id_tutor IS NOT NULL'
            );
        }
    }

    public function down()
    {
        $this->forge->dropTable('socios_tutores', true);
    }
}
