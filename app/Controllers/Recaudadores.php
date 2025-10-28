<?php

namespace App\Controllers;

use App\Models\RecaudadoresModel; //Acá asignamos que usamos el modelo designado



class Recaudadores extends BaseController
{
    protected $recaudadores; //Esto es para que la variable en cuestión esté definida

    public function __construct()
    { //La función constructora
        $this->recaudadores = new RecaudadoresModel();
    }

    public function index($activo = 1)
    {
        //Esto serñia una consulta,  "Select * from socios"
        $recaudadores = $this->recaudadores->where('activo', $activo)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'recaudadores' => $recaudadores,
            'titulo' => "Gestión de recaudadores",
            'pagname' => "Gestion/recaudadores"
        ];



        echo view('panel/header', $context);
        echo view('recaudadores/listado', $context);
        echo view('panel/footer');
    }
    //esta función "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo()
    {
        //este context es para cambiar el título de la página que está esperando el header
        $context = [
            'titulo' => "Nuevo Recaudador",
            'pagname' => 'Gestión/Nuevo Recaudador'
        ];

        echo view('panel/header', $context);
        echo view('recaudadores/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
        $this->recaudadores->save(
            [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'dni' => $this->request->getPost('dni'),
                
            ]
        );
        return redirect()->to(base_url() . 'public/recaudadores/');
    }
    public function borrar($id)
    {
        $this->recaudadores->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'public/recaudadores/');
    }
    public function editar($id)
    {
        $recaudador = $this->recaudadores->where('id', $id)->findAll();
        $context = [
            'recaudador' => $recaudador,
            'titulo' => "Edición recaudador",
            'pagname' => 'Gestión/Edición recaudador'
        ];



        echo view('panel/header', $context);
        echo view('recaudadores/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        //primer parámetro es el where en este caso $id
        $this->recaudadores->update(
            $id,
            [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'dni' => $this->request->getPost('dni')
            ]
        );
        return redirect()->to(base_url() . 'public/recaudadores/');
    }
}
