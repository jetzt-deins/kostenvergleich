<?php

namespace App\Controllers;

class Kalkulation extends BaseController
{
    public function index(): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('aktiv', 1)->orderBy('name')->get()->getResultArray();

        $zusatzprodukte = [];
        foreach ($trucker as $t) {
            $zusatzprodukte[$t['id']] = $db->table('trucker_zusatzprodukte')
                ->where('trucker_id', $t['id'])
                ->where('aktiv', 1)
                ->orderBy('sortierung')
                ->get()->getResultArray();
        }

        $verpackungsarten = [];
        foreach ($trucker as $t) {
            $verpackungsarten[$t['id']] = $db->table('trucker_verpackungsarten')
                ->where('trucker_id', $t['id'])
                ->where('aktiv', 1)
                ->orderBy('sortierung')
                ->get()->getResultArray();
        }

        return view('kalkulation/index', [
            'title'            => 'Kalkulation',
            'trucker'          => $trucker,
            'zusatzprodukte'   => $zusatzprodukte,
            'verpackungsarten' => $verpackungsarten,
        ]);
    }

    private function berechneGewicht(array $positionen): array
    {
        $gesamt_kg   = 0;
        $gesamt_cbm  = 0;
        $gesamt_ldm  = 0;
        $anzahl_euro = 0;

        foreach ($positionen as $pos) {
            if (empty($pos['anzahl']) || empty($pos['verpackungsart'])) continue;

            $anzahl        = (float)$pos['anzahl'];
            $kg_pro_stueck = (float)$pos['gewicht'];
            $laenge        = (float)$pos['laenge'];
            $breite        = (float)$pos['breite'];
            $hoehe         = (float)$pos['hoehe'];
            $verpackungsart = $pos['verpackungsart'];

            // Max-Gewicht pro Stück anhand Abmessungen
            // ab 121x81 cm = Industriepalette = keine Deckelung
            // alle anderen = max 400 kg pro Stück
            if ($laenge >= 121 || $breite >= 81) {
                // Keine Deckelung
            } else {
                $kg_pro_stueck = min($kg_pro_stueck, 400);
            }

            $kg = $kg_pro_stueck * $anzahl;

            $cbm = ($laenge / 100) * ($breite / 100) * ($hoehe / 100) * $anzahl;
            $ldm = ($laenge / 100) * ($breite / 100) / 2.4 * $anzahl;

            $gesamt_kg  += $kg;
            $gesamt_cbm += $cbm;
            $gesamt_ldm += $ldm;

            if ($verpackungsart === 'Europalette') {
                $anzahl_euro += $anzahl;
            }
        }

        return [
            'gesamt_kg'   => $gesamt_kg,
            'gesamt_cbm'  => $gesamt_cbm,
            'gesamt_ldm'  => $gesamt_ldm,
            'anzahl_euro' => $anzahl_euro,
        ];
    }

    private function berechneAbrechnungsgewicht(array $gewichte, array $positionen, array $umrechnungsfaktoren): float
    {
        $cbm_faktor = (float)($umrechnungsfaktoren['cbm_faktor'] ?? 200);
        $ldm_faktor = (float)($umrechnungsfaktoren['ldm_faktor'] ?? 1000);
        $ldm_ab_ep  = (int)($umrechnungsfaktoren['ldm_ab_europaletten'] ?? 5);

        $gew_cbm = $gewichte['gesamt_cbm'] * $cbm_faktor;
        $gew_ldm = $gewichte['gesamt_ldm'] * $ldm_faktor;

        if ($gewichte['anzahl_euro'] >= $ldm_ab_ep) {
            $abrechnungsgewicht = max($gewichte['gesamt_kg'], $gew_ldm);
        } else {
            $abrechnungsgewicht = max($gewichte['gesamt_kg'], $gew_cbm);
        }

        // Abmessungsbasiertes Mindestgewicht — mit Anzahl multipliziert
        foreach ($positionen as $pos) {
            if (empty($pos['anzahl']) || empty($pos['verpackungsart'])) continue;

            $laenge = (float)$pos['laenge'];
            $breite = (float)$pos['breite'];
            $anzahl = (float)$pos['anzahl'];

            if ($laenge >= 121 || $breite >= 81) {
                // Industriepalette: Min 250 kg pro Stück × Anzahl
                $min = 250 * $anzahl;
            } elseif ($laenge <= 60 && $breite <= 40) {
                // Viertelpalette: Min 50 kg × Anzahl
                $min = 50 * $anzahl;
            } elseif ($laenge <= 80 && $breite <= 60) {
                // Halbpalette: Min 100 kg × Anzahl
                $min = 100 * $anzahl;
            } else {
                // Europalette/Gitterbox: Min 200 kg × Anzahl
                $min = 200 * $anzahl;
            }

            $abrechnungsgewicht = max($abrechnungsgewicht, $min);
        }

        return $abrechnungsgewicht;
    }

    private function berechneLademittel(array $positionen): float
    {
        $lademittel_gesamt = 0;
        foreach ($positionen as $pos) {
            if (!empty($pos['lademittel']) && (float)$pos['lademittel'] > 0) {
                $lademittel_gesamt += (float)$pos['lademittel'] * (float)$pos['anzahl'];
            }
        }
        return $lademittel_gesamt;
    }

    public function berechnen()
    {
        $db             = \Config\Database::connect();
        $trucker_id     = $this->request->getPost('trucker_id');
        $plz            = $this->request->getPost('plz');
        $richtung       = $this->request->getPost('richtung');
        $zusatz_id      = $this->request->getPost('zusatzprodukt_id');
        $dieselzuschlag = (float)$this->request->getPost('dieselzuschlag');
        $positionen     = $this->request->getPost('positionen');

        // Validierung
        if (empty($trucker_id) || empty($plz) || empty($richtung)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bitte Trucker, PLZ und Richtung angeben.',
            ]);
        }

        if (strlen($plz) > 2 || !is_numeric($plz)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'PLZ muss zweistellig und numerisch sein.',
            ]);
        }

        if (empty($positionen)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bitte mindestens eine Position eingeben.',
            ]);
        }

        $trucker = $db->table('trucker')->where('id', $trucker_id)->where('aktiv', 1)->get()->getRowArray();
        if (!$trucker) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ungültiger Trucker.',
            ]);
        }

        $plz_exists = $db->table('preistabellen')
            ->where('trucker_id', $trucker_id)
            ->where('richtung', $richtung)
            ->where('plz', $plz)
            ->countAllResults();

        if (!$plz_exists) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Für PLZ ' . $plz . ' sind keine Preise hinterlegt.',
            ]);
        }

        $umrechnungsfaktoren = $db->table('trucker_umrechnungsfaktoren')
            ->where('trucker_id', $trucker_id)
            ->get()->getRowArray();

        if (!$umrechnungsfaktoren) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keine Umrechnungsfaktoren für diesen Trucker hinterlegt.',
            ]);
        }

        // Zusatzprodukt laden — nur für diesen Trucker
        $zusatzprodukt = null;
        if ($zusatz_id) {
            $zusatzprodukt = $db->table('trucker_zusatzprodukte')
                ->where('id', $zusatz_id)
                ->where('trucker_id', $trucker_id)
                ->where('aktiv', 1)
                ->get()->getRowArray();
        }

        // Gewichte berechnen
        $gewichte           = $this->berechneGewicht($positionen);
        $abrechnungsgewicht = $this->berechneAbrechnungsgewicht($gewichte, $positionen, $umrechnungsfaktoren);
        $lademittel_gesamt  = $this->berechneLademittel($positionen);

        $gew_cbm = $gewichte['gesamt_cbm'] * (float)$umrechnungsfaktoren['cbm_faktor'];
        $gew_ldm = $gewichte['gesamt_ldm'] * (float)$umrechnungsfaktoren['ldm_faktor'];

        // Gewichtsklasse ermitteln
        $gewichtsklasse = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->where('gewicht_von <=', $abrechnungsgewicht)
            ->where('gewicht_bis >=', $abrechnungsgewicht)
            ->get()->getRowArray();

        if (!$gewichtsklasse) {
            $gewichtsklasse = $db->table('trucker_gewichtsklassen')
                ->where('trucker_id', $trucker_id)
                ->orderBy('gewicht_bis', 'DESC')
                ->get()->getRowArray();
        }

        $frachtpreis = 0;
        if ($gewichtsklasse) {
            $preis_eintrag = $db->table('preistabellen')
                ->where('trucker_id', $trucker_id)
                ->where('richtung', $richtung)
                ->where('plz', $plz)
                ->where('gewichtsklassen_id', $gewichtsklasse['id'])
                ->get()->getRowArray();

            if ($preis_eintrag) {
                $frachtpreis = (float)$preis_eintrag['preis'];
            }
        }

        // Zusatzprodukt-Aufschlag
        $aufschlag = 0;
        if ($zusatzprodukt && $zusatzprodukt['aufschlag'] > 0) {
            if ($zusatzprodukt['aufschlag_typ'] === 'prozent') {
                $aufschlag = $frachtpreis * ($zusatzprodukt['aufschlag'] / 100);
            } else {
                $aufschlag = (float)$zusatzprodukt['aufschlag'];
            }
        }

        // Dieselzuschlag
        $diesel_betrag = 0;
        if ($dieselzuschlag > 0) {
            $diesel_betrag = $frachtpreis * ($dieselzuschlag / 100);
        }

        $gesamtpreis = $frachtpreis + $aufschlag + $diesel_betrag + $lademittel_gesamt;

        return $this->response->setJSON([
            'success'            => true,
            'abrechnungsgewicht' => round($abrechnungsgewicht, 2),
            'gesamt_kg'          => round($gewichte['gesamt_kg'], 2),
            'gesamt_cbm'         => round($gewichte['gesamt_cbm'], 4),
            'gesamt_ldm'         => round($gewichte['gesamt_ldm'], 4),
            'gew_cbm'            => round($gew_cbm, 2),
            'gew_ldm'            => round($gew_ldm, 2),
            'gewichtsklasse'     => $gewichtsklasse ? $gewichtsklasse['gewicht_bis'] : null,
            'frachtpreis'        => round($frachtpreis, 2),
            'aufschlag'          => round($aufschlag, 2),
            'diesel_betrag'      => round($diesel_betrag, 2),
            'dieselzuschlag'     => $dieselzuschlag,
            'lademittel_gesamt'  => round($lademittel_gesamt, 2),
            'gesamtpreis'        => round($gesamtpreis, 2),
            'trucker'            => $trucker['name'],
            'richtung'           => $richtung,
            'plz'                => $plz,
        ]);
    }

    public function vergleichen()
    {
        $db             = \Config\Database::connect();
        $plz            = $this->request->getPost('plz');
        $richtung       = $this->request->getPost('richtung');
        $dieselzuschlag = (float)$this->request->getPost('dieselzuschlag');
        $positionen     = $this->request->getPost('positionen');

        $trucker_alle = $db->table('trucker')->where('aktiv', 1)->orderBy('name')->get()->getResultArray();
        $ergebnisse   = [];

        foreach ($trucker_alle as $trucker) {
            $trucker_id          = $trucker['id'];
            $umrechnungsfaktoren = $db->table('trucker_umrechnungsfaktoren')
                ->where('trucker_id', $trucker_id)
                ->get()->getRowArray();

            if (!$umrechnungsfaktoren) continue;

            $gewichte           = $this->berechneGewicht($positionen);
            $abrechnungsgewicht = $this->berechneAbrechnungsgewicht($gewichte, $positionen, $umrechnungsfaktoren);
            $lademittel_gesamt  = $this->berechneLademittel($positionen);

            $gewichtsklasse = $db->table('trucker_gewichtsklassen')
                ->where('trucker_id', $trucker_id)
                ->where('gewicht_von <=', $abrechnungsgewicht)
                ->where('gewicht_bis >=', $abrechnungsgewicht)
                ->get()->getRowArray();

            if (!$gewichtsklasse) {
                $gewichtsklasse = $db->table('trucker_gewichtsklassen')
                    ->where('trucker_id', $trucker_id)
                    ->orderBy('gewicht_bis', 'DESC')
                    ->get()->getRowArray();
            }

            $frachtpreis = 0;
            if ($gewichtsklasse) {
                $preis_eintrag = $db->table('preistabellen')
                    ->where('trucker_id', $trucker_id)
                    ->where('richtung', $richtung)
                    ->where('plz', $plz)
                    ->where('gewichtsklassen_id', $gewichtsklasse['id'])
                    ->get()->getRowArray();

                if ($preis_eintrag) {
                    $frachtpreis = (float)$preis_eintrag['preis'];
                }
            }

            $diesel_betrag = 0;
            if ($dieselzuschlag > 0) {
                $diesel_betrag = $frachtpreis * ($dieselzuschlag / 100);
            }

            $gesamtpreis = $frachtpreis + $diesel_betrag + $lademittel_gesamt;

            $ergebnisse[] = [
                'trucker'            => $trucker['name'],
                'abrechnungsgewicht' => round($abrechnungsgewicht, 2),
                'gewichtsklasse'     => $gewichtsklasse ? $gewichtsklasse['gewicht_bis'] : null,
                'frachtpreis'        => round($frachtpreis, 2),
                'lademittel'         => round($lademittel_gesamt, 2),
                'diesel_betrag'      => round($diesel_betrag, 2),
                'gesamtpreis'        => round($gesamtpreis, 2),
                'hat_preise'         => $frachtpreis > 0,
            ];
        }

        $min_preis = PHP_FLOAT_MAX;
        foreach ($ergebnisse as $e) {
            if ($e['hat_preise'] && $e['gesamtpreis'] < $min_preis) {
                $min_preis = $e['gesamtpreis'];
            }
        }
        foreach ($ergebnisse as &$e) {
            $e['guenstigster'] = ($e['hat_preise'] && $e['gesamtpreis'] == $min_preis);
        }

        return $this->response->setJSON([
            'success'    => true,
            'ergebnisse' => $ergebnisse,
            'richtung'   => $richtung,
            'plz'        => $plz,
        ]);
    }
}