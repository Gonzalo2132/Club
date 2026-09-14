<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizarFechasPagos extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('pagos', [
            'fecha_pago' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->db->query(
            "UPDATE pagos SET fecha_pago = NULL
             WHERE fecha_pago = '0000-00-00 00:00:00'"
        );
    }

    public function down()
    {
        $this->db->query(
            "UPDATE pagos SET fecha_pago = '1970-01-01 00:00:00'
             WHERE fecha_pago IS NULL"
        );

        $this->forge->modifyColumn('pagos', [
            'fecha_pago' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
    }
}
