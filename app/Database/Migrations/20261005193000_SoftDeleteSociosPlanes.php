<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SoftDeleteSociosPlanes extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('fecha_borrado', 'socios_planes')) {
            $this->forge->addColumn('socios_planes', [
                'fecha_borrado' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('fecha_borrado', 'socios_planes')) {
            $this->forge->dropColumn('socios_planes', 'fecha_borrado');
        }
    }
}
