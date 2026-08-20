<?php

namespace App\Models;

use CodeIgniter\Model;

class TruckerModel extends Model
{
    protected $table            = 'trucker';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'name', 'kurzname', 'strasse', 'plz', 'ort', 'telefon', 'email', 'aktiv',
    ];

    public function getAktive(): array
    {
        return $this->where('aktiv', 1)->orderBy('name')->findAll();
    }
}