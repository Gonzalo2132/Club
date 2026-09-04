<?php

namespace App\Controllers;

use App\Models\SociosModel;
use App\Models\LiquidacionesModel;
use App\Models\PagosModel;
use App\Models\CajasModel;

class Pagos extends BaseController
{
    protected $socios;
    protected $liquidaciones;
    protected $pagos;
    protected $sesion;
    protected $cajas;

    public function __construct()
    {
        $this->socios = new SociosModel();
        $this->liquidaciones = new LiquidacionesModel();
        $this->pagos = new PagosModel();
        $this->cajas = new CajasModel();
        $this->sesion = session();
    }

    public function nuevo($idSocio, $idLiquidacion)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $socio = $this->socios->find($idSocio);
        $liquidacion = $this->liquidaciones->find($idLiquidacion);

        // Traer todas las cajas activas
        $cajas = $this->cajas->where('activo', 1)->findAll();

        $pagos = $this->pagos
            ->where('id_socio', $idSocio)
            ->where('id_liquidacion', $idLiquidacion)
            ->findAll();

        $context = [
            'titulo' => "Registrar pago",
            'socio' => $socio,
            'liquidacion' => $liquidacion,
            'pagos' => $pagos,
            'cajas' => $cajas
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
        $fechaPago = $this->request->getPost('fecha_pago');

        $this->pagos->save([
            'id_socio' => $idSocio,
            'id_liquidacion' => $idLiquidacion,
            'id_caja' => $idCaja,
            'forma_pago' => $formaPago,
            'monto' => $monto,
            'fecha_pago' => $fechaPago,
            'fecha_alta' => date('Y-m-d H:i:s')
        ]);

        // Registrar movimiento en la tabla de movimientos de cajas si existe
        $db = \Config\Database::connect();
        foreach (['movimientos_caja', 'movimientos_cajas'] as $tabla) {
            if ($db->tableExists($tabla)) {
                $columnNames = $db->getFieldNames($tabla);

                $socio = $this->socios->find($idSocio);
                $liquidacion = $this->liquidaciones->find($idLiquidacion);

                $socioNombre = trim(($socio['apellido'] ?? '') . ' ' . ($socio['nombre'] ?? ''));
                $liqNombre = $liquidacion['nombre'] ?? ('Liquidacion ' . $idLiquidacion);

                $detalleConcepto = sprintf('Pago: %s - %s | Socio: %s | Forma: %s', $liqNombre, number_format($monto, 2, '.', ''), $socioNombre, $formaPago);

                $data = [
                    'tipo_movimiento' => 'ingreso',
                    'monto' => $monto,
                    'concepto' => $detalleConcepto,
                    'fecha' => date('Y-m-d H:i:s')
                ];

                // Si la tabla tiene caja_destino_id, usarla para marcar la caja receptora
                if (in_array('caja_destino_id', $columnNames)) {
                    $data['caja_destino_id'] = (int) $idCaja;
                }

                // Si la tabla tiene caja_id (otro esquema), usarla
                if (in_array('caja_id', $columnNames) && !isset($data['caja_id'])) {
                    $data['caja_id'] = (int) $idCaja;
                }

                // Si la tabla sólo tiene caja_origen_id (esquema movimientos_caja),
                // asignar la caja en caja_origen_id y construir un concepto con formato "Origen -> Destino | detalle"
                // para que al parsear el concepto la vista muestre correctamente destino = caja
                if (in_array('caja_origen_id', $columnNames) && !in_array('caja_destino_id', $columnNames) && !in_array('caja_id', $columnNames)) {
                    // Esquema antiguo: movimientos_caja tiene solo caja_origen_id
                    // Guardar caja_origen_id = 0 (Caja Central) y construir concepto "Caja Central -> CajaDestino | detalle"
                    $data['caja_origen_id'] = 0;

                    $caja = $this->cajas->find((int) $idCaja);
                    $cajaNombre = $caja['denominacion'] ?? ('Caja ' . $idCaja);

                    // construir concepto con flecha indicando origen (Caja Central) y destino la caja recibidora
                    $data['concepto'] = sprintf('%s -> %s | %s', 'Caja Central', $cajaNombre, $detalleConcepto);
                }

                $db->table($tabla)->insert($data);

                break;
            }
        }

        return redirect()->to(base_url('public/liquidaciones/listado_socio/' . $idSocio));
    }
}
