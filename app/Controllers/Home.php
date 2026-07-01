<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $anzahl_trucker      = $db->table('trucker')->where('aktiv', 1)->countAllResults();
        $trucker_liste       = $db->table('trucker')->where('aktiv', 1)->orderBy('name')->get()->getResultArray();
        $anzahl_preiseintraege = $db->table('preistabellen')->countAllResults();
        $anzahl_plz          = $db->table('preistabellen')->distinct()->select('plz')->countAllResults();

        return view('dashboard', [
            'title'                => 'Dashboard',
            'anzahl_trucker'       => $anzahl_trucker,
            'trucker_liste'        => $trucker_liste,
            'anzahl_preiseintraege' => $anzahl_preiseintraege,
            'anzahl_plz'           => $anzahl_plz,
        ]);
    }
}