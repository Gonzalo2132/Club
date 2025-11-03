<?php

namespace App\Controllers;

use App\Models\ZonasModel; //Acá asignamos que usamos el modelo designado



class Zonas extends BaseController
{
    protected $zonas; //Esto es para que la variable en cuestion este definida

    public function __construct()
    { //La función constructora
        $this->zonas = new ZonasModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //Esto seria una consulta,  "Select * from clientes"
        $zonas = $this->zonas->where('activo', $activo)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'zonas' => $zonas,
            'titulo' => "Zonas",
            'pagname' => "Gestión/Zonas"
        ];



        echo view('panel/header', $context);
        echo view('zonas/listado', $context);
        echo view('panel/footer');
    }
    //esta funcion "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //este context es para cambiar el titulo de la pagina que esta esperando el header
        $context = [
            'titulo' => "Nueva zona",
            'pagname' => 'Gestión/Nueva zona'
        ];

        echo view('panel/header', $context);
        echo view('zonas/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->zonas->save(
            [
                'denominacion' => $this->request->getPost('denominacion')

            ]
        );
        return redirect()->to(base_url() . 'public/zonas/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->zonas->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'public/zonas/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $zona = $this->zonas->where('id', $id)->findAll();
        $context = [
            'zona' => $zona,
            'titulo' => "Edición zona",
            'pagname' => 'Gestión/Edición zona'
        ];



        echo view('panel/header', $context);
        echo view('zonas/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //primer parametro es el where en este caso $id
        $this->zonas->update(
            $id,
            [
                'denominacion' => $this->request->getPost('denominacion')

            ]
        );
        return redirect()->to(base_url() . 'public/zonas/');
    }
}
