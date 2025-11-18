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

        $this->pagos->save([
            'id_socio' => $this->request->getPost('id_socio'),
            'id_liquidacion' => $this->request->getPost('id_liquidacion'),
            'id_caja' => $this->request->getPost('id_caja'), // 👈 directo del select
            'monto' => $this->request->getPost('monto'),
            'fecha_pago' => $this->request->getPost('fecha_pago'),
            'activo' => 1
        ]);

        return redirect()->to(base_url('public/liquidaciones/listado_socio/' . $this->request->getPost('id_socio')));
    }
}
