<?php

namespace App\Controllers;

use App\Models\SociosModel; //Acá asignamos que usamos el modelo designado



class Socios extends BaseController
{
    protected $socios; //Esto es para que la variable en cuestion este definida

    public function __construct()
    { //La función constructora
        $this->socios = new SociosModel();
    }

    public function index($estatus = 1)
    {
        //Esto seria una consulta,  "Select * from socios"
        $socios = $this->socios->where('estatus', $estatus)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'socios' => $socios,
            'titulo' => "gestion de socios",
            'pagname' => "Gestion/socios"
        ];



        echo view('panel/header', $context);
        echo view('socios/listado', $context);
        echo view('panel/footer');
    }
    //esta funcion "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo()
    {
        //este context es para cambiar el titulo de la pagina que esta esperando el header
        $context = [
            'titulo' => "nuevo Socio",
            'pagname' => 'Gestión/Nuevo Socio'
        ];

        echo view('panel/header', $context);
        echo view('socios/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
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
        $this->socios->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'public/socios/');
    }
    public function editar($id)
    {
        $socio = $this->socios->where('id', $id)->findAll();
        $context = [
            'socio' => $socio,
            'titulo' => "edicion socio",
            'pagname' => 'Gestión/Edición socio'
        ];



        echo view('panel/header', $context);
        echo view('socios/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        //primer parametro es el where en este caso $id
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
