<?php

namespace App\Models;

use CodeIgniter\Model;

class SociosTutoresModel extends Model
{
    protected $table = 'socios_tutores';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'id_socio',
        'id_tutor',
        'fecha_borrado',
    ];
    protected $deletedField = 'fecha_borrado';
}
