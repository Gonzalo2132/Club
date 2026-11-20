<?php

namespace App\Controllers;

use App\Models\SociosModel;
use App\Models\LiquidacionesModel;
use App\Models\PagosModel;
use App\Models\CajasModel;
use App\Models\SociosPlanesModel;
use App\Models\RecaudadoresModel;

class Pagos extends BaseController
{
    protected $socios;
    protected $liquidaciones;
    protected $pagos;
    protected $sesion;
    protected $cajas;
    protected $sociosPlanes;
    protected $recaudadores;

    public function __construct()
    {
        $this->socios = new SociosModel();
        $this->liquidaciones = new LiquidacionesModel();
        $this->pagos = new PagosModel();
        $this->cajas = new CajasModel();
        $this->sociosPlanes = new SociosPlanesModel();
        $this->recaudadores = new RecaudadoresModel();
        $this->sesion = session();
    }

    public function nuevo($idSocio, $idLiquidacion)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $socio = $this->socios->find($idSocio);
        $liquidacion = $this->liquidaciones->find($idLiquidacion);
        if (!$socio || !$liquidacion || !$this->socioPuedePagarLiquidacion($idSocio, $liquidacion)) {
            return redirect()
                ->to(base_url('public/liquidaciones/listado_socio/' . $idSocio))
                ->with('error', 'El socio no tiene asignado el plan de esta liquidación.');
        }

        // Traer todas las cajas activas
        $cajas = $this->cajas->where('activo', 1)->findAll();
        $recaudadores = $this->recaudadoresActivosDeZona($socio['id_zona'] ?? null);
        $recaudadorAsignado = (int) ($socio['id_recaudador'] ?? 0);
        $recaudadorSeleccionado = in_array($recaudadorAsignado, array_map(
            static fn(array $recaudador): int => (int) $recaudador['id'],
            $recaudadores
        ), true) ? $recaudadorAsignado : '';
        $recaudadorFormulario = old('id_recaudador', $recaudadorSeleccionado);
        $cajaSeleccionada = '';
        foreach ($recaudadores as $recaudador) {
            if ((string) $recaudador['id'] === (string) $recaudadorFormulario) {
                $cajaSeleccionada = $recaudador['id_caja'];
                break;
            }
        }
        if ($cajaSeleccionada === null || $cajaSeleccionada === '') {
            $cajaAnterior = old('id_caja');
            $cajaSeleccionada = in_array((string) $cajaAnterior, array_map(
                static fn(array $caja): string => (string) $caja['id'],
                $cajas
            ), true) ? $cajaAnterior : ($cajas[0]['id'] ?? '');
        }
        $cajaSeleccionadaNombre = '';
        foreach ($cajas as $caja) {
            if ((string) $caja['id'] === (string) $cajaSeleccionada) {
                $cajaSeleccionadaNombre = $caja['denominacion'];
                break;
            }
        }

        $pagos = $this->pagos
            ->where('id_socio', $idSocio)
            ->where('id_liquidacion', $idLiquidacion)
            ->findAll();

        $context = [
            'titulo' => "Registrar pago",
            'socio' => $socio,
            'liquidacion' => $liquidacion,
            'pagos' => $pagos,
            'cajas' => $cajas,
            'recaudadores' => $recaudadores,
            'recaudadorSeleccionado' => $recaudadorSeleccionado,
            'cajaSeleccionada' => $cajaSeleccionada,
            'cajaSeleccionadaNombre' => $cajaSeleccionadaNombre,
        ];

        echo view('panel/header', $context);
        echo view('pagos/nuevo', $context);
        echo view('panel/footer');
    }

    // Guardar un nuevo pago
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $idSocio = $this->request->getPost('id_socio');
        $idLiquidacion = $this->request->getPost('id_liquidacion');
        $idCaja = $this->request->getPost('id_caja');
        $formaPago = $this->request->getPost('forma_pago');
        $monto = (float) $this->request->getPost('monto');
        $fechaPago = $this->request->getPost('fecha_pago') ?: date('Y-m-d');
        $idRecaudadorPost = $this->request->getPost('id_recaudador');
        $liquidacion = $this->liquidaciones->find($idLiquidacion);
        $socio = $this->socios->find($idSocio);

        if (!$socio || !$liquidacion || !$this->socioPuedePagarLiquidacion($idSocio, $liquidacion)) {
            return redirect()
                ->to(base_url('public/liquidaciones/listado_socio/' . $idSocio))
                ->with('error', 'El socio no tiene asignado el plan de esta liquidación.');
        }

        $recaudadoresDisponibles = $this->recaudadoresActivosDeZona($socio['id_zona'] ?? null);
        $idRecaudador = null;
        $recaudadorValido = null;
        if ($idRecaudadorPost !== null && $idRecaudadorPost !== '') {
            $idRecaudador = filter_var($idRecaudadorPost, FILTER_VALIDATE_INT);
            $recaudadorValido = $idRecaudador !== false
                ? $this->recaudadores
                    ->where('id', $idRecaudador)
                    ->where('id_zona', $socio['id_zona'] ?? null)
                    ->where('activo', 1)
                    ->first()
                : null;
            if (!$recaudadorValido) {
                return redirect()->back()->withInput()->with('error', 'El recaudador seleccionado no está activo en la zona del socio.');
            }
            $idRecaudador = (int) $idRecaudador;
            $idCaja = (int) $recaudadorValido['id_caja'];
            $cajaRecaudadorActiva = $this->cajas
                ->where('id', $idCaja)
                ->where('activo', 1)
                ->first();
            if (!$cajaRecaudadorActiva) {
                return redirect()->back()->withInput()->with('error', 'La caja asignada al recaudador no está activa.');
            }
        } elseif (!is_scalar($idRecaudadorPost) && $idRecaudadorPost !== null) {
            return redirect()->back()->withInput()->with('error', 'La selección del recaudador no es válida.');
        } elseif ($recaudadoresDisponibles !== []) {
            return redirect()->back()->withInput()->with('error', 'Selecciona quién recibió el pago.');
        } else {
            $idCaja = filter_var($idCaja, FILTER_VALIDATE_INT);
            if ($idCaja === false || $idCaja < 1 || !$this->cajas->where('id', $idCaja)->where('activo', 1)->first()) {
                return redirect()->back()->withInput()->with('error', 'Selecciona una caja activa para registrar el pago.');
            }
        }

        $pago = [
            'id_socio' => $idSocio,
            'id_liquidacion' => $idLiquidacion,
            'id_caja' => $idCaja,
            'id_recaudador' => $idRecaudador,
            'forma_pago' => $formaPago,
            'monto' => $monto,
            'fecha_pago' => $fechaPago,
            'fecha_alta' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $db->transBegin();

        if (!$this->pagos->save($pago) || !$db->transStatus()) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudo registrar el pago.');
        }

        foreach (['movimientos_caja', 'movimientos_cajas'] as $tabla) {
            if ($db->tableExists($tabla)) {
                $columnNames = $db->getFieldNames($tabla);

                $socio = $this->socios->find($idSocio);
                $liquidacion = $this->liquidaciones->find($idLiquidacion);

                $socioNombre = trim(($socio['apellido'] ?? '') . ' ' . ($socio['nombre'] ?? ''));
                $liqNombre = $liquidacion['nombre'] ?? ('Liquidacion ' . $idLiquidacion);

                $detalleConcepto = sprintf('Pago: %s - %s | Socio: %s | Forma: %s', $liqNombre, number_format($monto, 2, '.', ''), $socioNombre, $formaPago);

                $caja = $this->cajas->find((int) $idCaja);
                $cajaNombre = $caja['denominacion'] ?? ('Caja ' . $idCaja);

                $data = [
                    'tipo_movimiento' => 'ingreso',
                    'monto' => $monto,
                    'concepto' => sprintf('%s -> %s | %s', $socioNombre ?: 'Socio', $cajaNombre, $detalleConcepto),
                    'fecha' => date('Y-m-d H:i:s')
                ];

                if (in_array('caja_destino_id', $columnNames, true)) {
                    $data['caja_destino_id'] = (int) $idCaja;
                }

                if (in_array('caja_id', $columnNames, true) && !isset($data['caja_id'])) {
                    $data['caja_id'] = (int) $idCaja;
                }

                if (in_array('caja_origen_id', $columnNames, true)
                    && !in_array('caja_destino_id', $columnNames, true)
                    && !in_array('caja_id', $columnNames, true)) {
                    $data['caja_origen_id'] = 0;
                }

                if (!$db->table($tabla)->insert($data) || !$db->transStatus()) {
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('error', 'No se pudo registrar el movimiento de caja.');
                }

                break;
            }
        }

        if (!$db->transCommit()) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudo confirmar el pago.');
        }

        return redirect()->to(base_url('public/liquidaciones/listado_socio/' . $idSocio));
    }

    private function socioPuedePagarLiquidacion($idSocio, array $liquidacion): bool
    {
        if (empty($liquidacion['id_plan'])) {
            return true;
        }

        return $this->sociosPlanes
            ->where('id_socio', $idSocio)
            ->where('id_plan', $liquidacion['id_plan'])
            ->first() !== null;
    }

    private function recaudadoresActivosDeZona($idZona): array
    {
        if (empty($idZona)) {
            return [];
        }

        return $this->recaudadores
            ->select('recaudadores.id, recaudadores.nombre, recaudadores.apellido, recaudadores.id_zona, recaudadores.id_caja, cajas.denominacion AS caja_denominacion')
            ->join('cajas', 'cajas.id = recaudadores.id_caja', 'left')
            ->where('recaudadores.id_zona', $idZona)
            ->where('recaudadores.activo', 1)
            ->orderBy('recaudadores.apellido')
            ->orderBy('recaudadores.nombre')
            ->findAll();
    }
}
