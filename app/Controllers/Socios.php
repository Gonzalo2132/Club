<?php

namespace App\Controllers;

use App\Models\SociosModel;
use App\Models\PlanesModel;
use App\Models\ZonasModel;
use App\Models\SociosPlanesModel;
use App\Models\LiquidacionesModel;
use App\Models\PagosModel;

class Socios extends BaseController
{
    protected $socios;
    protected $zonas;
    protected $sociosPlanes;
    protected $sesion;
    protected $liquidaciones;
    protected $pagos;

    public function __construct()
    { //La función constructora
        $this->socios = new SociosModel();
        $this->zonas = new ZonasModel();
        $this->sociosPlanes = new SociosPlanesModel();
        $this->sesion = session();
        $this->liquidaciones = new LiquidacionesModel();
        $this->pagos = new PagosModel();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $socios = $this->socios->select('socios.*, GROUP_CONCAT(planes.nombre SEPARATOR " | ") as planes_nombres')
                               ->join('socios_planes', 'socios_planes.id_socio = socios.id AND socios_planes.fecha_borrado IS NULL', 'left')
                               ->join('planes', 'planes.id = socios_planes.id_plan', 'left')
                               ->where('socios.activo', $activo)
                               ->groupBy('socios.id')
                               ->findAll();

        //Esto serñia una consulta,  "Select * from socios"
        //$socios = $this->socios->where('activo', $activo)->findAll();

        //Traer todas las zonas
        $zonas = $this->zonas->where('activo',1)->findAll();

        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'socios' => $socios,
            'zonas' => $zonas,
            'titulo' => "Gestión de Socios",
            'pagname' => "Gestión/socios"
        ];



        echo view('panel/header', $context);
        echo view('socios/listado', $context);
        echo view('panel/footer');
    }
    //esta función "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $planesModel = new PlanesModel();
        $planes = $planesModel->where('activo', 1)->findAll();
        //este context es para cambiar el título de la página que está esperando el header
        $zonas = $this->zonas->where('activo', $activo)->findAll();
        $context = [
            'planes' => $planes,
            'titulo' => "Nuevo Socio",
            'pagname' => 'Gestión/Nuevo Socio',
            'zonas' => $zonas
        ];

        echo view('panel/header', $context);
        echo view('socios/nuevo', $context);
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $id_socio = $this->socios->insert(
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

        $planes_seleccionados = $this->request->getPost('planes'); // Viene como un arreglo []
        if (!empty($planes_seleccionados)) {
            foreach ($planes_seleccionados as $id_plan) {
                $this->sociosPlanes->insert([
                    'id_socio' => $id_socio,
                    'id_plan' => $id_plan
                ]);
            }
        }

        return redirect()->to(base_url() . 'public/socios/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //$this->socios->update($id, ['activo' => 0]);
        $this->socios->delete($id);
        return redirect()->to(base_url() . 'public/socios/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $planesModel = new PlanesModel();
        $planes = $planesModel->where('activo', 1)->findAll();
        $status = 1;
        $socio = $this->socios->where('id', $id)->findAll();
        $zonas = $this->zonas->where('activo', $status)->findall();     

        $planesAsignados = $this->sociosPlanes->where('id_socio', $id)->findAll();
        $misPlanesIds = array_column($planesAsignados, 'id_plan');

        $context = [
            'socio' => $socio,
            'planes' => $planes,
            'misPlanes' => $misPlanesIds,
            'titulo' => "Edición socio",
            'pagname' => 'Gestión/Edición socio',
            'zonas' => $zonas
        ];



        echo view('panel/header', $context);
        echo view('socios/editar', $context);
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

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

        $this->sociosPlanes->where('id_socio', $id)->delete();

        $planes_seleccionados = $this->request->getPost('planes'); 
        if (!empty($planes_seleccionados)) {
            foreach ($planes_seleccionados as $id_plan) {
                $this->sociosPlanes->insert([
                    'id_socio' => $id,
                    'id_plan' => $id_plan
                ]);
            }
        }

        return redirect()->to(base_url() . 'public/socios/');
    }

    public function informe($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $socio = $this->socios->find($id);
        if (!$socio) {
            return redirect()->to(base_url('public/socios'))
                ->with('error', 'El socio no existe.');
        }

        $zona = $this->zonas->find($socio['id_zona']);
        $planesAsignados = $this->sociosPlanes
            ->select('planes.id, planes.nombre, planes.descripcion')
            ->join('planes', 'planes.id = socios_planes.id_plan')
            ->where('socios_planes.id_socio', $id)
            ->findAll();

        $fechaAlta = date('Y-m-d', strtotime($socio['fecha_alta']));
        $liquidaciones = $this->liquidaciones
            ->where('fecha_vencimiento >=', $fechaAlta)
            ->findAll();
        $idsPlanesSocio = array_map(
            'intval',
            array_column($planesAsignados, 'id')
        );
        $liquidaciones = array_values(array_filter(
            $liquidaciones,
            static fn(array $liquidacion): bool =>
                empty($liquidacion['id_plan'])
                || in_array((int) $liquidacion['id_plan'], $idsPlanesSocio, true)
        ));

        $pagos = $this->pagos
            ->where('id_socio', $id)
            ->findAll();
        $informeLiquidaciones = [];
        foreach ($liquidaciones as $liquidacion) {
            $pagosLiquidacion = array_filter(
                $pagos,
                static fn(array $pago): bool =>
                    (int) $pago['id_liquidacion'] === (int) $liquidacion['id']
            );
            $totalPagado = round(array_sum(array_map(
                static fn(array $pago): float => (float) $pago['monto'],
                $pagosLiquidacion
            )), 2);
            $monto = (float) $liquidacion['monto'];

            $informeLiquidaciones[] = [
                'liquidacion' => $liquidacion,
                'total_pagado' => $totalPagado,
                'saldo_pendiente' => max(0, round($monto - $totalPagado, 2)),
                'estado' => $totalPagado <= 0
                    ? 'Pendiente'
                    : ($totalPagado < $monto ? 'Pago parcial' : 'Pagado')
            ];
        }

        $context = [
            'socio' => $socio,
            'zona' => $zona,
            'planes' => $planesAsignados,
            'liquidaciones' => $informeLiquidaciones,
            'titulo' => 'Vista previa del socio',
            'pagname' => 'Gestión/Informe de socio'
        ];

        echo view('panel/header', $context);
        echo view('socios/informe', $context);
        echo view('panel/footer');
    }
}
