<?php

namespace App\Controllers;

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
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $usuarios = $this->usuarios->where('activo', $activo)->findAll();

        $context = [
            'usuarios' => $usuarios,
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
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $hash = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT);
        $this->usuarios->save(
            [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'username' => $this->request->getPost('username'),
                'clave' => $hash,
                'dni' => $this->request->getPost('dni')
            ]
        );
        return redirect()->to(base_url() . 'public/usuarios/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->usuarios->delete($id);
        return redirect()->to(base_url() . 'public/usuarios/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $usuario = $this->usuarios->where('id', $id)->findAll();
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
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }


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
