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

        foreach ($cajas as $index => $caja) {
            $cajas[$index]['saldo'] = $this->obtenerSaldoCaja((int) $caja['id']);
        }

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

        $origenId = $origenId === '' ? null : (int) $origenId;
        $destinoId = $destinoId === '' ? null : (int) $destinoId;

        if ($monto <= 0 || $origenId === null || $destinoId === null || (int) $origenId === (int) $destinoId) {
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
        return [
            'id' => 0,
            'denominacion' => 'Caja Central',
            'descripcion' => 'Caja central del club',
            'saldo_actual' => $this->obtenerSaldoCentral()
        ];
    }

    private function obtenerSaldoCaja(int $cajaId): float
    {
        $db = \Config\Database::connect();
        $saldo = 0.0;

        $pagos = $db->table('pagos')
            ->selectSum('monto', 'total')
            ->where('id_caja', $cajaId)
            ->get()
            ->getRowArray();

        $saldo += (float) ($pagos['total'] ?? 0);

        $movimientos = $db->table('movimientos_caja')
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

        return round($saldo, 2);
    }

    private function obtenerSaldoCentral(): float
    {
        $db = \Config\Database::connect();
        $saldo = 0.0;

        $movimientos = $db->table('movimientos_caja')->get()->getResultArray();

        foreach ($movimientos as $movimiento) {
            $monto = (float) ($movimiento['monto'] ?? 0);
            $concepto = trim((string) ($movimiento['concepto'] ?? ''));
            $origenId = $movimiento['caja_origen_id'] ?? null;
            $procesado = false;

            if (preg_match('/^(.*?)\s*->\s*(.*?)(?:\s*\|\s*.*)?$/', $concepto, $matches)) {
                $origen = trim($matches[1]);
                $destino = trim($matches[2]);

                if ($this->normalizarNombreCaja($destino) === 'caja central') {
                    $saldo += $monto;
                    $procesado = true;
                }

                if ($this->normalizarNombreCaja($origen) === 'caja central') {
                    $saldo -= $monto;
                    $procesado = true;
                }
            }

            if ((int) $origenId === 0 && !$procesado) {
                $saldo -= $monto;
            }
        }

        return round($saldo, 2);
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
