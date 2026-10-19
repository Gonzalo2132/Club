<?php

namespace App\Models;

use CodeIgniter\Model;

class TutoresModel extends Model
{
    protected $table = 'tutores';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'email',
        'parentesco',
        'fecha_alta',
        'fecha_edicion',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edicion';
}
