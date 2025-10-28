<?php

namespace App\Controllers;

use App\Models\SociosModel; //Acá asignamos que usamos el modelo designado



class Socios extends BaseController
{
    protected $socios; //Esto es para que la variable en cuestión esté definida

    public function __construct()
    { //La función constructora
        $this->socios = new SociosModel();
    }

    public function index($activo = 1)
    {
        //Esto serñia una consulta,  "Select * from socios"
        $socios = $this->socios->where('activo', $activo)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'socios' => $socios,
            'titulo' => "gestión de socios",
            'pagname' => "Gestion/socios"
        ];



        echo view('panel/header', $context);
        echo view('socios/listado', $context);
        echo view('panel/footer');
    }
    //esta función "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo()
    {
        //este context es para cambiar el título de la página que está esperando el header
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
            'titulo' => "Edición socio",
            'pagname' => 'Gestión/Edición socio'
        ];



        echo view('panel/header', $context);
        echo view('socios/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
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
