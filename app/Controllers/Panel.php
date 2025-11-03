<?php

namespace App\Controllers;

use App\Models\UsuariosModel; 
use App\Models\SociosModel;
use App\Models\RecaudadoresModel;
use App\Models\ZonasModel;
class Panel extends BaseController
{
    protected $usuarios; 
    protected $socios;
    protected $recaudadores;
    protected $zonas;
    public function __construct() { //La función constructora
        
        $this->usuarios = new UsuariosModel();
        $this->socios = new SociosModel();
        $this->recaudadores = new RecaudadoresModel();
        $this->zonas = new ZonasModel();
        
        $this->sesion = session();

           
    }

    public function index()
    {
       if(!isset($this->sesion->id_usuario)){
        return redirect()->to(base_url() .'public/login');
        }

        //Esto seria una consulta,  "Select * from clientes"
        $usuarios = $this->usuarios->findAll();
        $socios = $this->socios->findAll();
        $recaudadores = $this->recaudadores->findAll();
        $zonas = $this->zonas->findAll();
        
        $totalUsuarios = $this->usuarios->countAll();
        $totalSocios = $this->socios->countAll();
        $totalRecaudadores = $this->recaudadores->countAll();
        $totalZonas = $this->zonas->countAll();
        
        
        $context = ['titulo' => 'Tablero', 'totalSocios' => $totalSocios,
                     'totalUsuarios' => $totalUsuarios,
                    'totalRecaudadores' => $totalRecaudadores,
                    'totalZonas' => $totalZonas];
       

        echo view('panel/header', $context);
        echo view('panel/contenido');
        echo view('panel/footer');
 
    }

    public function nuevo()
    {
        if(!isset($this->sesion->id_usuario)){
        return redirect()->to(base_url() .'public/login');
        }
        $contex['titulo'] = "Panel"; //los valores de contexto pueden ser cualquier tipo de dato, siempre tiene que tener
        $contex['subtitulo'] = "Este es el panel de control";
        $contex['pagname'] = "Panel de control";
        echo view('inicio', $contex);
        echo view('nuevo', $contex); //se envia la variable de contexto a esta vista
        echo view('fin');
    }
}
