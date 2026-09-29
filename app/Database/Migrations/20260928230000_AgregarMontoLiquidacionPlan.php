<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregarMontoLiquidacionPlan extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('monto', 'liquidaciones_planes')) {
            $this->forge->addColumn('liquidaciones_planes', [
                'monto' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'null' => false,
                    'default' => 0,
                ],
            ]);
        }

        $this->db->query(
            'UPDATE liquidaciones_planes
             INNER JOIN liquidaciones ON liquidaciones.id = liquidaciones_planes.id_liquidacion
             SET liquidaciones_planes.monto = liquidaciones.monto
             WHERE liquidaciones_planes.id_plan = liquidaciones.id_plan
               AND liquidaciones_planes.monto = 0
               AND liquidaciones.monto > 0'
        );
    }

    public function down()
    {
        if ($this->db->fieldExists('monto', 'liquidaciones_planes')) {
            $this->forge->dropColumn('liquidaciones_planes', 'monto');
        }
    }
}
