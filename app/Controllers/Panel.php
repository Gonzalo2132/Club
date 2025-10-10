<?php

namespace App\Controllers;

use App\Models\ClientesModel; //Acá asignamos que usamos el modelo designado



class Panel extends BaseController
{
    protected $clientes; //Esto es para que la variable en cuestion este definida

    public function __construct() { //La función constructora
        $this->clientes = new ClientesModel();
    }

    public function index()
    {
        
    

        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable
        $t_clientes  = $this->clientes->countAll();


        $context = ['titulo' => 'Tablero',
    't_clientes'=>$t_clientes

];


        /*consulta sql para contabilizar registros: SELECT COUNT(*) AS total_registros
         FROM tu_tabla;*/

        ////nueva implementacion
/*
        $clientes = $this->session->userdata('clientes'); 
        $cantidad_clientes =$this->ClientesModel->getCantidadClientes($clientes);
      */

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
