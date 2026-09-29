<?php

namespace App\Controllers;

use App\Models\LiquidacionesModel;
use App\Models\PlanesModel;
use App\Models\SociosModel;
use App\Models\PagosModel;
use App\Models\PlanesModel;

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
                ->select('liquidaciones_planes.id_liquidacion, GROUP_CONCAT(planes.id ORDER BY planes.id SEPARATOR ", ") AS planes_ids, GROUP_CONCAT(planes.nombre ORDER BY planes.id SEPARATOR " | ") AS planes_nombres')
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
        
        $planesModel = new PlanesModel();

        $planes = (new PlanesModel())->where('activo', 1)->findAll();
        $context = [
            'planes' => $planes,
            'planesSeleccionados' => [],
            'titulo' => "Nueva Liquidacion",
            'pagname' => 'Gestión/Nuevo Liquidacion',
            'planes' => $planesModel->findAll()
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
        $idLiquidacion = $this->liquidaciones->insert([
            'nombre' => $this->request->getPost('nombre'),
            'monto' => $this->request->getPost('monto'),
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

        $planesModel = new PlanesModel();
        $liquidacion = $this->liquidaciones->where('id', $id)->findAll();
        $planes = (new PlanesModel())->where('activo', 1)->findAll();
        $planesSeleccionados = \Config\Database::connect()
            ->table('liquidaciones_planes')
            ->select('id_plan')
            ->where('id_liquidacion', $id)
            ->get()
            ->getResultArray();
        $planesSeleccionados = array_column($planesSeleccionados, 'id_plan');
        if (!$planesSeleccionados && !empty($liquidacion[0]['id_plan'])) {
            $planesSeleccionados[] = $liquidacion[0]['id_plan'];
        }

        $context = [
            'liquidacion' => $liquidacion,
            'planes' => $planes,
            'planesSeleccionados' => $planesSeleccionados,
            'titulo' => "Edición liquidacion",
            'pagname' => 'Gestión/Edición liquidacion',
            'planes' => $planesModel->findAll()
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
        $actualizado = $this->liquidaciones->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'monto' => $this->request->getPost('monto'),
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
        if (!is_array($ids)) {
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

        return count($planes) === count($idsNormalizados) ? $planes : null;
    }

    private function nombrePlanes(array $planes): string
    {
        return $planes[0]['nombre'] ?? 'Sin plan';
    }

    private function relacionesPlanes(int $idLiquidacion, array $planes): array
    {
        return array_map(
            static fn(array $plan): array => [
                'id_liquidacion' => $idLiquidacion,
                'id_plan' => $plan['id']
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
