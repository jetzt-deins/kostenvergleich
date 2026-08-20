<?php

namespace App\Models;

use CodeIgniter\Model;

class TruckerZusatzprodukteModel extends Model
{
    protected $table         = 'trucker_zusatzprodukte';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'trucker_id', 'bezeichnung', 'aufschlag', 'aufschlag_typ', 'sortierung', 'aktiv',
    ];

    public function getAktiveByTruckerId(int $truckerId): array
    {
        return $this->where('trucker_id', $truckerId)
            ->where('aktiv', 1)
            ->orderBy('sortierung')
            ->findAll();
    }

    public function getAktivesByIdUndTruckerId(int $id, int $truckerId): ?array
    {
        return $this->where('id', $id)
            ->where('trucker_id', $truckerId)
            ->where('aktiv', 1)
            ->first();
    }
}