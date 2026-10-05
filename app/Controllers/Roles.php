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
            $role['modulos'] = $db->table('roles_modulos')
                ->select('modulo')
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
            'modulosSeleccionados' => [],
            'modulos' => AccessControl::modules(),
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
        if (!$this->nombreDisponible($nombre)) {
            return redirect()->back()->withInput()->with('error', 'El nombre es obligatorio y debe ser único (máximo 80 caracteres).');
        }

        $db = db_connect();
        $db->transBegin();
        $db->table('roles')->insert([
            'nombre' => $nombre,
            'es_administrador' => 0,
        ]);
        $roleId = (int) $db->insertID();
        $this->guardarModulos($roleId);

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

        $permissions = $db->table('roles_modulos')
            ->select('modulo')
            ->where('rol_id', $id)
            ->get()
            ->getResultArray();
        $context = [
            'rol' => $role,
            'modulosSeleccionados' => array_column($permissions, 'modulo'),
            'modulos' => AccessControl::modules(),
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

        $db->transBegin();
        $db->table('roles')->where('id', $id)->update(['nombre' => $nombre]);
        $db->table('roles_modulos')->where('rol_id', $id)->delete();
        $this->guardarModulos((int) $id);

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
        $db->table('roles')->where('id', $id)->delete();
        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('No se pudo borrar el rol.');
        }
        $db->transCommit();

        return redirect()->to(base_url('public/roles'))->with('mensaje', 'Rol borrado correctamente.');
    }

    private function guardarModulos(int $roleId): void
    {
        $posted = $this->request->getPost('modulos');
        if (!is_array($posted)) {
            return;
        }

        $postedModules = array_filter($posted, 'is_string');
        $validModules = array_intersect(
            array_keys(AccessControl::modules()),
            $postedModules
        );
        $permissions = [];
        foreach (array_unique($validModules) as $module) {
            $permissions[] = ['rol_id' => $roleId, 'modulo' => $module];
        }
        if ($permissions !== []) {
            db_connect()->table('roles_modulos')->insertBatch($permissions);
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
