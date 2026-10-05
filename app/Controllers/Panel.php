<?php

namespace App\Controllers;

use App\Libraries\AccessControl;
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
    protected $sesion;
    public function __construct()
    { //La función constructora

        $this->usuarios = new UsuariosModel();
        $this->socios = new SociosModel();
        $this->recaudadores = new RecaudadoresModel();
        $this->zonas = new ZonasModel();

        $this->sesion = session();
    }

    public function index()
    {
        $modulosPermitidos = (new AccessControl())->allowedModules((int) $this->sesion->id_usuario);

        $totalUsuarios = in_array('usuarios', $modulosPermitidos, true) ? $this->usuarios->countAll() : null;
        $totalSocios = in_array('socios', $modulosPermitidos, true) ? $this->socios->countAll() : null;
        $totalRecaudadores = in_array('recaudadores', $modulosPermitidos, true) ? $this->recaudadores->countAll() : null;
        $totalZonas = in_array('zonas', $modulosPermitidos, true) ? $this->zonas->countAll() : null;

        $context = [
            'titulo' => 'Tablero',
            'modulosPermitidos' => $modulosPermitidos,
            'totalSocios' => $totalSocios,
            'totalUsuarios' => $totalUsuarios,
            'totalRecaudadores' => $totalRecaudadores,
            'totalZonas' => $totalZonas
        ];


        echo view('panel/header', $context);
        echo view('panel/contenido');
        echo view('panel/footer');
    }

    public function nuevo()
    {
        /*if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }
        */
        $contex['titulo'] = "Panel"; //los valores de contexto pueden ser cualquier tipo de dato, siempre tiene que tener
        $contex['subtitulo'] = "Este es el panel de control";
        $contex['pagname'] = "Panel de control";
        echo view('inicio', $contex);
        echo view('nuevo', $contex); //se envia la variable de contexto a esta vista
        echo view('fin');
    }
}
