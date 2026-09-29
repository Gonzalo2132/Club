<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearLiquidacionesPlanes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_liquidacion' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_plan' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey(['id_liquidacion', 'id_plan'], true);
        $this->forge->addKey('id_plan');
        $this->forge->createTable('liquidaciones_planes', true);
    }

    public function down()
    {
        $this->forge->dropTable('liquidaciones_planes', true);
    }
}
