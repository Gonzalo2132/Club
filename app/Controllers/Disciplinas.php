<?php

namespace App\Controllers;

use App\Models\DisciplinasModel;

class Disciplinas extends BaseController
{
    private DisciplinasModel $disciplinas;

    public function __construct()
    {
        $this->disciplinas = new DisciplinasModel();
    }

    public function index()
    {
        $disciplinas = $this->disciplinas->where('activo', 1)->findAll();
        $context = [
            'disciplinas' => $disciplinas,
            'titulo' => 'Gestión de disciplinas',
            'pagname' => 'Gestión/Disciplinas',
        ];

        echo view('panel/header', $context);
        echo view('disciplinas/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        $context = [
            'disciplina' => ['nombre' => '', 'descripcion' => ''],
            'accion' => 'guardar',
            'titulo' => 'Nueva disciplina',
            'pagname' => 'Gestión/Nueva disciplina',
        ];

        echo view('panel/header', $context);
        echo view('disciplinas/formulario', $context);
        echo view('panel/footer');
    }

    public function guardar()
    {
        $nombre = trim((string) $this->request->getPost('nombre'));
        if (!$this->nombreValido($nombre)) {
            return redirect()->back()->withInput()
                ->with('error', 'El nombre es obligatorio y debe tener hasta 80 caracteres.');
        }
        if ($this->disciplinas->where('nombre', $nombre)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe una disciplina con ese nombre.');
        }

        $this->disciplinas->insert([
            'nombre' => $nombre,
            'descripcion' => trim((string) $this->request->getPost('descripcion')) ?: null,
            'activo' => 1,
        ]);

        return redirect()->to(base_url('public/disciplinas'))
            ->with('mensaje', 'Disciplina creada correctamente.');
    }

    public function editar($id)
    {
        $disciplina = $this->disciplinas->find($id);
        if ($disciplina === null) {
            return redirect()->to(base_url('public/disciplinas'))
                ->with('error', 'La disciplina no existe.');
        }

        $context = [
            'disciplina' => $disciplina,
            'accion' => 'actualizar/' . $id,
            'titulo' => 'Editar disciplina',
            'pagname' => 'Gestión/Editar disciplina',
        ];

        echo view('panel/header', $context);
        echo view('disciplinas/formulario', $context);
        echo view('panel/footer');
    }

    public function jugadores($id)
    {
        $disciplina = $this->disciplinas->find($id);
        if ($disciplina === null) {
            return redirect()->to(base_url('public/disciplinas'))
                ->with('error', 'La disciplina no existe.');
        }

        $jugadores = db_connect()->table('socios_disciplinas')
            ->select(
                'socios.id, socios.nombre, socios.apellido, socios.dni, socios.fecha_nac, ' .
                'socios.telefono, tutores.nombre AS tutor_nombre, tutores.apellido AS tutor_apellido, ' .
                'tutores.dni AS tutor_dni, tutores.telefono AS tutor_telefono, tutores.email AS tutor_email, ' .
                'tutores.parentesco AS tutor_parentesco'
            )
            ->join('socios', 'socios.id = socios_disciplinas.id_socio')
            ->join('socios_tutores', 'socios_tutores.id_socio = socios.id AND socios_tutores.fecha_borrado IS NULL', 'left')
            ->join('tutores', 'tutores.id = socios_tutores.id_tutor', 'left')
            ->where('socios_disciplinas.id_disciplina', $id)
            ->where('socios_disciplinas.fecha_borrado', null)
            ->where('socios.activo', 1)
            ->orderBy('socios.apellido')
            ->orderBy('socios.nombre')
            ->get()
            ->getResultArray();
        $jugadoresPorId = [];
        foreach ($jugadores as $jugador) {
            $jugadorId = (int) $jugador['id'];
            if (!isset($jugadoresPorId[$jugadorId])) {
                $jugadoresPorId[$jugadorId] = [
                    'id' => $jugadorId,
                    'nombre' => $jugador['nombre'],
                    'apellido' => $jugador['apellido'],
                    'dni' => $jugador['dni'],
                    'fecha_nac' => $jugador['fecha_nac'],
                    'telefono' => $jugador['telefono'],
                    'tutores' => [],
                ];
            }
            if ($jugador['tutor_dni'] !== null) {
                $jugadoresPorId[$jugadorId]['tutores'][] = [
                    'nombre' => trim($jugador['tutor_nombre'] . ' ' . $jugador['tutor_apellido']),
                    'dni' => $jugador['tutor_dni'],
                    'telefono' => $jugador['tutor_telefono'],
                    'email' => $jugador['tutor_email'],
                    'parentesco' => $jugador['tutor_parentesco'],
                ];
            }
        }

        $context = [
            'disciplina' => $disciplina,
            'jugadores' => array_values($jugadoresPorId),
            'titulo' => 'Jugadores inscriptos',
            'pagname' => 'Gestión/Disciplinas/Jugadores',
        ];

        echo view('panel/header', $context);
        echo view('disciplinas/jugadores', $context);
        echo view('panel/footer');
    }

    public function actualizar($id)
    {
        $disciplina = $this->disciplinas->find($id);
        if ($disciplina === null) {
            return redirect()->to(base_url('public/disciplinas'))
                ->with('error', 'La disciplina no existe.');
        }

        $nombre = trim((string) $this->request->getPost('nombre'));
        if (!$this->nombreValido($nombre)) {
            return redirect()->back()->withInput()
                ->with('error', 'El nombre es obligatorio y debe tener hasta 80 caracteres.');
        }
        $duplicada = $this->disciplinas->where('nombre', $nombre)
            ->where('id !=', $id)
            ->first();
        if ($duplicada) {
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe una disciplina con ese nombre.');
        }

        $this->disciplinas->update($id, [
            'nombre' => $nombre,
            'descripcion' => trim((string) $this->request->getPost('descripcion')) ?: null,
        ]);

        return redirect()->to(base_url('public/disciplinas'))
            ->with('mensaje', 'Disciplina actualizada correctamente.');
    }

    public function borrar($id)
    {
        $disciplina = $this->disciplinas->find($id);
        if ($disciplina === null) {
            return redirect()->to(base_url('public/disciplinas'))
                ->with('error', 'La disciplina no existe.');
        }

        $inscripciones = db_connect()->table('socios_disciplinas')
            ->where('id_disciplina', $id)
            ->where('fecha_borrado', null)
            ->countAllResults();
        if ($inscripciones > 0) {
            return redirect()->to(base_url('public/disciplinas'))
                ->with('error', 'No se puede borrar una disciplina con socios inscriptos.');
        }

        $this->disciplinas->delete($id);
        return redirect()->to(base_url('public/disciplinas'))
            ->with('mensaje', 'Disciplina borrada correctamente.');
    }

    private function nombreValido(string $nombre): bool
    {
        return $nombre !== '' && mb_strlen($nombre) <= 80;
    }
}
