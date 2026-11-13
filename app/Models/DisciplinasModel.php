<?php

namespace App\Models;

use CodeIgniter\Model;

class DisciplinasModel extends Model
{
    protected $table = 'disciplinas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'activo',
        'fecha_alta',
        'fecha_edicion',
        'fecha_borrado',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edicion';
    protected $deletedField = 'fecha_borrado';
}
