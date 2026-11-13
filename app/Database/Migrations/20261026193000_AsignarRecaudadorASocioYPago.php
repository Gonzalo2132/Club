<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AsignarRecaudadorASocioYPago extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('id_recaudador', 'socios')) {
            $this->forge->addColumn('socios', [
                'id_recaudador' => [
                    'type' => 'INT',
                    'null' => true,
                ],
            ]);
        }

        if (!$this->db->fieldExists('id_recaudador', 'pagos')) {
            $this->forge->addColumn('pagos', [
                'id_recaudador' => [
                    'type' => 'INT',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('id_recaudador', 'pagos')) {
            $this->forge->dropColumn('pagos', 'id_recaudador');
        }

        if ($this->db->fieldExists('id_recaudador', 'socios')) {
            $this->forge->dropColumn('socios', 'id_recaudador');
        }
    }
}
