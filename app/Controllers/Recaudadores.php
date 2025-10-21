<?php

namespace App\Controllers;

use App\Models\RecaudadoresModel; //Acá asignamos que usamos el modelo designado



class Recaudadores extends BaseController
{
    protected $recaudadores; //Esto es para que la variable en cuestion este definida

    public function __construct()
    { //La función constructora
        $this->recaudadores = new RecaudadoresModel();
    }

    public function index()
    {
        //Esto seria una consulta,  "Select * from clientes"
        $recaudadores = $this->recaudadores->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable
        $contex = ['recaudadores' => $recaudadores];
        $contex['titulo'] = "Recaudador";
        $contex['pagname'] = "Panel de control";

        echo view('inicio', $contex);
        echo view('contenido', $contex);
        echo view('fin');
 
    }

    public function nuevo()
    {
        $contex['titulo'] = "Panel"; //los valores de contexto pueden ser cualquier tipo de dato, siempre tiene que tener
        $contex['subtitulo'] = "Este es el panel de control";
        $contex['pagname'] = "Panel de control";
        echo view('inicio', $contex);
        echo view('nuevo', $contex); //se envia la variable de contexto a esta vista
        echo view('fin');
    }
}
