<?php

namespace App\Controllers;

use App\Models\LiquidacionesModel;

class Liquidaciones extends BaseController
{
    protected $liquidaciones; 
    protected $sesion;

    public function __construct()
    { 
        $this->liquidaciones = new LiquidacionesModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        
        $liquidaciones = $this->liquidaciones->where('activo', $activo)->findAll();
        
        $context = [
            'liquidaciones' => $liquidaciones,
            'titulo' => "Gestión de Liquidaciones",
            'pagname' => "Gestion/liquidaciones"
        ];



        echo view('panel/header', $context);
        echo view('liquidaciones/listado', $context);
        echo view('panel/footer');
    }
    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $context = [
            'titulo' => "Nueva Liquidacion",
            'pagname' => 'Gestión/Nuevo Liquidacion'
        ];

        echo view('panel/header', $context);
        echo view('liquidaciones/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->liquidaciones->save(
            [
                'nombre' => $this->request->getPost('nombre'),
                'monto' => $this->request->getPost('monto'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),

            ]
        );
        return redirect()->to(base_url() . 'public/liquidaciones/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->liquidaciones->delete($id);
        return redirect()->to(base_url() . 'public/liquidaciones/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $liquidacion = $this->liquidaciones->where('id', $id)->findAll();
        $context = [
            'liquidacion' => $liquidacion,
            'titulo' => "Edición liquidacion",
            'pagname' => 'Gestión/Edición liquidacion'
        ];



        echo view('panel/header', $context);
        echo view('liquidaciones/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

       $this->liquidaciones->update(
            $id,
            [
                'nombre' => $this->request->getPost('nombre'),
                'monto' => $this->request->getPost('monto'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento')
            ]
        );
        return redirect()->to(base_url() . 'public/liquidaciones/');
    }
}
