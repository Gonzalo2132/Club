<?php

namespace App\Controllers;

use App\Libraries\AccessControl;
use App\Models\UsuariosModel;

class Usuarios extends BaseController
{
    protected $usuarios;
    protected $sesion;
    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        $usuarios = $this->usuarios
            ->select('usuarios.*, roles.nombre AS rol_nombre, roles.es_administrador AS rol_es_administrador')
            ->join('roles', 'roles.id = usuarios.rol_id', 'left')
            ->where('usuarios.activo', $activo)
            ->findAll();
        $roles = db_connect()->table('roles')->orderBy('nombre')->get()->getResultArray();
        $esAdministrador = (new AccessControl())->isAdministrator((int) $this->sesion->id_usuario);

        $context = [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'esAdministrador' => $esAdministrador,
            'usuarioActualId' => (int) $this->sesion->id_usuario,
            'titulo' => "Usuarios",
            'pagname' => "Gestión/Usuarios"
        ];

        echo view('panel/header', $context);
        echo view('usuarios/listado', $context);
        echo view('panel/footer');
    }
    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $context = [
            'titulo' => "Nuevo usuario",
            'pagname' => 'Gestión/Nueva usuario'
        ];

        echo view('panel/header', $context);
        echo view('usuarios/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
        $hash = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT);
        $this->usuarios->save(
            [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'username' => $this->request->getPost('username'),
                'clave' => $hash,
                'dni' => $this->request->getPost('dni'),
                'rol_id' => null,
            ]
        );
        return redirect()->to(base_url() . 'public/usuarios/');
    }
    public function borrar($id)
    {
        $role = (new AccessControl())->roleForUser((int) $id);
        if ((int) $id === (int) $this->sesion->id_usuario
            || ($role !== null && (int) $role['es_administrador'] === 1)) {
            return redirect()->to(base_url('public/usuarios'))
                ->with('error', 'No se puede borrar la cuenta administradora ni la sesión actual.');
        }

        $this->usuarios->delete($id);
        return redirect()->to(base_url() . 'public/usuarios/');
    }

    public function asignarRol($id)
    {
        $access = new AccessControl();
        $userId = (int) $this->sesion->id_usuario;
        if (!$access->isAdministrator($userId)) {
            return $this->response->setStatusCode(403)->setBody('Solo un administrador puede asignar roles.');
        }
        if ((int) $id === $userId) {
            return redirect()->to(base_url('public/usuarios'))
                ->with('error', 'No puedes cambiar tu propio rol.');
        }

        $roleId = filter_var($this->request->getPost('rol_id'), FILTER_VALIDATE_INT);
        $role = $roleId
            ? db_connect()->table('roles')->where('id', $roleId)->get()->getRowArray()
            : null;

        if ($role === null) {
            return redirect()->to(base_url('public/usuarios'))
                ->with('error', 'Selecciona un rol válido.');
        }

        $targetUser = $this->usuarios->find($id);
        if ($targetUser === null) {
            return redirect()->to(base_url('public/usuarios'))
                ->with('error', 'No se encontró el usuario seleccionado.');
        }
        $currentRole = $access->roleForUser((int) $id);
        if (($currentRole !== null && (int) $currentRole['es_administrador'] === 1)
            || (int) $role['es_administrador'] === 1) {
            return redirect()->to(base_url('public/usuarios'))
                ->with('error', 'La cuenta administradora y su rol están protegidos.');
        }

        $this->usuarios->update($id, ['rol_id' => (int) $role['id']]);
        return redirect()->to(base_url('public/usuarios'))
            ->with('mensaje', 'Rol asignado correctamente.');
    }

    public function editar($id)
    {
        $usuario = $this->usuarios->where('id', $id)->findAll();
        if ($usuario === []) {
            return redirect()->to(base_url('public/usuarios'))
                ->with('error', 'No se encontró el usuario seleccionado.');
        }
        $context = [
            'usuario' => $usuario,
            'titulo' => "Edición usuario",
            'pagname' => 'Gestión/Edición usuario'
        ];


        echo view('panel/header', $context);
        echo view('usuarios/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'username' => $this->request->getPost('username'),
            'dni' => $this->request->getPost('dni')
        ];


        $clave_nueva = $this->request->getPost('clave');

        //Verificar si se ingresó una nueva contraseña
        if (!empty($clave_nueva)) {

            $hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
            $data['clave'] = $hash;
        }


        $this->usuarios->update($id, $data);

        return redirect()->to(base_url() . 'public/usuarios/');
    }
}
