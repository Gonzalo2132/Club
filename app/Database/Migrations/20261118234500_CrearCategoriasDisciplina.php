<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearCategoriasDisciplina extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('categorias_disciplina')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'auto_increment' => true,
                ],
                'id_disciplina' => [
                    'type' => 'INT',
                ],
                'nombre' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 80,
                ],
                'descripcion' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'activo' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'fecha_alta' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
                'fecha_edicion' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('id_disciplina');
            $this->forge->addUniqueKey(['id_disciplina', 'nombre']);
            $this->forge->createTable('categorias_disciplina');
        }
    }

    public function down()
    {
        $this->forge->dropTable('categorias_disciplina', true);
    }
}