<?php

namespace App\Controllers;

use App\Models\SociosModel;
use App\Models\PlanesModel;
use App\Models\DisciplinasModel;
use App\Models\SociosDisciplinasModel;
use App\Models\SociosTutoresModel;
use App\Models\TutoresModel;
use App\Models\ZonasModel;
use App\Models\SociosPlanesModel;
use App\Models\LiquidacionesModel;
use App\Models\PagosModel;

class Socios extends BaseController
{
    protected $socios;
    protected $zonas;
    protected $sociosPlanes;
    protected $sesion;
    protected $liquidaciones;
    protected $pagos;
    protected $disciplinas;
    protected $sociosDisciplinas;
    protected $tutores;
    protected $sociosTutores;

    public function __construct()
    { //La función constructora
        $this->socios = new SociosModel();
        $this->zonas = new ZonasModel();
        $this->sociosPlanes = new SociosPlanesModel();
        $this->sesion = session();
        $this->liquidaciones = new LiquidacionesModel();
        $this->pagos = new PagosModel();
        $this->disciplinas = new DisciplinasModel();
        $this->sociosDisciplinas = new SociosDisciplinasModel();
        $this->tutores = new TutoresModel();
        $this->sociosTutores = new SociosTutoresModel();
    }

    public function index($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $socios = $this->socios->select(
            'socios.*, GROUP_CONCAT(DISTINCT CONCAT(tutores.nombre, " ", tutores.apellido) SEPARATOR " | ") AS tutores_nombres, ' .
            'GROUP_CONCAT(DISTINCT planes.nombre SEPARATOR " | ") as planes_nombres, ' .
            'GROUP_CONCAT(DISTINCT disciplinas.nombre SEPARATOR " | ") as disciplinas_nombres'
        )
                               ->join('socios_tutores', 'socios_tutores.id_socio = socios.id AND socios_tutores.fecha_borrado IS NULL', 'left')
                               ->join('tutores', 'tutores.id = socios_tutores.id_tutor', 'left')
                               ->join('socios_planes', 'socios_planes.id_socio = socios.id AND socios_planes.fecha_borrado IS NULL', 'left')
                               ->join('planes', 'planes.id = socios_planes.id_plan', 'left')
                               ->join('socios_disciplinas', 'socios_disciplinas.id_socio = socios.id AND socios_disciplinas.fecha_borrado IS NULL', 'left')
                               ->join('disciplinas', 'disciplinas.id = socios_disciplinas.id_disciplina AND disciplinas.fecha_borrado IS NULL', 'left')
                               ->where('socios.activo', $activo)
                               ->groupBy('socios.id')
                               ->findAll();

        //Esto serñia una consulta,  "Select * from socios"
        //$socios = $this->socios->where('activo', $activo)->findAll();

        //Traer todas las zonas
        $zonas = $this->zonas->where('activo',1)->findAll();

        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = [
            'socios' => $socios,
            'zonas' => $zonas,
            'titulo' => "Gestión de Socios",
            'pagname' => "Gestión/socios"
        ];



        echo view('panel/header', $context);
        echo view('socios/listado', $context);
        echo view('panel/footer');
    }
    //esta función "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo($activo = 1)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $planesModel = new PlanesModel();
        $planes = $planesModel->where('activo', 1)->findAll();
        $disciplinas = $this->disciplinas->where('activo', 1)->findAll();
        //este context es para cambiar el título de la página que está esperando el header
        $zonas = $this->zonas->where('activo', $activo)->findAll();
        $context = [
            'planes' => $planes,
            'disciplinas' => $disciplinas,
            'misDisciplinas' => [],
            'tutores' => [],
            'titulo' => "Nuevo Socio",
            'pagname' => 'Gestión/Nuevo Socio',
            'zonas' => $zonas
        ];

        echo view('panel/header', $context);
        echo view('socios/nuevo', $context);
        echo view('panel/footer');
    }
    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $formulario = $this->validarFormularioDeportivo();
        if (isset($formulario['error'])) {
            return redirect()->back()->withInput()->with('error', $formulario['error']);
        }

        $db = db_connect();
        $db->transBegin();
        $idSocio = $this->socios->insert($this->datosSocio(), true);
        if ($idSocio === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'No se pudo guardar el socio.');
        }

        $tutorIds = $this->guardarTutores($formulario['tutores']);
        $this->sincronizarTutores((int) $idSocio, $tutorIds);
        $this->guardarPlanes((int) $idSocio, $formulario['planes']);
        $this->sincronizarDisciplinas((int) $idSocio, $formulario['disciplinas']);
        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('No se pudieron guardar las inscripciones del socio.');
        }
        $db->transCommit();

        return redirect()->to(base_url() . 'public/socios/');
    }
    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        //$this->socios->update($id, ['activo' => 0]);
        $this->socios->delete($id);
        return redirect()->to(base_url() . 'public/socios/');
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $planesModel = new PlanesModel();
        $planes = $planesModel->where('activo', 1)->findAll();
        $disciplinas = $this->disciplinas->where('activo', 1)->findAll();
        $status = 1;
        $socio = $this->socios->where('id', $id)->findAll();
        if ($socio === []) {
            return redirect()->to(base_url('public/socios'))->with('error', 'El socio no existe.');
        }
        $zonas = $this->zonas->where('activo', $status)->findall();     

        $planesAsignados = $this->sociosPlanes->where('id_socio', $id)->findAll();
        $misPlanesIds = array_column($planesAsignados, 'id_plan');
        $disciplinasAsignadas = $this->sociosDisciplinas->where('id_socio', $id)->findAll();
        $misDisciplinasIds = array_map('intval', array_column($disciplinasAsignadas, 'id_disciplina'));
        $tutoresAsignados = $this->obtenerTutoresSocio((int) $id);

        $context = [
            'socio' => $socio,
            'planes' => $planes,
            'disciplinas' => $disciplinas,
            'misDisciplinas' => $misDisciplinasIds,
            'tutores' => $tutoresAsignados,
            'misPlanes' => $misPlanesIds,
            'titulo' => "Edición socio",
            'pagname' => 'Gestión/Edición socio',
            'zonas' => $zonas
        ];



        echo view('panel/header', $context);
        echo view('socios/editar', $context);
        echo view('panel/footer');
    }
    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        if (!$this->socios->find($id)) {
            return redirect()->to(base_url('public/socios'))->with('error', 'El socio no existe.');
        }

        $formulario = $this->validarFormularioDeportivo();
        if (isset($formulario['error'])) {
            return redirect()->back()->withInput()->with('error', $formulario['error']);
        }

        $db = db_connect();
        $db->transBegin();
        $tutorIds = $this->guardarTutores($formulario['tutores']);
        $this->socios->update($id, $this->datosSocio());
        $this->sincronizarTutores((int) $id, $tutorIds);
        $this->guardarPlanes((int) $id, $formulario['planes']);
        $this->sincronizarDisciplinas((int) $id, $formulario['disciplinas']);
        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \RuntimeException('No se pudieron actualizar los datos del socio y sus inscripciones.');
        }
        $db->transCommit();

        return redirect()->to(base_url() . 'public/socios/');
    }

    public function informe($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $socio = $this->socios->find($id);
        if (!$socio) {
            return redirect()->to(base_url('public/socios'))
                ->with('error', 'El socio no existe.');
        }

        $zona = $this->zonas->find($socio['id_zona']);
        $tutores = $this->obtenerTutoresSocio((int) $id);
        $disciplinasAsignadas = $this->sociosDisciplinas
            ->select('disciplinas.id, disciplinas.nombre, disciplinas.descripcion')
            ->join('disciplinas', 'disciplinas.id = socios_disciplinas.id_disciplina AND disciplinas.fecha_borrado IS NULL')
            ->where('socios_disciplinas.id_socio', $id)
            ->findAll();
        $planesAsignados = $this->sociosPlanes
            ->select('planes.id, planes.nombre, planes.descripcion')
            ->join('planes', 'planes.id = socios_planes.id_plan')
            ->where('socios_planes.id_socio', $id)
            ->findAll();

        $fechaAlta = date('Y-m-d', strtotime($socio['fecha_alta']));
        $liquidaciones = $this->liquidaciones
            ->where('fecha_vencimiento >=', $fechaAlta)
            ->findAll();
        $idsPlanesSocio = array_map(
            'intval',
            array_column($planesAsignados, 'id')
        );
        $liquidaciones = array_values(array_filter(
            $liquidaciones,
            static fn(array $liquidacion): bool =>
                empty($liquidacion['id_plan'])
                || in_array((int) $liquidacion['id_plan'], $idsPlanesSocio, true)
        ));

        $pagos = $this->pagos
            ->where('id_socio', $id)
            ->findAll();
        $informeLiquidaciones = [];
        foreach ($liquidaciones as $liquidacion) {
            $pagosLiquidacion = array_filter(
                $pagos,
                static fn(array $pago): bool =>
                    (int) $pago['id_liquidacion'] === (int) $liquidacion['id']
            );
            $totalPagado = round(array_sum(array_map(
                static fn(array $pago): float => (float) $pago['monto'],
                $pagosLiquidacion
            )), 2);
            $monto = (float) $liquidacion['monto'];

            $informeLiquidaciones[] = [
                'liquidacion' => $liquidacion,
                'total_pagado' => $totalPagado,
                'saldo_pendiente' => max(0, round($monto - $totalPagado, 2)),
                'estado' => $totalPagado <= 0
                    ? 'Pendiente'
                    : ($totalPagado < $monto ? 'Pago parcial' : 'Pagado')
            ];
        }

        $context = [
            'socio' => $socio,
            'zona' => $zona,
            'planes' => $planesAsignados,
            'disciplinas' => $disciplinasAsignadas,
            'tutores' => $tutores,
            'liquidaciones' => $informeLiquidaciones,
            'titulo' => 'Vista previa del socio',
            'pagname' => 'Gestión/Informe de socio'
        ];

        echo view('panel/header', $context);
        echo view('socios/informe', $context);
        echo view('panel/footer');
    }

    private function validarFormularioDeportivo(): array
    {
        $fechaNacimiento = trim((string) $this->request->getPost('fecha_nac'));
        $fecha = \DateTimeImmutable::createFromFormat('!Y-m-d', $fechaNacimiento);
        if (!$fecha || $fecha->format('Y-m-d') !== $fechaNacimiento) {
            return ['error' => 'Ingresa una fecha de nacimiento válida.'];
        }
        if ($fecha > new \DateTimeImmutable('today')) {
            return ['error' => 'La fecha de nacimiento no puede ser futura.'];
        }

        $planesPost = $this->request->getPost('planes') ?? [];
        if (!is_array($planesPost)) {
            return ['error' => 'La selección de planes no es válida.'];
        }
        $planesIds = [];
        foreach ($planesPost as $idPlan) {
            $validatedId = filter_var($idPlan, FILTER_VALIDATE_INT);
            if ($validatedId === false || $validatedId < 1) {
                return ['error' => 'La selección de planes no es válida.'];
            }
            $planesIds[] = (int) $validatedId;
        }
        $planesIds = array_values(array_unique($planesIds));
        if ($planesIds !== []) {
            $validPlans = (new PlanesModel())
                ->select('id')
                ->where('activo', 1)
                ->whereIn('id', $planesIds)
                ->findAll();
            if (count($validPlans) !== count($planesIds)) {
                return ['error' => 'Uno o más planes seleccionados ya no están disponibles.'];
            }
        }

        $disciplinasPost = $this->request->getPost('disciplinas') ?? [];
        if (!is_array($disciplinasPost)) {
            return ['error' => 'La selección de disciplinas no es válida.'];
        }
        $ids = [];
        foreach ($disciplinasPost as $id) {
            $validatedId = filter_var($id, FILTER_VALIDATE_INT);
            if ($validatedId === false || $validatedId < 1) {
                return ['error' => 'La selección de disciplinas no es válida.'];
            }
            $ids[] = (int) $validatedId;
        }
        $ids = array_values(array_unique($ids));
        if ($ids !== []) {
            $validIds = $this->disciplinas
                ->select('id')
                ->where('activo', 1)
                ->whereIn('id', $ids)
                ->findAll();
            if (count($validIds) !== count($ids)) {
                return ['error' => 'Una o más disciplinas seleccionadas ya no están disponibles.'];
            }
        }

        $hoy = new \DateTimeImmutable('today');
        $esMenor = $fecha > $hoy->modify('-18 years');
        $tutores = [];
        if ($esMenor) {
            $tutoresPost = $this->request->getPost('tutores');
            if (!is_array($tutoresPost)) {
                return ['error' => 'Para un socio menor de 18 años, registra al menos dos tutores.'];
            }

            $dnis = [];
            foreach ($tutoresPost as $tutorPost) {
                if (!is_array($tutorPost)) {
                    return ['error' => 'Los datos de los tutores no son válidos.'];
                }
                foreach ($tutorPost as $value) {
                    if (!is_scalar($value) && $value !== null) {
                        return ['error' => 'Los datos de los tutores no son válidos.'];
                    }
                }
                $tutor = [
                    'nombre' => trim((string) ($tutorPost['nombre'] ?? '')),
                    'apellido' => trim((string) ($tutorPost['apellido'] ?? '')),
                    'dni' => trim((string) ($tutorPost['dni'] ?? '')),
                    'telefono' => trim((string) ($tutorPost['telefono'] ?? '')),
                    'email' => trim((string) ($tutorPost['email'] ?? '')),
                    'parentesco' => trim((string) ($tutorPost['parentesco'] ?? '')),
                ];
                if (implode('', $tutor) === '') {
                    continue;
                }
                if ($tutor['nombre'] === '' || $tutor['apellido'] === ''
                    || $tutor['dni'] === '' || $tutor['telefono'] === '') {
                    return ['error' => 'Completa nombre, apellido, DNI y teléfono de cada tutor.'];
                }
                if (isset($dnis[$tutor['dni']])) {
                    return ['error' => 'Cada tutor debe tener un DNI diferente.'];
                }
                if ($tutor['email'] !== '' && !filter_var($tutor['email'], FILTER_VALIDATE_EMAIL)) {
                    return ['error' => 'El email de cada tutor debe tener un formato válido.'];
                }
                $dnis[$tutor['dni']] = true;
                $tutores[] = $tutor;
            }
            if (count($tutores) < 2) {
                return ['error' => 'Para un socio menor de 18 años, registra al menos dos tutores completos.'];
            }
        }

        return ['planes' => $planesIds, 'disciplinas' => $ids, 'tutores' => $tutores];
    }

    private function datosSocio(): array
    {
        return [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'domicilio' => $this->request->getPost('domicilio'),
            'telefono' => $this->request->getPost('telefono'),
            'fecha_nac' => $this->request->getPost('fecha_nac'),
            'dni' => $this->request->getPost('dni'),
            'email' => $this->request->getPost('email'),
            'id_zona' => $this->request->getPost('id_zona'),
        ];
    }

    private function guardarTutores(array $tutores): array
    {
        $tutorIds = [];
        foreach ($tutores as $datosTutor) {
            $datosTutor['email'] = $datosTutor['email'] !== '' ? $datosTutor['email'] : null;
            $datosTutor['parentesco'] = $datosTutor['parentesco'] !== '' ? $datosTutor['parentesco'] : null;
            $existente = $this->tutores->where('dni', $datosTutor['dni'])->first();
            if ($existente) {
                $this->tutores->update($existente['id'], $datosTutor);
                $tutorIds[] = (int) $existente['id'];
                continue;
            }

            $tutorIds[] = (int) $this->tutores->insert($datosTutor, true);
        }

        return $tutorIds;
    }

    private function obtenerTutoresSocio(int $idSocio): array
    {
        return db_connect()->table('socios_tutores')
            ->select('tutores.*')
            ->join('tutores', 'tutores.id = socios_tutores.id_tutor')
            ->where('socios_tutores.id_socio', $idSocio)
            ->where('socios_tutores.fecha_borrado', null)
            ->orderBy('tutores.apellido')
            ->orderBy('tutores.nombre')
            ->get()
            ->getResultArray();
    }

    private function sincronizarTutores(int $idSocio, array $tutorIds): void
    {
        $existentes = db_connect()->table('socios_tutores')
            ->where('id_socio', $idSocio)
            ->get()
            ->getResultArray();
        $porTutor = [];
        foreach ($existentes as $relacion) {
            $porTutor[(int) $relacion['id_tutor']] = $relacion;
        }

        foreach ($tutorIds as $idTutor) {
            if (isset($porTutor[$idTutor])) {
                if ($porTutor[$idTutor]['fecha_borrado'] !== null) {
                    db_connect()->table('socios_tutores')
                        ->where('id', $porTutor[$idTutor]['id'])
                        ->update(['fecha_borrado' => null]);
                }
                unset($porTutor[$idTutor]);
                continue;
            }
            $this->sociosTutores->insert([
                'id_socio' => $idSocio,
                'id_tutor' => $idTutor,
            ]);
        }

        foreach ($porTutor as $relacion) {
            if ($relacion['fecha_borrado'] === null) {
                $this->sociosTutores->delete($relacion['id']);
            }
        }
    }

    private function guardarPlanes(int $idSocio, array $seleccionados): void
    {
        $this->sociosPlanes->where('id_socio', $idSocio)->delete();

        foreach ($seleccionados as $idPlan) {
            $this->sociosPlanes->insert([
                'id_socio' => $idSocio,
                'id_plan' => $idPlan,
            ]);
        }
    }

    private function sincronizarDisciplinas(int $idSocio, array $seleccionadas): void
    {
        $existentes = db_connect()->table('socios_disciplinas')
            ->where('id_socio', $idSocio)
            ->get()
            ->getResultArray();
        $porDisciplina = [];
        foreach ($existentes as $relacion) {
            $porDisciplina[(int) $relacion['id_disciplina']] = $relacion;
        }

        foreach ($seleccionadas as $idDisciplina) {
            if (isset($porDisciplina[$idDisciplina])) {
                if ($porDisciplina[$idDisciplina]['fecha_borrado'] !== null) {
                    db_connect()->table('socios_disciplinas')
                        ->where('id', $porDisciplina[$idDisciplina]['id'])
                        ->update(['fecha_borrado' => null]);
                }
                unset($porDisciplina[$idDisciplina]);
                continue;
            }

            $this->sociosDisciplinas->insert([
                'id_socio' => $idSocio,
                'id_disciplina' => $idDisciplina,
                'fecha_alta' => date('Y-m-d H:i:s'),
            ]);
        }

        foreach ($porDisciplina as $relacion) {
            if ($relacion['fecha_borrado'] === null) {
                $this->sociosDisciplinas->delete($relacion['id']);
            }
        }
    }
}
