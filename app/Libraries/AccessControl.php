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

    public static function modules(): array
    {
        return self::MODULES;
    }

    public function canAccess(int $userId, string $module): bool
    {
        if (!isset(self::MODULES[$module])) {
            return false;
        }

        return $this->database()
            ->table('usuarios')
            ->join('roles', 'roles.id = usuarios.rol_id')
            ->join('roles_modulos', 'roles_modulos.rol_id = roles.id')
            ->where('usuarios.id', $userId)
            ->where('roles_modulos.modulo', $module)
            ->countAllResults() > 0;
    }

    public function allowedModules(int $userId): array
    {
        $permissions = $this->database()
            ->table('roles_modulos')
            ->select('roles_modulos.modulo')
            ->join('usuarios', 'usuarios.rol_id = roles_modulos.rol_id')
            ->where('usuarios.id', $userId)
            ->get()
            ->getResultArray();

        return array_column($permissions, 'modulo');
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
