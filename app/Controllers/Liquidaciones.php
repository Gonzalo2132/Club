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
        $planesPorLiquidacion = [];
        if ($liquidaciones) {
            $idsLiquidaciones = array_column($liquidaciones, 'id');
            $relaciones = \Config\Database::connect()
                ->table('liquidaciones_planes')
                ->select('liquidaciones_planes.id_liquidacion, GROUP_CONCAT(planes.id ORDER BY planes.id SEPARATOR ", ") AS planes_ids, GROUP_CONCAT(CONCAT(planes.nombre, " ($", FORMAT(liquidaciones_planes.monto, 2), ")") ORDER BY planes.id SEPARATOR " | ") AS planes_nombres')
                ->join('planes', 'planes.id = liquidaciones_planes.id_plan')
                ->whereIn('liquidaciones_planes.id_liquidacion', $idsLiquidaciones)
                ->groupBy('liquidaciones_planes.id_liquidacion')
                ->get()
                ->getResultArray();

            foreach ($relaciones as $relacion) {
                $planesPorLiquidacion[$relacion['id_liquidacion']] = $relacion;
            }
        }

        $context = [
            'liquidaciones' => $liquidaciones,
            'planesPorLiquidacion' => $planesPorLiquidacion,
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
            'planesSeleccionados' => [],
            'montosPlanes' => [],
            'montoTotal' => old('monto', '0.00'),
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

        $planesSeleccionados = $this->obtenerPlanesPost();
        if ($planesSeleccionados === null) {
            return redirect()->back()->withInput()->with('error', 'La selección de planes no es válida.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();
        $montoTotal = $planesSeleccionados
            ? array_sum(array_column($planesSeleccionados, 'monto'))
            : $this->request->getPost('monto');
        $idLiquidacion = $this->liquidaciones->insert([
            'nombre' => $this->request->getPost('nombre'),
            'monto' => $montoTotal,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'id_plan' => $planesSeleccionados[0]['id'] ?? null,
            'nombre_plan' => $this->nombrePlanes($planesSeleccionados)
        ]);

        if ($idLiquidacion && $planesSeleccionados) {
            $db->table('liquidaciones_planes')->insertBatch(
                $this->relacionesPlanes((int) $idLiquidacion, $planesSeleccionados)
            );
        }

        if (!$idLiquidacion || !$db->transStatus()) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudo guardar la liquidación y sus planes.');
        }

        $db->transCommit();
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
        $relaciones = \Config\Database::connect()
            ->table('liquidaciones_planes')
            ->select('id_plan, monto')
            ->where('id_liquidacion', $id)
            ->get()
            ->getResultArray();
        $planesSeleccionados = array_column($relaciones, 'id_plan');
        $montosPlanes = [];
        foreach ($relaciones as $relacion) {
            $montosPlanes[$relacion['id_plan']] = $relacion['monto'];
        }
        if (!$planesSeleccionados && !empty($liquidacion[0]['id_plan'])) {
            $planesSeleccionados[] = $liquidacion[0]['id_plan'];
            $montosPlanes[$liquidacion[0]['id_plan']] = $liquidacion[0]['monto'];
        }

        $context = [
            'liquidacion' => $liquidacion,
            'planes' => $planes,
            'planesSeleccionados' => $planesSeleccionados,
            'montosPlanes' => $montosPlanes,
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

        $planesSeleccionados = $this->obtenerPlanesPost();
        if ($planesSeleccionados === null) {
            return redirect()->back()->withInput()->with('error', 'La selección de planes no es válida.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();
        $montoTotal = $planesSeleccionados
            ? array_sum(array_column($planesSeleccionados, 'monto'))
            : $this->request->getPost('monto');
        $actualizado = $this->liquidaciones->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'monto' => $montoTotal,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'id_plan' => $planesSeleccionados[0]['id'] ?? null,
            'nombre_plan' => $this->nombrePlanes($planesSeleccionados)
        ]);
        $db->table('liquidaciones_planes')->where('id_liquidacion', $id)->delete();
        if ($planesSeleccionados) {
            $db->table('liquidaciones_planes')->insertBatch(
                $this->relacionesPlanes((int) $id, $planesSeleccionados)
            );
        }

        if (!$actualizado || !$db->transStatus()) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudieron actualizar la liquidación y sus planes.');
        }

        $db->transCommit();
        return redirect()->to(base_url() . 'public/liquidaciones/');
    }

    private function obtenerPlanesPost(): ?array
    {
        $ids = $this->request->getPost('planes') ?? [];
        $montos = $this->request->getPost('montos_plan') ?? [];
        if (!is_array($ids)) {
            return null;
        }
        if (!is_array($montos)) {
            return null;
        }

        $idsNormalizados = [];
        foreach ($ids as $id) {
            if (!is_scalar($id) || !ctype_digit((string) $id) || (int) $id < 1) {
                return null;
            }
            $idsNormalizados[] = (int) $id;
        }
        $idsNormalizados = array_values(array_unique($idsNormalizados));

        if (!$idsNormalizados) {
            return [];
        }

        $planes = (new PlanesModel())
            ->where('activo', 1)
            ->whereIn('id', $idsNormalizados)
            ->findAll();

        if (count($planes) !== count($idsNormalizados)) {
            return null;
        }

        foreach ($planes as &$plan) {
            $id = (string) $plan['id'];
            if (!isset($montos[$id]) || !is_scalar($montos[$id]) || !is_numeric($montos[$id]) || (float) $montos[$id] < 0) {
                return null;
            }
            $plan['monto'] = round((float) $montos[$id], 2);
        }
        unset($plan);

        return $planes;
    }

    private function nombrePlanes(array $planes): string
    {
        return $planes ? implode(' | ', array_column($planes, 'nombre')) : 'Sin plan';
    }

    private function relacionesPlanes(int $idLiquidacion, array $planes): array
    {
        return array_map(
            static fn(array $plan): array => [
                'id_liquidacion' => $idLiquidacion,
                'id_plan' => $plan['id'],
                'monto' => $plan['monto']
            ],
            $planes
        );
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
