<?php

namespace App\Controllers;

use App\Libraries\AccessControl;

class Roles extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $roles = $db->table('roles')
            ->orderBy('nombre')
            ->get()
            ->getResultArray();

        foreach ($roles as &$role) {
            $role['permisos'] = $db->table('roles_acciones')
                ->select('modulo, accion')
                ->where('rol_id', $role['id'])
                ->get()
                ->getResultArray();
            $role['usuarios_asignados'] = $db->table('usuarios')
                ->where('rol_id', $role['id'])
                ->countAllResults();
        }
        unset($role);

        $context = [
            'roles' => $roles,
            'titulo' => 'Roles y permisos',
            'pagname' => 'Gestión/Roles y permisos',
        ];

        echo view('panel/header', $context);
        echo view('roles/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        $context = [
            'rol' => ['id' => null, 'nombre' => '', 'es_administrador' => 0],
            'permisosSeleccionados' => [],
            'modulos' => AccessControl::modules(),
            'acciones' => AccessControl::actions(),
            'accion' => 'guardar',
            'titulo' => 'Nuevo rol',
            'pagname' => 'Gestión/Nuevo rol',
        ];

        echo view('panel/header', $context);
        echo view('roles/formulario', $context);
        echo view('panel/footer');
    }

    public function guardar()
    {
        $nombre = trim((string) $this->request->getPost('nombre'));
        $permissions = $this->permisosEnviados();
        if (!$this->nombreDisponible($nombre)) {
            return redirect()->back()->withInput()->with('error', 'El nombre es obligatorio y debe ser único (máximo 80 caracteres).');
        }
        if ($permissions === null) {
            return redirect()->back()->withInput()->with('error', 'La selección de permisos no es válida.');
        }

        $db = db_connect();
        $db->transBegin();
        $db->table('roles')->insert([
            'nombre' => $nombre,
            'es_administrador' => 0,
        ]);
        $roleId = (int) $db->insertID();
        $this->guardarPermisos($roleId, $permissions);

        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('No se pudo guardar el rol y sus permisos.');
        }
        $db->transCommit();

        return redirect()->to(base_url('public/roles'))->with('mensaje', 'Rol creado correctamente.');
    }

    public function editar($id)
    {
        $db = db_connect();
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if ($role === null || (int) $role['es_administrador'] === 1) {
            return redirect()->to(base_url('public/roles'))
                ->with('error', 'El rol no existe o está protegido.');
        }

        $permissions = $db->table('roles_acciones')
            ->select('modulo, accion')
            ->where('rol_id', $id)
            ->get()
            ->getResultArray();
        $selectedPermissions = [];
        foreach ($permissions as $permission) {
            $selectedPermissions[$permission['modulo']][] = $permission['accion'];
        }
        $context = [
            'rol' => $role,
            'permisosSeleccionados' => $selectedPermissions,
            'modulos' => AccessControl::modules(),
            'acciones' => AccessControl::actions(),
            'accion' => 'actualizar/' . $id,
            'titulo' => 'Editar rol',
            'pagname' => 'Gestión/Editar rol',
        ];

        echo view('panel/header', $context);
        echo view('roles/formulario', $context);
        echo view('panel/footer');
    }

    public function actualizar($id)
    {
        $db = db_connect();
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        $nombre = trim((string) $this->request->getPost('nombre'));
        if ($role === null || (int) $role['es_administrador'] === 1) {
            return redirect()->to(base_url('public/roles'))->with('error', 'El rol no existe o está protegido.');
        }
        if (!$this->nombreDisponible($nombre, (int) $id)) {
            return redirect()->back()->withInput()->with('error', 'El nombre es obligatorio y debe ser único (máximo 80 caracteres).');
        }
        $permissions = $this->permisosEnviados();
        if ($permissions === null) {
            return redirect()->back()->withInput()->with('error', 'La selección de permisos no es válida.');
        }

        $db->transBegin();
        $db->table('roles')->where('id', $id)->update(['nombre' => $nombre]);
        $db->table('roles_modulos')->where('rol_id', $id)->delete();
        $db->table('roles_acciones')->where('rol_id', $id)->delete();
        $this->guardarPermisos((int) $id, $permissions);

        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('No se pudieron actualizar los permisos del rol.');
        }
        $db->transCommit();

        return redirect()->to(base_url('public/roles'))->with('mensaje', 'Rol actualizado correctamente.');
    }

    public function borrar($id)
    {
        $db = db_connect();
        $role = $db->table('roles')->where('id', $id)->get()->getRowArray();
        if ($role === null || (int) $role['es_administrador'] === 1) {
            return redirect()->to(base_url('public/roles'))->with('error', 'El rol no existe o está protegido.');
        }
        if ($db->table('usuarios')->where('rol_id', $id)->countAllResults() > 0) {
            return redirect()->to(base_url('public/roles'))
                ->with('error', 'No se puede borrar un rol que está asignado a usuarios.');
        }

        $db->transBegin();
        $db->table('roles_modulos')->where('rol_id', $id)->delete();
        $db->table('roles_acciones')->where('rol_id', $id)->delete();
        $db->table('roles')->where('id', $id)->delete();
        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('No se pudo borrar el rol.');
        }
        $db->transCommit();

        return redirect()->to(base_url('public/roles'))->with('mensaje', 'Rol borrado correctamente.');
    }

    private function permisosEnviados(): ?array
    {
        $posted = $this->request->getPost('permisos') ?? [];
        if (!is_array($posted)) {
            return null;
        }

        $validModules = AccessControl::modules();
        $validActions = AccessControl::actions();
        $permissions = [];
        foreach ($posted as $module => $actions) {
            if (!is_string($module) || !isset($validModules[$module]) || !is_array($actions)) {
                return null;
            }
            foreach ($actions as $action) {
                if (!is_string($action) || !isset($validActions[$action])) {
                    return null;
                }
                $permissions[$module][$action] = true;
            }
        }

        return $permissions;
    }

    private function guardarPermisos(int $roleId, array $permissionsByModule): void
    {
        $modulePermissions = [];
        $actionPermissions = [];
        foreach ($permissionsByModule as $module => $actions) {
            if ($actions === []) {
                continue;
            }
            $modulePermissions[] = ['rol_id' => $roleId, 'modulo' => $module];
            foreach (array_keys($actions) as $action) {
                $actionPermissions[] = [
                    'rol_id' => $roleId,
                    'modulo' => $module,
                    'accion' => $action,
                ];
            }
        }

        $db = db_connect();
        if ($modulePermissions !== []) {
            $db->table('roles_modulos')->insertBatch($modulePermissions);
        }
        if ($actionPermissions !== []) {
            $db->table('roles_acciones')->insertBatch($actionPermissions);
        }
    }

    private function nombreDisponible(string $nombre, ?int $excludeId = null): bool
    {
        if ($nombre === '' || mb_strlen($nombre) > 80) {
            return false;
        }

        $query = db_connect()->table('roles')->where('nombre', $nombre);
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        return $query->countAllResults() === 0;
    }
}
