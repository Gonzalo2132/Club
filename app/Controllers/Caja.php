<?php

namespace App\Controllers;

use App\Models\CajasModel;

class Caja extends BaseController
{
    protected $cajas;
    protected $sesion;

    public function __construct()
    {
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

        foreach ($cajas as $index => $caja) {
            $cajas[$index]['saldo'] = $this->obtenerSaldoCaja((int) $caja['id']);
        }

        $context = [
            'cajas' => $cajas,
            'movimientos' => $this->obtenerMovimientos(),
            'titulo' => 'Cajas',
            'pagname' => 'Gestión/Cajas'
        ];

        echo view('panel/header', $context);
        echo view('caja/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

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

        $this->cajas->update(
            $id,
            [
                'denominacion' => $this->request->getPost('denominacion'),
                'descripcion' => $this->request->getPost('descripcion'),
                'codigointerno' => $this->request->getPost('codigointerno')
            ]
        );
        return redirect()->to(base_url() . 'public/cajas/');
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

        $origenId = $origenId === '' ? null : (int) $origenId;
        $destinoId = $destinoId === '' ? null : (int) $destinoId;

        if ($origenId === null || $destinoId === null) {
            return redirect()->to(base_url() . 'public/cajas')
                ->with('error', 'Seleccioná una caja de origen y una caja de destino.');
        }

        if ($monto <= 0) {
            return redirect()->to(base_url() . 'public/cajas')
                ->with('error', 'El monto debe ser mayor a 0.');
        }

        if ($origenId === $destinoId) {
            return redirect()->to(base_url() . 'public/cajas')
                ->with('error', 'La caja de origen y destino no pueden ser la misma.');
        }

        $saldoOrigen = $this->obtenerSaldoCaja($origenId);

        if ($monto > $saldoOrigen) {
            return redirect()->to(base_url() . 'public/cajas')
                ->with('error', 'No hay saldo suficiente en la caja de origen.');
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
            if (in_array('caja_destino_id', $columnNames)) {
                $data['caja_destino_id'] = $destinoId;
            }
            if (in_array('caja_id', $columnNames)) {
                $data['caja_id'] = $origenId;
            }

            $db->table($tablaMovimiento)->insert($data);
        }

        return redirect()->to(base_url() . 'public/cajas')->with('success', 'Movimiento registrado correctamente.');
    }

    public function rendicionCuentas()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $db = \Config\Database::connect();

        $caja_zonaId = $this->request->getPost('caja_origen_id');
        $caja_CentralId = 1;
        $montoARendir = (float) $this->request->getPost('monto');

        if (empty($caja_zonaId) || $montoARendir <= 0) {
            return redirect()->back()->with('error', 'Datos inválidos para la rendición.');
        }

        $saldoActual = $this->obtenerSaldoCaja((int) $caja_zonaId);
        if ($montoARendir > $saldoActual) {
            return redirect()->back()->with('error', 'La caja no tiene saldo suficiente para rendir ese monto.');
        }

        $tablaMovimiento = $this->obtenerTablaMovimientos($db);

        if (!$tablaMovimiento) {
            return redirect()->back()->with('error', 'Error: No se encontró la tabla de movimientos.');
        }

        $db->transStart();

        $origenNombre = $this->obtenerNombreCaja($caja_zonaId);
        $destinoNombre = 'Caja Central';
        $mensaje = sprintf('%s -> %s | Rendición de cuentas', $origenNombre, $destinoNombre);

        $data = [
            'tipo_movimiento' => 'transferencia',
            'monto' => $montoARendir,
            'concepto' => $mensaje,
            'fecha' => date('Y-m-d H:i:s')
        ];

        $columnNames = $db->getFieldNames($tablaMovimiento);

        if (in_array('caja_origen_id', $columnNames)) {
            $data['caja_origen_id'] = $caja_zonaId;
        }
        if (in_array('caja_destino_id', $columnNames)) {
            $data['caja_destino_id'] = $caja_CentralId;
        }
        if (in_array('caja_id', $columnNames)) {
            $data['caja_id'] = $caja_zonaId;
        }

        $db->table($tablaMovimiento)->insert($data);

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Error en la base de datos al procesar la rendición.');
        }

        return redirect()->to(base_url() . 'public/cajas')->with('success', 'Rendición a Caja Central completada con éxito.');
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

    private function obtenerSaldoCaja(int $cajaId): float
    {
        $db = \Config\Database::connect();
        $saldo = 0.0;
        $tablaMovimiento = $this->obtenerTablaMovimientos($db);

        if (!$tablaMovimiento) {
            $pagos = $db->table('pagos')
                ->selectSum('monto', 'total')
                ->where('id_caja', $cajaId)
                ->get()
                ->getRowArray();

            return round((float) ($pagos['total'] ?? 0), 2);
        }

        $movimientos = $db->table($tablaMovimiento)
            ->get()
            ->getResultArray();

        foreach ($movimientos as $movimiento) {
            $monto = (float) ($movimiento['monto'] ?? 0);
            $concepto = trim((string) ($movimiento['concepto'] ?? ''));
            $origenId = $movimiento['caja_origen_id'] ?? null;
            $cajaActual = $this->normalizarNombreCaja($this->obtenerNombreCaja($cajaId));
            $procesado = false;

            if ((int) $origenId === $cajaId && !preg_match('/\s*->\s*/', $concepto)) {
                $saldo -= $monto;
                $procesado = true;
            }

            if (preg_match('/^(.*?)\s*->\s*(.*?)(?:\s*\|\s*.*)?$/', $concepto, $matches)) {
                $origen = trim($matches[1]);
                $destino = trim($matches[2]);

                if ($this->normalizarNombreCaja($origen) === $cajaActual && !$procesado) {
                    $saldo -= $monto;
                    $procesado = true;
                }

                if ($this->normalizarNombreCaja($destino) === $cajaActual) {
                    $saldo += $monto;
                    $procesado = true;
                }
            }
        }

        return max(0, round($saldo, 2));
    }

    private function normalizarNombreCaja(string $nombre): string
    {
        $nombre = trim($nombre);
        $nombre = strtolower($nombre);
        $nombre = str_replace(['á', 'à', 'ä', 'â'], 'a', $nombre);
        $nombre = str_replace(['é', 'è', 'ë', 'ê'], 'e', $nombre);
        $nombre = str_replace(['í', 'ì', 'ï', 'î'], 'i', $nombre);
        $nombre = str_replace(['ó', 'ò', 'ö', 'ô'], 'o', $nombre);
        $nombre = str_replace(['ú', 'ù', 'ü', 'û'], 'u', $nombre);
        $nombre = str_replace(['ñ'], 'n', $nombre);
        $nombre = preg_replace('/\s+/', ' ', $nombre);

        return trim((string) $nombre);
    }

    public function movimientos($id = 0)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $db = \Config\Database::connect();
        $tablaMovimiento = $this->obtenerTablaMovimientos($db);

        if (!$tablaMovimiento) {
            return redirect()->to(base_url() . 'public/cajas')
                ->with('error', 'No se encontró la tabla de movimientos.');
        }

        $movimientos = $db->table($tablaMovimiento)
            ->orderBy('fecha', 'DESC')
            ->get()
            ->getResultArray();

        $cajaId = (int) $id;
        $cajaNombre = $this->obtenerNombreCaja($cajaId);
        $cajaNormalizada = $this->normalizarNombreCaja($cajaNombre);
        $resultado = [];

        foreach ($movimientos as $movimiento) {
            $origenId = $movimiento['caja_origen_id'] ?? ($movimiento['caja_id'] ?? null);
            $destinoId = $movimiento['caja_destino_id'] ?? null;
            $origen = $this->obtenerNombreCaja($origenId);
            $destino = $this->obtenerNombreCaja($destinoId);
            $concepto = trim((string) ($movimiento['concepto'] ?? ''));

            if (preg_match('/^(.*?)\s*->\s*(.*?)(?:\s*\|\s*.*)?$/', $concepto, $matches)) {
                $origen = trim($matches[1]);
                $destino = trim($matches[2]);
            }

            // En 0 se muestran todos; si se solicita una caja, se contemplan
            // origen, destino por id y destinos guardados dentro del concepto.
            $esDeLaCaja = $cajaId === 0
                || (int) $origenId === $cajaId
                || (int) $destinoId === $cajaId
                || $this->normalizarNombreCaja($origen) === $cajaNormalizada
                || $this->normalizarNombreCaja($destino) === $cajaNormalizada;

            if ($esDeLaCaja) {
                $movimiento['origen'] = $origen;
                $movimiento['destino'] = $destino;
                $movimiento['tipo'] = $movimiento['tipo_movimiento'] ?? 'transferencia';
                $movimiento['concepto'] = $concepto !== '' ? $concepto : 'Movimiento registrado';
                $resultado[] = $movimiento;
            }
        }

        $context = [
            'movimientos' => $resultado,
            'cajaNombre' => $cajaId === 0 ? 'Todas las cajas' : $cajaNombre,
            'titulo' => 'Movimientos',
            'pagname' => 'Gestión/Movimientos'
        ];

        echo view('panel/header', $context);
        echo view('caja/movimientos', $context);
        echo view('panel/footer');
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
            ->get()
            ->getResultArray();

        foreach ($result as &$movimiento) {
            $origenId = $movimiento['caja_origen_id'] ?? ($movimiento['caja_id'] ?? null);
            $destinoId = $movimiento['caja_destino_id'] ?? null;
            $origen = $this->obtenerNombreCaja($origenId);
            $destino = $this->obtenerNombreCaja($destinoId);
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
        if ($id === null || $id === '') {
            return 'Caja Desconocida';
        }

        $caja = $this->cajas->where('id', $id)->first();

        if ($caja) {
            return $caja['denominacion'] ?: 'Caja ' . $id;
        }

        return 'Caja ' . $id;
    }
}
