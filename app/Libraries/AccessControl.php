<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

class AccessControl
{
    private const MODULES = [
        'zonas'          => 'Zonas',
        'usuarios'       => 'Usuarios',
        'socios'         => 'Socios',
        'cajas'          => 'Cajas',
        'recaudadores'   => 'Recaudadores',
        'liquidaciones'  => 'Liquidaciones',
        'pagos'          => 'Pagos',
        'planes'         => 'Planes',
        'disciplinas'    => 'Disciplinas',
    ];

    private const ACTIONS = [
        'ver'      => 'Ver',
        'crear'    => 'Crear',
        'editar'   => 'Editar',
        'eliminar' => 'Eliminar',
    ];

    public static function modules(): array
    {
        return self::MODULES;
    }

    public static function actions(): array
    {
        return self::ACTIONS;
    }

    public function canAccess(int $userId, string $module): bool
    {
        return $this->canPerform($userId, $module, 'ver');
    }

    public function canPerform(int $userId, string $module, string $action): bool
    {
        if (!isset(self::MODULES[$module]) || !isset(self::ACTIONS[$action])) {
            return false;
        }

        if ($this->isAdministrator($userId)) {
            return true;
        }

        return $this->database()
            ->table('usuarios')
            ->join('roles_acciones', 'roles_acciones.rol_id = usuarios.rol_id')
            ->where('usuarios.id', $userId)
            ->where('roles_acciones.modulo', $module)
            ->where('roles_acciones.accion', $action)
            ->countAllResults() > 0;
    }

    public function allowedActions(int $userId): array
    {
        if ($this->isAdministrator($userId)) {
            $allActions = [];
            foreach (self::MODULES as $module => $_label) {
                $allActions[$module] = array_keys(self::ACTIONS);
            }

            return $allActions;
        }

        $permissions = $this->database()
            ->table('roles_acciones')
            ->select('roles_acciones.modulo, roles_acciones.accion')
            ->join('usuarios', 'usuarios.rol_id = roles_acciones.rol_id')
            ->where('usuarios.id', $userId)
            ->get()
            ->getResultArray();
        $allowed = [];
        foreach ($permissions as $permission) {
            $allowed[$permission['modulo']][] = $permission['accion'];
        }

        return $allowed;
    }

    public function allowedModules(int $userId): array
    {
        $permissions = $this->database()
            ->table('roles_acciones')
            ->select('roles_acciones.modulo')
            ->join('usuarios', 'usuarios.rol_id = roles_acciones.rol_id')
            ->where('usuarios.id', $userId)
            ->where('roles_acciones.accion', 'ver')
            ->get()
            ->getResultArray();

        return array_values(array_unique(array_column($permissions, 'modulo')));
    }

    public function isAdministrator(int $userId): bool
    {
        return $this->database()
            ->table('usuarios')
            ->join('roles', 'roles.id = usuarios.rol_id')
            ->where('usuarios.id', $userId)
            ->where('roles.es_administrador', 1)
            ->countAllResults() > 0;
    }

    public function roleForUser(int $userId): ?array
    {
        $role = $this->database()
            ->table('usuarios')
            ->select('roles.id, roles.nombre, roles.es_administrador')
            ->join('roles', 'roles.id = usuarios.rol_id')
            ->where('usuarios.id', $userId)
            ->get()
            ->getRowArray();

        return $role ?: null;
    }

    private function database(): BaseConnection
    {
        return \Config\Database::connect();
    }
}
