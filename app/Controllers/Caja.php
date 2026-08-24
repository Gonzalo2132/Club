<?php

namespace App\Controllers;

use App\Models\CajasModel; //Acá asignamos que usamos el modelo designado



class Caja extends BaseController
{
    protected $cajas; //Esto es para que la variable en cuestion este definida
    protected $sesion;

    public function __construct()
    { //La función constructora
        $this->cajas = new CajasModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $cajas = $this->cajas
            ->where('activo', $activo)
            ->orderBy('id', 'ASC')
            ->findAll();

        $context = [
            'cajas' => $cajas,
            'cajaCentral' => $this->obtenerCajaCentral(),
            'movimientos' => $this->obtenerMovimientos(),
            'titulo' => 'Cajas',
            'pagname' => 'Gestión/Cajas'
        ];

        echo view('panel/header', $context);
        echo view('caja/listado', $context);
        echo view('panel/footer');
    }
    //esta funcion "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //este context es para cambiar el titulo de la pagina que esta esperando el header
        $context = [
            'titulo' => "Nueva caja",
            'pagname' => 'Gestión/Nueva caja'
        ];

        echo view('panel/header', $context);
        echo view('caja/nuevo');
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->cajas->save(
            [
                'denominacion' => $this->request->getPost('denominacion'),
                'descripcion' => $this->request->getPost('descripcion'),
                'codigointerno' => $this->request->getPost('codigointerno')

            ]
        );
        return redirect()->to(base_url() . 'public/cajas/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->cajas->delete($id);
        return redirect()->to(base_url() . 'public/cajas/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $caja = $this->cajas->where('id', $id)->findAll();
        $context = [
            'caja' => $caja,
            'titulo' => "Edición caja",
            'pagname' => 'Gestión/Edición caja'
        ];



        echo view('panel/header', $context);
        echo view('caja/editar');
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //primer parametro es el where en este caso $id
        $this->cajas->update(
            $id,
            [
                'denominacion' => $this->request->getPost('denominacion'),
                'descripcion' => $this->request->getPost('descripcion'),
                'codigointerno' => $this->request->getPost('codigointerno')

            ]
        );
                    return redirect()->to(base_url().'public/cajas/');


        }

    public function guardarMovimiento()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $origenId = $this->request->getPost('caja_origen_id');
        $destinoId = $this->request->getPost('caja_destino_id');
        $monto = (float) $this->request->getPost('monto');
        $concepto = trim((string) $this->request->getPost('concepto'));

        if ($monto <= 0 || empty($origenId) || empty($destinoId) || $origenId === $destinoId) {
            return redirect()->to(base_url() . 'public/cajas')->with('error', 'Ingresá un origen, un destino y un monto válido.');
        }

        $db = \Config\Database::connect();
        $tablaMovimiento = $this->obtenerTablaMovimientos($db);

        if ($tablaMovimiento) {
            $origenNombre = $this->obtenerNombreCaja($origenId);
            $destinoNombre = $this->obtenerNombreCaja($destinoId);
            $detalle = $concepto !== '' ? $concepto : 'Transferencia entre cajas';
            $mensaje = sprintf('%s -> %s | %s', $origenNombre, $destinoNombre, $detalle);

            $data = [
                'tipo_movimiento' => 'transferencia',
                'monto' => $monto,
                'concepto' => $mensaje,
                'fecha' => date('Y-m-d H:i:s')
            ];

            $columnNames = $db->getFieldNames($tablaMovimiento);

            if (in_array('caja_origen_id', $columnNames)) {
                $data['caja_origen_id'] = $origenId;
            }
            if (in_array('caja_id', $columnNames)) {
                $data['caja_id'] = $origenId;
            }

            $db->table($tablaMovimiento)->insert($data);
        }

        return redirect()->to(base_url() . 'public/cajas')->with('success', 'Movimiento registrado correctamente.');
    }

    private function obtenerTablaMovimientos($db): ?string
    {
        foreach (['movimientos_caja', 'movimientos_cajas'] as $tabla) {
            if ($db->tableExists($tabla)) {
                return $tabla;
            }
        }

        return null;
    }

    private function obtenerCajaCentral(): array
    {
        $db = \Config\Database::connect();

        if ($db->tableExists('caja_central')) {
            $cajaCentral = $db->table('caja_central')->orderBy('id', 'ASC')->get()->getRowArray();
            if ($cajaCentral) {
                return [
                    'id' => $cajaCentral['id'] ?? 0,
                    'denominacion' => $cajaCentral['nombre'] ?? 'Caja Central',
                    'descripcion' => 'Caja central del club',
                    'saldo_actual' => $cajaCentral['saldo_actual'] ?? 0
                ];
            }
        }

        return [
            'id' => 0,
            'denominacion' => 'Caja Central',
            'descripcion' => 'Caja central del club',
            'saldo_actual' => 0
        ];
    }

    private function obtenerMovimientos(): array
    {
        $db = \Config\Database::connect();
        $tablaMovimiento = $this->obtenerTablaMovimientos($db);

        if (!$tablaMovimiento) {
            return [];
        }

        $result = $db->table($tablaMovimiento)
            ->orderBy('fecha', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        foreach ($result as &$movimiento) {
            $origenId = $movimiento['caja_origen_id'] ?? ($movimiento['caja_id'] ?? null);
            $origen = $this->obtenerNombreCaja($origenId);
            $destino = 'Caja Central';
            $concepto = trim((string) ($movimiento['concepto'] ?? ''));

            if (preg_match('/^(.*?)\s*->\s*(.*?)(?:\s*\|\s*.*)?$/', $concepto, $matches)) {
                $origen = trim($matches[1]);
                $destino = trim($matches[2]);
            }

            $movimiento['origen'] = $origen;
            $movimiento['destino'] = $destino;
            $movimiento['tipo'] = $movimiento['tipo_movimiento'] ?? 'transferencia';
            $movimiento['concepto'] = $concepto !== '' ? $concepto : 'Movimiento registrado';
        }

        return $result;
    }

    private function obtenerNombreCaja($id): string
    {
        if ($id === null || $id === '' || $id === 0) {
            return 'Caja Central';
        }

        $caja = $this->cajas->where('id', $id)->first();

        if ($caja) {
            return $caja['denominacion'] ?: 'Caja ' . $id;
        }

        return 'Caja ' . $id;
    }

    public function rendicionCuentas()
    {
        $db = \Config\Database::connect();

        $caja_zonaId = 2;
        $caja_CentralId = 1;
        $montoARendir = $this->request->getPost('monto');

        $db->transStart();

        $db->query("UPDATE cajas SET saldo = saldo - ? WHERE id = ?", [$montoARendir, $caja_zonaId]);
        $db->query("INSERT INTO movimientos_cajas (caja_id, tipo, monto, concepto) VALUES (?, 'egreso', ?, 'Rendición a Caja Central')", [$caja_zonaId, $montoARendir]);

        $db->query("UPDATE cajas SET saldo = saldo + ? WHERE id = ?", [$montoARendir, $caja_CentralId]);
        $db->query("INSERT INTO movimientos_cajas (caja_id, tipo, monto, concepto) VALUES (?, 'ingreso', ?, 'Ingreso por Rendición de Pepito')", [$caja_CentralId, $montoARendir]);

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Error al procesar la rendición.');
        }

        return redirect()->to('/cajas')->with('success', 'Rendición completada con éxito.');
    }
}
