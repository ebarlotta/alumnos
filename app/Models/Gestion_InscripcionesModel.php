<?php

namespace App\Models;

use CodeIgniter\Model;

class Gestion_InscripcionesModel extends Model
{
    protected $table      = 'gestion_inscripciones';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_persona', 'id_instituto', 'id_carrera', 'estado', 'anolectivo'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}