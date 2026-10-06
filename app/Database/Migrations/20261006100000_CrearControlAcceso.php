<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearControlAcceso extends Migration
{
    private array $modulos = [
        'zonas',
        'usuarios',
        'socios',
        'cajas',
        'recaudadores',
        'liquidaciones',
        'pagos',
        'planes',
    ];

    public function up()
    {
        $db = \Config\Database::connect();
        $administrador = $db->table('usuarios')->where('username', 'admin')->get()->getRowArray();
        if ($administrador === null) {
            throw new \RuntimeException('No existe el usuario `admin`; no se puede inicializar el administrador del sistema.');
        }

        if (!$this->db->tableExists('roles')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nombre' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 80,
                ],
                'es_administrador' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('nombre', false, true);
            $this->forge->createTable('roles');
        }

        if (!$this->db->tableExists('roles_modulos')) {
            $this->forge->addField([
                'rol_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                ],
                'modulo' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                ],
            ]);
            $this->forge->addKey(['rol_id', 'modulo'], true);
            $this->forge->createTable('roles_modulos');
        }

        if (!$this->db->fieldExists('rol_id', 'usuarios')) {
            $this->forge->addColumn('usuarios', [
                'rol_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                    'null'     => true,
                ],
            ]);
        }

        $administradorId = $this->getOrCreateRole($db, 'Administrador', 1);
        $accesoCompletoId = $this->getOrCreateRole($db, 'Acceso completo', 0);

        $permisosExistentes = $db->table('roles_modulos')
            ->whereIn('rol_id', [$administradorId, $accesoCompletoId])
            ->get()
            ->getResultArray();
        $asignados = [];
        foreach ($permisosExistentes as $permission) {
            $asignados[$permission['rol_id'] . ':' . $permission['modulo']] = true;
        }
        $permisos = [];
        foreach ([$administradorId, $accesoCompletoId] as $rolId) {
            foreach ($this->modulos as $modulo) {
                if (isset($asignados[$rolId . ':' . $modulo])) {
                    continue;
                }
                $permisos[] = [
                    'rol_id' => $rolId,
                    'modulo' => $modulo,
                ];
            }
        }
        if ($permisos !== []) {
            $db->table('roles_modulos')->insertBatch($permisos);
        }

        $db->table('usuarios')->update(['rol_id' => $accesoCompletoId]);
        $db->table('usuarios')
            ->where('id', $administrador['id'])
            ->update(['rol_id' => $administradorId]);
    }

    public function down()
    {
        if ($this->db->fieldExists('rol_id', 'usuarios')) {
            $this->forge->dropColumn('usuarios', 'rol_id');
        }

        $this->forge->dropTable('roles_modulos', true);
        $this->forge->dropTable('roles', true);
    }

    private function getOrCreateRole($db, string $name, int $isAdmin): int
    {
        $role = $db->table('roles')->where('nombre', $name)->get()->getRowArray();
        if ($role !== null) {
            if ((int) $role['es_administrador'] !== $isAdmin) {
                $db->table('roles')->where('id', $role['id'])->update(['es_administrador' => $isAdmin]);
            }

            return (int) $role['id'];
        }

        $db->table('roles')->insert([
            'nombre'           => $name,
            'es_administrador' => $isAdmin,
        ]);

        return (int) $db->insertID();
    }
}
