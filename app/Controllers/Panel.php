<?php

namespace App\Controllers;

class Panel extends BaseController
{
    public function index()
    {
        $context = ['titulo' => 'Tablero',
];

        echo view('panel/header', $context);
        echo view('panel/contenido');
        echo view('panel/footer');
       // echo view('inicio', $contex);
        //echo view('contenido', $contex);
        //echo view('fin');
 
    }

    public function nuevo()
    {
        $contex['titulo'] = "Panel"; //los valores de contexto pueden ser cualquier tipo de dato, siempre tiene que tener
        $contex['subtitulo'] = "Este es el panel de control";
        $contex['pagname'] = "Panel de control";
        echo view('inicio', $context);
        echo view('nuevo', $context); //se envia la variable de contexto a esta vista
        echo view('fin');
    }
}
