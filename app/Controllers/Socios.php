<?php

namespace App\Controllers;

use App\Models\SociosModel; //Acá asignamos que usamos el modelo designado
use App\Models\ZonasModel; //Acá asignamos que usamos el modelo designado



class Socios extends BaseController
{
    protected $socios; //Esto es para que la variable en cuestión esté definida

    public function __construct()
    { //La función constructora
        $this->socios = new SociosModel();
        $this->zonas = new ZonasModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //Esto serñia una consulta,  "Select * from socios"
        $socios = $this->socios->where('activo', $activo)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'socios' => $socios,
            'titulo' => "Gestión de Socios",
            'pagname' => "Gestion/socios"
        ];



        echo view('panel/header', $context);
        echo view('socios/listado', $context);
        echo view('panel/footer');
    }
    //esta función "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //este context es para cambiar el título de la página que está esperando el header
        $zonas = $this->zonas->where('activo', $activo)->findAll();
        $context = [
            'titulo' => "Nuevo Socio",
            'pagname' => 'Gestión/Nuevo Socio',
            'zonas' => $zonas
        ];

        echo view('panel/header', $context);
        echo view('socios/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->socios->save(
            [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'domicilio' => $this->request->getPost('domicilio'),
                'telefono' => $this->request->getPost('telefono'),
                'fecha_nac' => $this->request->getPost('fecha_nac'),
                'dni' => $this->request->getPost('dni'),
                'email' => $this->request->getPost('email'),
                'id_zona' => $this->request->getPost('id_zona')

            ]
        );
        return redirect()->to(base_url() . 'public/socios/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->socios->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'public/socios/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $status = 1;
        $socio = $this->socios->where('id', $id)->findAll();
        $zonas = $this->zonas->where('activo', $status)->findall();
        $context = [
            'socio' => $socio,
            'titulo' => "Edición socio",
            'pagname' => 'Gestión/Edición socio',
            'zonas' => $zonas
        ];



        echo view('panel/header', $context);
        echo view('socios/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //primer parámetro es el where en este caso $id
        $this->socios->update(
            $id,
            [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'domicilio' => $this->request->getPost('domicilio'),
                'telefono' => $this->request->getPost('telefono'),
                'fecha_nac' => $this->request->getPost('fecha_nac'),
                'dni' => $this->request->getPost('dni'),
                'email' => $this->request->getPost('email'),
                'id_zona' => $this->request->getPost('id_zona')
            ]
        );
        return redirect()->to(base_url() . 'public/socios/');
    }
}
