<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use RuntimeException;

class AumentarNombreLiquidacion extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('liquidaciones', [
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $longNames = $this->db->table('liquidaciones')
            ->where('CHAR_LENGTH(nombre) >', 50)
            ->countAllResults();
        if ($longNames > 0) {
            throw new RuntimeException('No se puede reducir el nombre: existen nombres de liquidación de más de 50 caracteres.');
        }

        $this->forge->modifyColumn('liquidaciones', [
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
        ]);
    }
}
