<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearPermisosPorAccion extends Migration
{
    private const ACTIONS = ['ver', 'crear', 'editar', 'eliminar'];

    public function up()
    {
        if (!$this->db->tableExists('roles_acciones')) {
            $this->forge->addField([
                'rol_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                ],
                'modulo' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                ],
                'accion' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                ],
            ]);
            $this->forge->addKey(['rol_id', 'modulo', 'accion'], true);
            $this->forge->createTable('roles_acciones');
        }

        if (!$this->db->tableExists('roles_modulos')) {
            return;
        }

        $modulePermissions = $this->db->table('roles_modulos')
            ->select('rol_id, modulo')
            ->get()
            ->getResultArray();
        $permissions = [];
        foreach ($modulePermissions as $modulePermission) {
            foreach (self::ACTIONS as $action) {
                $permissions[] = [
                    'rol_id' => $modulePermission['rol_id'],
                    'modulo' => $modulePermission['modulo'],
                    'accion' => $action,
                ];
            }
        }

        if ($permissions !== []) {
            $this->db->table('roles_acciones')->ignore(true)->insertBatch($permissions);
        }
    }

    public function down()
    {
        $this->forge->dropTable('roles_acciones', true);
    }
}
