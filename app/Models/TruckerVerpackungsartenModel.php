<?php

namespace App\Models;

use CodeIgniter\Model;

class TruckerVerpackungsartenModel extends Model
{
    protected $table         = 'trucker_verpackungsarten';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'trucker_id', 'bezeichnung', 'min_gewicht', 'max_gewicht',
        'max_laenge', 'max_breite', 'max_hoehe', 'max_gewicht_kolli',
        'lademittelgebuehr', 'lademittelgebuehr_standard',
        'sortierung', 'aktiv',
    ];

    public function getAktiveByTruckerId(int $truckerId): array
    {
        return $this->where('trucker_id', $truckerId)
            ->where('aktiv', 1)
            ->orderBy('sortierung')
            ->findAll();
    }

    /**
     * Liefert die Verpackungsarten indiziert nach Bezeichnung, z.B. ['Europalette' => [...]]
     */
    public function getAktiveIndiziertByTruckerId(int $truckerId): array
    {
        $arten = $this->getAktiveByTruckerId($truckerId);
        $indiziert = [];
        foreach ($arten as $art) {
            $indiziert[$art['bezeichnung']] = $art;
        }
        return $indiziert;
    }
}