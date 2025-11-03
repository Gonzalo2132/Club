<?php

namespace App\Controllers;

class Panel extends BaseController
{
    
    public function __construct()
    { //La función constructora

        $this->sesion = session();
    }
    public function index()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $context = [
            'titulo' => 'Tablero',
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
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }
        $context['titulo'] = "Panel"; //los valores de contexto pueden ser cualquier tipo de dato, siempre tiene que tener
        $context['subtitulo'] = "Este es el panel de control";
        $context['pagname'] = "Panel de control";
        echo view('inicio', $context);
        echo view('nuevo', $context); //se envia la variable de contexto a esta vista
        echo view('fin');
    }
}
