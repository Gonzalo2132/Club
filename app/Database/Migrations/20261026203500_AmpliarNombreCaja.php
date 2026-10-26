<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AmpliarNombreCaja extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('cajas', [
            'denominacion' => [
                'name' => 'denominacion',
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('cajas', [
            'denominacion' => [
                'name' => 'denominacion',
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
        ]);
    }
}
