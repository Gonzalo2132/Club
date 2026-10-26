<?php

namespace App\Controllers;

use App\Models\RecaudadoresModel;
use App\Models\CajasModel;
use App\Models\ZonasModel;

class Recaudadores extends BaseController
{
    protected $recaudadores;
    protected $cajas;
    protected $zonas;
    protected $sesion;

    public function __construct()
    {
        $this->recaudadores = new RecaudadoresModel();
        $this->cajas = new CajasModel();
        $this->zonas = new ZonasModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $recaudadores = $this->recaudadores->where('activo', $activo)->findAll();
        $cajas = $this->cajas->where('activo', 1)->findAll();
        $zonas = $this->zonas->where('activo', 1)->findAll();

        $context = [
            'recaudadores' => $recaudadores,
            'cajas' => $cajas,
            'zonas' => $zonas,
            'titulo' => 'Gestión de Recaudadores',
            'pagname' => 'Gestion/recaudadores'
        ];

        echo view('panel/header', $context);
        echo view('recaudadores/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $zonas = $this->zonas->where('activo', $activo)->findAll();

        $context = [
            'titulo' => 'Nuevo Recaudador',
            'pagname' => 'Gestión/Nuevo Recaudador',
            'zona' => $zonas
        ];

        echo view('panel/header', $context);
        echo view('recaudadores/nuevo');
        echo view('panel/footer');
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $nombre = trim((string) $this->request->getPost('nombre'));
        $apellido = trim((string) $this->request->getPost('apellido'));
        $dni = trim((string) $this->request->getPost('dni'));
        $telefono = trim((string) $this->request->getPost('telefono'));
        $idZona = $this->request->getPost('id_zona');
        if ($nombre === '' || $apellido === '' || $dni === '' || $telefono === '') {
            return redirect()->back()->withInput()->with('error', 'Completa nombre, apellido, DNI y teléfono del recaudador.');
        }

        $db = db_connect();
        $db->transBegin();

        $nombreCaja = trim($nombre . ' ' . $apellido);
        $idCaja = $this->cajas->insert([
            'denominacion' => $nombreCaja,
            'descripcion' => 'Caja personal del recaudador',
            'codigointerno' => 'REC-' . bin2hex(random_bytes(8)),
        ], true);
        if ($idCaja === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la caja del recaudador.');
        }

        $idRecaudador = $this->recaudadores->insert([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'telefono' => $telefono,
            'id_caja' => $idCaja,
            'id_zona' => $idZona === '' ? null : $idZona,
        ], true);
        if ($idRecaudador === false || !$db->transStatus()) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudo guardar el recaudador y su caja.');
        }

        $db->transCommit();

        return redirect()->to(base_url() . 'public/recaudadores/');
    }

    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->recaudadores->delete($id);
        return redirect()->to(base_url() . 'public/recaudadores/');
    }

    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $status = 1;
        $recaudador = $this->recaudadores->where('id', $id)->findAll();
        $cajas = $this->cajas->where('activo', $status)->findAll();
        $zonas = $this->zonas->where('activo', $status)->findAll();

        $context = [
            'recaudador' => $recaudador,
            'titulo' => 'Edición recaudador',
            'pagname' => 'Gestión/Edición recaudador',
            'caja' => $cajas,
            'zona' => $zonas
        ];

        echo view('panel/header', $context);
        echo view('recaudadores/editar');
        echo view('panel/footer');
    }

    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $idZona = $this->request->getPost('id_zona');
        $idCaja = $this->request->getPost('id_caja');

        $this->recaudadores->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'dni' => $this->request->getPost('dni'),
            'telefono' => $this->request->getPost('telefono'),
            'id_caja' => $idCaja,
            'id_zona' => $idZona === '' ? null : $idZona
        ]);

        return redirect()->to(base_url() . 'public/recaudadores/');
    }
}
