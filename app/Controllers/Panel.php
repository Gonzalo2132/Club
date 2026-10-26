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
        $accessControl = new AccessControl();
        $userId = (int) $this->sesion->id_usuario;
        $modulosPermitidos = $accessControl->allowedModules($userId);

        $totalUsuarios = in_array('usuarios', $modulosPermitidos, true) ? $this->usuarios->countAll() : null;
        $totalSocios = in_array('socios', $modulosPermitidos, true) ? $this->socios->countAll() : null;
        $totalRecaudadores = in_array('recaudadores', $modulosPermitidos, true) ? $this->recaudadores->countAll() : null;
        $totalZonas = in_array('zonas', $modulosPermitidos, true) ? $this->zonas->countAll() : null;
        $puedeVerFinanzas = $accessControl->canPerform($userId, 'socios', 'ver')
            && $accessControl->canPerform($userId, 'liquidaciones', 'ver')
            && $accessControl->canPerform($userId, 'pagos', 'ver');

        $context = [
            'titulo' => 'Tablero',
            'modulosPermitidos' => $modulosPermitidos,
            'totalSocios' => $totalSocios,
            'totalUsuarios' => $totalUsuarios,
            'totalRecaudadores' => $totalRecaudadores,
            'totalZonas' => $totalZonas,
            'resumenFinanciero' => $puedeVerFinanzas ? $this->obtenerResumenFinanciero() : null,
        ];


        echo view('panel/header', $context);
        echo view('panel/contenido', $context);
        echo view('panel/footer');
    }

    private function obtenerResumenFinanciero(): array
    {
        $db = db_connect();
        $hoy = date('Y-m-d');
        $hasta = date('Y-m-d', strtotime('+7 days'));
        $pendientesSql = "
            SELECT socios.id AS id_socio,
                socios.nombre AS socio_nombre,
                socios.apellido AS socio_apellido,
                liquidaciones.id AS id_liquidacion,
                COALESCE(NULLIF(liquidaciones.nombre_plan, ''), liquidaciones.nombre) AS liquidacion_nombre,
                liquidaciones.fecha_vencimiento,
                GREATEST(liquidaciones.monto - COALESCE(pagos.total_pagado, 0), 0) AS saldo_pendiente
            FROM socios
            INNER JOIN liquidaciones
                ON liquidaciones.fecha_vencimiento >= DATE(socios.fecha_alta)
                AND liquidaciones.activo = 1
                AND liquidaciones.fecha_borrado IS NULL
            LEFT JOIN (
                SELECT id_socio, id_liquidacion, SUM(monto) AS total_pagado
                FROM pagos
                WHERE fecha_borrado IS NULL
                GROUP BY id_socio, id_liquidacion
            ) AS pagos
                ON pagos.id_socio = socios.id
                AND pagos.id_liquidacion = liquidaciones.id
            WHERE socios.activo = 1
                AND socios.fecha_borrado IS NULL
                AND (
                    liquidaciones.id_plan IS NULL
                    OR EXISTS (
                        SELECT 1
                        FROM socios_planes
                        WHERE socios_planes.id_socio = socios.id
                            AND socios_planes.id_plan = liquidaciones.id_plan
                            AND socios_planes.fecha_borrado IS NULL
                    )
                )
        ";
        $pendientes = "SELECT * FROM ({$pendientesSql}) AS liquidaciones_pendientes WHERE saldo_pendiente > 0";

        $totales = $db->query(
            "SELECT COUNT(*) AS cantidad_pendiente,
                COALESCE(SUM(saldo_pendiente), 0) AS saldo_total,
                SUM(CASE WHEN fecha_vencimiento < ? THEN 1 ELSE 0 END) AS cantidad_vencida,
                COALESCE(SUM(CASE WHEN fecha_vencimiento < ? THEN saldo_pendiente ELSE 0 END), 0) AS saldo_vencido,
                SUM(CASE WHEN fecha_vencimiento BETWEEN ? AND ? THEN 1 ELSE 0 END) AS cantidad_proxima,
                COALESCE(SUM(CASE WHEN fecha_vencimiento BETWEEN ? AND ? THEN saldo_pendiente ELSE 0 END), 0) AS saldo_proximo
             FROM ({$pendientes}) AS resumen",
            [$hoy, $hoy, $hoy, $hasta, $hoy, $hasta]
        )->getRowArray();

        $liquidacionesPendientes = $db->query(
            "{$pendientes}
             ORDER BY fecha_vencimiento ASC, socio_apellido ASC, socio_nombre ASC
             LIMIT 10"
        )->getResultArray();

        return [
            'cantidadPendiente' => (int) ($totales['cantidad_pendiente'] ?? 0),
            'saldoTotal' => (float) ($totales['saldo_total'] ?? 0),
            'cantidadVencida' => (int) ($totales['cantidad_vencida'] ?? 0),
            'saldoVencido' => (float) ($totales['saldo_vencido'] ?? 0),
            'cantidadProxima' => (int) ($totales['cantidad_proxima'] ?? 0),
            'saldoProximo' => (float) ($totales['saldo_proximo'] ?? 0),
            'liquidaciones' => $liquidacionesPendientes,
        ];
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
