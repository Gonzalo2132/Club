<?php

namespace App\Controllers;

use App\Models\PlanesModel;

class Planes extends BaseController
{
    protected $planes;
    protected $sesion;

    public function __construct()
    {
        $this->planes = new PlanesModel();
        $this->sesion = session();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $planes = $this->planes->where('activo', $activo)->findAll();

        $context = [
            'planes' => $planes,
            'titulo' => "Gestión de Planes de Suscripción",
            'pagname' => "Gestión/Planes"
        ];

        echo view('panel/header', $context);
        echo view('planes/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $context = [
            'titulo' => "Nuevo Plan",
            'pagname' => 'Gestión/Nuevo Plan'
        ];

        echo view('panel/header', $context);
        echo view('planes/nuevo');
        echo view('panel/footer');
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->planes->save(
            [
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion')
            ]
        );
        return redirect()->to(base_url() . 'public/planes/');
    }

    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->planes->delete($id);
        return redirect()->to(base_url() . 'public/planes/');
    }

    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $plan = $this->planes->where('id', $id)->findAll();
        $context = [
            'plan' => $plan,
            'titulo' => "Edición plan",
            'pagname' => 'Gestión/Edición plan'
        ];

        echo view('panel/header', $context);
        echo view('planes/editar');
        echo view('panel/footer');
    }

    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $this->planes->update(
            $id,
            [
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion')
            ]
        );
        return redirect()->to(base_url() . 'public/planes/');
    }
}