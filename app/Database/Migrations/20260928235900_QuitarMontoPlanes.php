<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class QuitarMontoPlanes extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('monto', 'planes')) {
            $this->forge->dropColumn('planes', 'monto');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('monto', 'planes')) {
            $this->forge->addColumn('planes', [
                'monto' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'null' => false,
                    'default' => 0,
                    'after' => 'descripcion',
                ],
            ]);
        }
    }
}
