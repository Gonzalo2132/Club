<?php

namespace App\Models;

use CodeIgniter\Model;

class SociosDisciplinasModel extends Model
{
    protected $table = 'socios_disciplinas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'id_socio',
        'id_disciplina',
        'fecha_alta',
        'fecha_borrado',
    ];
    protected $useTimestamps = false;
    protected $deletedField = 'fecha_borrado';
}
