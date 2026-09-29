<?php

namespace App\Controllers;

use App\Models\LiquidacionesModel;
use App\Models\PlanesModel;
use App\Models\SociosModel;
use App\Models\PagosModel;

class Liquidaciones extends BaseController
{
    protected $liquidaciones;
    protected $sesion;
    protected $socios;
    protected $pagos;

    public function __construct()
    {
        $this->liquidaciones = new LiquidacionesModel();
        $this->sesion = session();
        $this->socios = new SociosModel();
        $this->pagos = new PagosModel();
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
        
        $planes = (new PlanesModel())->where('activo', 1)->findAll();
        $context = [
            'planes' => $planes,
            'planSeleccionado' => old('id_plan', ''),
            'montoTotal' => old('monto', ''),
            'titulo' => "Nueva Liquidacion",
            'pagname' => 'Gestión/Nuevo Liquidacion'
        ];

        echo view('panel/header', $context);
        echo view('liquidaciones/nuevo', $context);
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $plan = $this->obtenerPlanPost();
        if ($plan === null) {
            return redirect()->back()->withInput()->with('error', 'La selección de planes no es válida.');
        }

        $this->liquidaciones->insert([
            'nombre' => $this->request->getPost('nombre'),
            'monto' => $this->request->getPost('monto'),
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'id_plan' => $plan['id'] ?? null,
            'nombre_plan' => $plan['nombre'] ?? 'Sin plan'
        ]);

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
        $planes = (new PlanesModel())->where('activo', 1)->findAll();
        $context = [
            'liquidacion' => $liquidacion,
            'planes' => $planes,
            'planSeleccionado' => old('id_plan', $liquidacion[0]['id_plan'] ?? ''),
            'montoTotal' => old('monto', $liquidacion[0]['monto']),
            'titulo' => "Edición liquidacion",
            'pagname' => 'Gestión/Edición liquidacion'
        ];



        echo view('panel/header', $context);
        echo view('liquidaciones/editar', $context);
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $plan = $this->obtenerPlanPost();
        if ($plan === null) {
            return redirect()->back()->withInput()->with('error', 'La selección de planes no es válida.');
        }

        $this->liquidaciones->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'monto' => $this->request->getPost('monto'),
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'id_plan' => $plan['id'] ?? null,
            'nombre_plan' => $plan['nombre'] ?? 'Sin plan'
        ]);
        return redirect()->to(base_url() . 'public/liquidaciones/');
    }

    private function obtenerPlanPost(): ?array
    {
        $idPlan = $this->request->getPost('id_plan');
        if ($idPlan === null || $idPlan === '') {
            return [];
        }

        if (!is_scalar($idPlan) || !ctype_digit((string) $idPlan) || (int) $idPlan < 1) {
            return null;
        }

        $plan = (new PlanesModel())
            ->where('activo', 1)
            ->find((int) $idPlan);

        return $plan ?: null;
    }

    //Función para traer las liquidaciones por socios
    public function listado_socio($idSocio)
    {
        // Validar sesión
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        // Validar socio
        $socio = $this->socios->find($idSocio);
        if (!$socio) {
            return redirect()->to(base_url('public/socios'))
                ->with('error', 'El socio no existe');
        }

        $fechaAlta = date('Y-m-d', strtotime($socio['fecha_alta']));

        // Liquidaciones desde la fecha de alta
        $liquidaciones = $this->liquidaciones
            ->where('fecha_vencimiento >=', $fechaAlta)
            ->findAll();

        // Pagos del socio
        $pagos = $this->pagos
            ->where('id_socio', $idSocio)
            ->findAll();

        // Armar historial
        $historial = [];
        foreach ($liquidaciones as $liq) {
            $pagosLiquidacion = array_filter(
                $pagos,
                fn($p) => (int) $p['id_liquidacion'] === (int) $liq['id']
            );
            $totalPagado = round(
                array_sum(array_map(
                    static fn($p) => (float) $p['monto'],
                    $pagosLiquidacion
                )),
                2
            );
            $montoLiquidacion = (float) $liq['monto'];
            $pago = $pagosLiquidacion ? reset($pagosLiquidacion) : null;
            $estado = $totalPagado <= 0
                ? 'Pendiente'
                : ($totalPagado < $montoLiquidacion ? 'Pago parcial' : 'Pagado');

            $historial[] = [
                'liquidacion' => $liq,
                'pago' => $pago,
                'total_pagado' => $totalPagado,
                'saldo_pendiente' => max(0, round($montoLiquidacion - $totalPagado, 2)),
                'estado' => $estado
            ];
        }

        $context = [
            'titulo' => "Liquidaciones del socio",
            'historial' => $historial,
            'idSocio' => $idSocio,
            'socio' => $socio
        ];

        echo view('panel/header', $context);
        echo view('liquidaciones/listado_socio', $context);
        echo view('panel/footer');
    }
}
