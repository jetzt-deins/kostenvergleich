<?php

namespace App\Models;

use CodeIgniter\Model;

class PreistabellenModel extends Model
{
    protected $table         = 'preistabellen';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'trucker_id', 'richtung', 'plz', 'gewichtsklassen_id', 'preis',
    ];

    public function plzHatPreise(int $truckerId, string $richtung, string $plz): bool
    {
        return $this->where('trucker_id', $truckerId)
            ->where('richtung', $richtung)
            ->where('plz', $plz)
            ->countAllResults() > 0;
    }

    public function findePreis(int $truckerId, string $richtung, string $plz, int $gewichtsklassenId): ?array
    {
        return $this->where('trucker_id', $truckerId)
            ->where('richtung', $richtung)
            ->where('plz', $plz)
            ->where('gewichtsklassen_id', $gewichtsklassenId)
            ->first();
    }

    public function getFuerAnzeige(int $truckerId, string $richtung): array
    {
        $rows = $this->where('trucker_id', $truckerId)
            ->where('richtung', $richtung)
            ->findAll();

        $preise = [];
        foreach ($rows as $r) {
            $preise[$r['plz']][$r['gewichtsklassen_id']] = $r['preis'];
        }
        ksort($preise);
        return $preise;
    }
}