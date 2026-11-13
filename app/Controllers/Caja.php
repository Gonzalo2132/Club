<?php

namespace App\Controllers;

use App\Models\CajasModel;

class Caja extends BaseController
{
    private const CATEGORIAS_GASTO = [
        'servicios' => 'Servicios',
        'reparaciones' => 'Reparaciones',
        'materiales' => 'Materiales y compras',
        'limpieza' => 'Limpieza',
        'transporte' => 'Transporte',
        'otros' => 'Otros',
    ];

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
            'gastoMesActual' => $this->obtenerGastoMesActual(),
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

    public function gastos()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $cajaId = $this->request->getGet('caja');
        $cajaId = $cajaId !== null && $cajaId !== '' ? filter_var($cajaId, FILTER_VALIDATE_INT) : null;
        if ($cajaId === false || ($cajaId !== null && $cajaId < 1)) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'La caja seleccionada no es válida.');
        }

        $pagina = filter_var($this->request->getGet('pagina') ?? 1, FILTER_VALIDATE_INT);
        if ($pagina === false || $pagina < 1) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'El número de página no es válido.');
        }

        $db = \Config\Database::connect();
        $conteoQuery = $db->table('gastos_caja');
        if ($cajaId !== null) {
            $conteoQuery->where('id_caja', $cajaId);
        }
        $totalRegistros = $conteoQuery->countAllResults();
        $porPagina = 50;
        $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));
        $pagina = min($pagina, $totalPaginas);

        $query = $db->table('gastos_caja')
            ->select('gastos_caja.*, cajas.denominacion AS caja_nombre, creador.username AS creador_nombre, anulador.username AS anulador_nombre')
            ->join('cajas', 'cajas.id = gastos_caja.id_caja')
            ->join('usuarios AS creador', 'creador.id = gastos_caja.registrado_por', 'left')
            ->join('usuarios AS anulador', 'anulador.id = gastos_caja.anulado_por', 'left')
            ->orderBy('gastos_caja.fecha_gasto', 'DESC')
            ->orderBy('gastos_caja.id', 'DESC');
        if ($cajaId !== null) {
            $query->where('gastos_caja.id_caja', $cajaId);
        }
        $gastos = $query->get($porPagina, ($pagina - 1) * $porPagina)->getResultArray();

        $totalMesQuery = $db->table('gastos_caja')
            ->selectSum('monto', 'total')
            ->where('fecha_gasto >=', date('Y-m-01'))
            ->where('fecha_gasto <=', date('Y-m-t'))
            ->where('fecha_anulacion', null);
        if ($cajaId !== null) {
            $totalMesQuery->where('id_caja', $cajaId);
        }
        $totalMes = (float) ($totalMesQuery->get()->getRowArray()['total'] ?? 0);

        $context = [
            'gastos' => $gastos,
            'cajas' => $this->cajas->where('activo', 1)->orderBy('denominacion')->findAll(),
            'cajaSeleccionada' => $cajaId,
            'categorias' => self::CATEGORIAS_GASTO,
            'totalMes' => $totalMes,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'totalRegistros' => $totalRegistros,
            'titulo' => 'Gastos de caja',
            'pagname' => 'Gestión/Gastos de caja',
        ];

        echo view('panel/header', $context);
        echo view('caja/gastos', $context);
        echo view('panel/footer');
    }

    public function nuevoGasto()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $cajas = $this->cajas->where('activo', 1)->orderBy('denominacion')->findAll();
        $cajaId = $this->request->getGet('caja');

        $context = [
            'cajas' => $cajas,
            'cajaSeleccionada' => $cajaId,
            'categorias' => self::CATEGORIAS_GASTO,
            'titulo' => 'Registrar gasto',
            'pagname' => 'Gestión/Registrar gasto',
        ];

        echo view('panel/header', $context);
        echo view('caja/nuevo_gasto', $context);
        echo view('panel/footer');
    }

    public function guardarGasto()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $idCaja = filter_var($this->request->getPost('id_caja'), FILTER_VALIDATE_INT);
        $categoria = $this->request->getPost('categoria');
        $conceptoPost = $this->request->getPost('concepto');
        $destinatarioPost = $this->request->getPost('destinatario');
        if (!is_scalar($conceptoPost)) {
            return redirect()->back()->withInput()->with('error', 'El detalle del gasto no es válido.');
        }
        $concepto = trim((string) $conceptoPost);
        $destinatario = is_scalar($destinatarioPost) ? trim((string) $destinatarioPost) : '';
        $montoPost = $this->request->getPost('monto');
        $fechaGasto = $this->request->getPost('fecha_gasto');

        if ($idCaja === false || $idCaja < 1 || !is_string($categoria) || !array_key_exists($categoria, self::CATEGORIAS_GASTO)) {
            return redirect()->back()->withInput()->with('error', 'Selecciona una caja y una categoría válidas.');
        }
        if ($concepto === '' || mb_strlen($concepto) > 255 || mb_strlen($destinatario) > 120) {
            return redirect()->back()->withInput()->with('error', 'Completa el detalle del gasto y revisa el largo de los campos.');
        }
        if (!is_scalar($montoPost) || !preg_match('/^\d+(?:\.\d{1,2})?$/', (string) $montoPost) || (float) $montoPost <= 0) {
            return redirect()->back()->withInput()->with('error', 'El monto debe ser mayor a cero y tener hasta dos decimales.');
        }
        $fecha = is_scalar($fechaGasto) ? \DateTimeImmutable::createFromFormat('!Y-m-d', (string) $fechaGasto) : false;
        if (!$fecha || $fecha->format('Y-m-d') !== $fechaGasto || $fecha > new \DateTimeImmutable('today')) {
            return redirect()->back()->withInput()->with('error', 'Ingresa una fecha válida que no sea futura.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();
        $cajaActiva = $db->query('SELECT id FROM cajas WHERE id = ? AND activo = 1 FOR UPDATE', [$idCaja])->getRowArray();
        if (!$cajaActiva) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'La caja seleccionada no existe o está inactiva.');
        }

        $monto = round((float) $montoPost, 2);
        if ($monto > $this->obtenerSaldoCaja((int) $idCaja)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'La caja no tiene saldo suficiente para registrar ese gasto.');
        }

        $comprobante = $this->guardarComprobanteSubido();
        if (isset($comprobante['error'])) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $comprobante['error']);
        }

        $datosGasto = [
            'id_caja' => (int) $idCaja,
            'categoria' => $categoria,
            'concepto' => $concepto,
            'destinatario' => $destinatario !== '' ? $destinatario : null,
            'monto' => $monto,
            'fecha_gasto' => $fecha->format('Y-m-d'),
            'comprobante' => $comprobante['nombre'] ?? null,
            'registrado_por' => (int) $this->sesion->id_usuario,
            'fecha_alta' => date('Y-m-d H:i:s'),
        ];

        if (!$db->table('gastos_caja')->insert($datosGasto) || !$db->transStatus()) {
            $db->transRollback();
            if (isset($comprobante['ruta']) && is_file($comprobante['ruta'])) {
                unlink($comprobante['ruta']);
            }
            return redirect()->back()->withInput()->with('error', 'No se pudo guardar el gasto.');
        }
        if (!$db->transCommit()) {
            $db->transRollback();
            if (isset($comprobante['ruta']) && is_file($comprobante['ruta'])) {
                unlink($comprobante['ruta']);
            }
            return redirect()->back()->withInput()->with('error', 'No se pudo confirmar el gasto en la base de datos.');
        }

        return redirect()->to(base_url('public/cajas/nuevo-gasto'))
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function anularGasto($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $motivoPost = $this->request->getPost('motivo_anulacion');
        $motivo = is_scalar($motivoPost) ? trim((string) $motivoPost) : '';
        if ($motivo === '' || mb_strlen($motivo) > 255) {
            return redirect()->to(base_url('public/cajas/gastos'))
                ->with('error', 'Indica un motivo de anulación de hasta 255 caracteres.');
        }

        $db = \Config\Database::connect();
        $gasto = $db->table('gastos_caja')->where('id', $id)->get()->getRowArray();
        if (!$gasto) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'El gasto no existe.');
        }
        if ($gasto['fecha_anulacion'] !== null) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'Ese gasto ya está anulado.');
        }

        $db->table('gastos_caja')
            ->where('id', $id)
            ->where('fecha_anulacion', null)
            ->update([
                'anulado_por' => (int) $this->sesion->id_usuario,
                'fecha_anulacion' => date('Y-m-d H:i:s'),
                'motivo_anulacion' => $motivo,
            ]);
        if ($db->affectedRows() !== 1) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'No se pudo anular el gasto; puede que ya haya sido anulado.');
        }

        return redirect()->to(base_url('public/cajas/gastos'))->with('success', 'Gasto anulado; quedó conservado en el historial.');
    }

    public function comprobanteGasto($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $gasto = \Config\Database::connect()
            ->table('gastos_caja')
            ->select('comprobante')
            ->where('id', $id)
            ->get()
            ->getRowArray();
        if (!$gasto || empty($gasto['comprobante'])) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'No se encontró el comprobante.');
        }

        $nombreArchivo = basename($gasto['comprobante']);
        $ruta = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'gastos' . DIRECTORY_SEPARATOR . $nombreArchivo;
        if (!is_file($ruta)) {
            return redirect()->to(base_url('public/cajas/gastos'))->with('error', 'El archivo del comprobante no está disponible.');
        }

        return $this->response->download($ruta, null)->setFileName('comprobante-gasto-' . (int) $id . '.' . pathinfo($nombreArchivo, PATHINFO_EXTENSION));
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
        $saldo = (float) ($db->table('pagos')
            ->selectSum('monto', 'total')
            ->where('id_caja', $cajaId)
            ->get()
            ->getRowArray()['total'] ?? 0);
        $saldo -= (float) ($db->table('gastos_caja')
            ->selectSum('monto', 'total')
            ->where('id_caja', $cajaId)
            ->where('fecha_anulacion', null)
            ->get()
            ->getRowArray()['total'] ?? 0);
        $tablaMovimiento = $this->obtenerTablaMovimientos($db);

        if (!$tablaMovimiento) {
            return round($saldo, 2);
        }

        $columnNames = $db->getFieldNames($tablaMovimiento);
        $movimientos = $db->table($tablaMovimiento)
            ->get()
            ->getResultArray();

        foreach ($movimientos as $movimiento) {
            $monto = (float) ($movimiento['monto'] ?? 0);
            $concepto = trim((string) ($movimiento['concepto'] ?? ''));
            $tipo = $movimiento['tipo_movimiento'] ?? ($movimiento['tipo'] ?? '');

            // Los pagos ya están incluidos desde la tabla pagos; no deben sumarse dos veces.
            if ($tipo === 'ingreso' && stripos($concepto, 'Pago:') !== false) {
                continue;
            }

            $origenId = $movimiento['caja_origen_id'] ?? ($movimiento['caja_id'] ?? null);
            $destinoId = in_array('caja_destino_id', $columnNames, true)
                ? ($movimiento['caja_destino_id'] ?? null)
                : null;

            if ($tipo === 'transferencia') {
                if ($destinoId === null && preg_match('/^(.*?)\s*->\s*(.*?)(?:\s*\|\s*.*)?$/', $concepto, $matches)) {
                    $origenId = $this->obtenerIdCajaPorNombre(trim($matches[1])) ?? $origenId;
                    $destinoId = $this->obtenerIdCajaPorNombre(trim($matches[2]));
                }
                if ((int) $origenId === $cajaId) {
                    $saldo -= $monto;
                }
                if ((int) $destinoId === $cajaId) {
                    $saldo += $monto;
                }
                continue;
            }

            if ((int) $origenId === $cajaId) {
                $saldo += $tipo === 'egreso' ? -$monto : $monto;
            }
        }

        return round($saldo, 2);
    }

    private function obtenerGastoMesActual(): float
    {
        return (float) (\Config\Database::connect()->table('gastos_caja')
            ->selectSum('monto', 'total')
            ->where('fecha_gasto >=', date('Y-m-01'))
            ->where('fecha_gasto <=', date('Y-m-t'))
            ->where('fecha_anulacion', null)
            ->get()
            ->getRowArray()['total'] ?? 0);
    }

    private function obtenerIdCajaPorNombre(string $nombre): ?int
    {
        $nombreNormalizado = $this->normalizarNombreCaja($nombre);
        foreach ($this->cajas->findAll() as $caja) {
            if ($this->normalizarNombreCaja((string) $caja['denominacion']) === $nombreNormalizado) {
                return (int) $caja['id'];
            }
        }

        return null;
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

        $movimientos = $tablaMovimiento
            ? $db->table($tablaMovimiento)
                ->orderBy('fecha', 'DESC')
                ->get()
                ->getResultArray()
            : [];

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

        $resultado = array_merge($resultado, $this->obtenerGastosParaMovimientos($cajaId));
        usort($resultado, static fn(array $left, array $right): int => strcmp((string) ($right['fecha'] ?? ''), (string) ($left['fecha'] ?? '')));
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
            return $this->obtenerGastosParaMovimientos(0);
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
        unset($movimiento);

        $result = array_merge($result, $this->obtenerGastosParaMovimientos(0));
        usort($result, static fn(array $left, array $right): int => strcmp((string) ($right['fecha'] ?? ''), (string) ($left['fecha'] ?? '')));

        return $result;
    }

    private function obtenerGastosParaMovimientos(int $cajaId): array
    {
        $query = \Config\Database::connect()->table('gastos_caja')
            ->select('gastos_caja.*, cajas.denominacion AS caja_nombre')
            ->join('cajas', 'cajas.id = gastos_caja.id_caja')
            ->orderBy('gastos_caja.fecha_gasto', 'DESC')
            ->orderBy('gastos_caja.id', 'DESC');
        if ($cajaId > 0) {
            $query->where('gastos_caja.id_caja', $cajaId);
        }

        $movimientos = [];
        foreach ($query->get()->getResultArray() as $gasto) {
            $anulado = $gasto['fecha_anulacion'] !== null;
            $concepto = sprintf(
                '%s: %s — %s',
                self::CATEGORIAS_GASTO[$gasto['categoria']] ?? 'Gasto',
                $gasto['concepto'],
                $gasto['destinatario'] ? 'Pagado a: ' . $gasto['destinatario'] : 'Sin destinatario indicado'
            );
            if ($anulado) {
                $concepto .= ' | ANULADO: ' . ($gasto['motivo_anulacion'] ?? '');
            }

            $movimientos[] = [
                'id' => 'gasto-' . $gasto['id'],
                'caja_origen_id' => (int) $gasto['id_caja'],
                'caja_destino_id' => null,
                'origen' => $gasto['caja_nombre'],
                'destino' => '-',
                'tipo_movimiento' => $anulado ? 'gasto anulado' : 'gasto',
                'tipo' => $anulado ? 'gasto anulado' : 'gasto',
                'monto' => $gasto['monto'],
                'concepto' => $concepto,
                'fecha' => $gasto['fecha_gasto'],
            ];
        }

        return $movimientos;
    }

    private function guardarComprobanteSubido(): array
    {
        $archivo = $this->request->getFile('comprobante');
        if ($archivo === null) {
            return [];
        }
        if (!($archivo instanceof \CodeIgniter\HTTP\Files\UploadedFile)) {
            return ['error' => 'El comprobante adjunto no es válido.'];
        }
        if ($archivo->getError() === UPLOAD_ERR_NO_FILE) {
            return [];
        }
        if (!$archivo->isValid()) {
            return ['error' => 'No se pudo recibir el comprobante adjunto.'];
        }
        if ($archivo->getSize() > 5 * 1024 * 1024) {
            return ['error' => 'El comprobante no puede superar los 5 MB.'];
        }

        $extensionesPermitidas = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $mime = $archivo->getMimeType();
        if (!isset($extensionesPermitidas[$mime])) {
            return ['error' => 'El comprobante debe ser PDF, JPG, PNG o WEBP.'];
        }

        $directorio = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'gastos';
        if (!is_dir($directorio) && !mkdir($directorio, 0750, true) && !is_dir($directorio)) {
            return ['error' => 'No se pudo preparar el almacenamiento privado de comprobantes.'];
        }

        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensionesPermitidas[$mime];
        $archivo->move($directorio, $nombreArchivo);

        return [
            'nombre' => $nombreArchivo,
            'ruta' => $directorio . DIRECTORY_SEPARATOR . $nombreArchivo,
        ];
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
