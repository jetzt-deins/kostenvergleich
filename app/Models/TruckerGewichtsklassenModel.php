<?php

namespace App\Models;

use CodeIgniter\Model;

class TruckerGewichtsklassenModel extends Model
{
    protected $table         = 'trucker_gewichtsklassen';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'trucker_id', 'gewicht_von', 'gewicht_bis', 'sortierung',
    ];

    public function getByTruckerId(int $truckerId): array
    {
        return $this->where('trucker_id', $truckerId)->orderBy('sortierung')->findAll();
    }

    public function findePassendeKlasse(int $truckerId, float $gewicht): ?array
    {
        $klasse = $this->where('trucker_id', $truckerId)
            ->where('gewicht_von <=', $gewicht)
            ->where('gewicht_bis >=', $gewicht)
            ->first();

        if (!$klasse) {
            // Über Maximum: höchste Klasse zurückgeben
            $klasse = $this->where('trucker_id', $truckerId)
                ->orderBy('gewicht_bis', 'DESC')
                ->first();
        }

        return $klasse;
    }
}