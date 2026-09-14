<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregarDestinoMovimientos extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('caja_destino_id', 'movimientos_caja')) {
            $this->forge->addColumn('movimientos_caja', [
                'caja_destino_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'caja_origen_id',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('caja_destino_id', 'movimientos_caja')) {
            $this->forge->dropColumn('movimientos_caja', 'caja_destino_id');
        }
    }
}
