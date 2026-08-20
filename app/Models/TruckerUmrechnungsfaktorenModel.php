<?php

namespace App\Models;

use CodeIgniter\Model;

class TruckerUmrechnungsfaktorenModel extends Model
{
    protected $table         = 'trucker_umrechnungsfaktoren';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'trucker_id', 'cbm_faktor', 'ldm_faktor', 'ldm_ab_europaletten',
    ];

    public function getByTruckerId(int $truckerId): ?array
    {
        return $this->where('trucker_id', $truckerId)->first();
    }
}