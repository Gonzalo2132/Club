<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearRegistroGastosCaja extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('gastos_caja')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_caja' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'categoria' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
            ],
            'concepto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'destinatario' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
            ],
            'monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'fecha_gasto' => [
                'type' => 'DATE',
            ],
            'comprobante' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'registrado_por' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'fecha_alta' => [
                'type' => 'DATETIME',
            ],
            'anulado_por' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'fecha_anulacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'motivo_anulacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['id_caja', 'fecha_gasto']);
        $this->forge->addKey(['fecha_anulacion', 'fecha_gasto']);
        $this->forge->createTable('gastos_caja');
    }

    public function down()
    {
        $this->forge->dropTable('gastos_caja', true);
    }
}
