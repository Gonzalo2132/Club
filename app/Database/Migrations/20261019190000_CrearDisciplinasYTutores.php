<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearDisciplinasYTutores extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('disciplinas')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'auto_increment' => true,
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
                'fecha_borrado' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('disciplinas');
        }

        if (!$this->db->tableExists('tutores')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'auto_increment' => true,
                ],
                'nombre' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'apellido' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'dni' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                ],
                'telefono' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 80,
                    'null'       => true,
                ],
                'parentesco' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                    'null'       => true,
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
            $this->forge->addUniqueKey('dni');
            $this->forge->createTable('tutores');
        }

        if (!$this->db->tableExists('socios_disciplinas')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'auto_increment' => true,
                ],
                'id_socio' => [
                    'type' => 'INT',
                ],
                'id_disciplina' => [
                    'type' => 'INT',
                ],
                'fecha_alta' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
                'fecha_borrado' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey(['id_socio', 'id_disciplina']);
            $this->forge->addKey('id_disciplina');
            $this->forge->createTable('socios_disciplinas');
        }

        if (!$this->db->fieldExists('id_tutor', 'socios')) {
            $this->forge->addColumn('socios', [
                'id_tutor' => [
                    'type' => 'INT',
                    'null' => true,
                ],
            ]);
        }

        if ($this->db->tableExists('roles_modulos') && $this->db->tableExists('roles')) {
            $roles = $this->db->table('roles')
                ->whereIn('nombre', ['Administrador', 'Acceso completo'])
                ->get()
                ->getResultArray();

            foreach ($roles as $role) {
                $exists = $this->db->table('roles_modulos')
                    ->where('rol_id', $role['id'])
                    ->where('modulo', 'disciplinas')
                    ->countAllResults();

                if ($exists === 0) {
                    $this->db->table('roles_modulos')->insert([
                        'rol_id' => $role['id'],
                        'modulo' => 'disciplinas',
                    ]);
                }
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('roles_modulos')) {
            $this->db->table('roles_modulos')
                ->where('modulo', 'disciplinas')
                ->delete();
        }

        if ($this->db->fieldExists('id_tutor', 'socios')) {
            $this->forge->dropColumn('socios', 'id_tutor');
        }

        $this->forge->dropTable('socios_disciplinas', true);
        $this->forge->dropTable('tutores', true);
        $this->forge->dropTable('disciplinas', true);
    }
}
