<?php

namespace App\Controllers;

class Kalkulation extends BaseController
{
    public function index(): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('aktiv', 1)->orderBy('name')->get()->getResultArray();

        // Zusatzprodukte für jeden Trucker laden
        $zusatzprodukte = [];
        foreach ($trucker as $t) {
            $zusatzprodukte[$t['id']] = $db->table('trucker_zusatzprodukte')
                ->where('trucker_id', $t['id'])
                ->where('aktiv', 1)
                ->orderBy('sortierung')
                ->get()->getResultArray();
        }

        // Verpackungsarten für jeden Trucker laden
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

    // Trucker prüfen
    $trucker = $db->table('trucker')->where('id', $trucker_id)->where('aktiv', 1)->get()->getRowArray();
    if (!$trucker) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Ungültiger Trucker.',
        ]);
    }

    // PLZ in Preistabelle prüfen
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

        // Trucker-Daten laden
        $trucker             = $db->table('trucker')->where('id', $trucker_id)->get()->getRowArray();
        $umrechnungsfaktoren = $db->table('trucker_umrechnungsfaktoren')->where('trucker_id', $trucker_id)->get()->getRowArray();
        $verpackungsarten    = $db->table('trucker_verpackungsarten')->where('trucker_id', $trucker_id)->where('aktiv', 1)->get()->getResultArray();

        // Verpackungsarten als Array [bezeichnung => daten]
        $vpa = [];
        foreach ($verpackungsarten as $v) {
            $vpa[$v['bezeichnung']] = $v;
        }

        // Zusatzprodukt laden
        $zusatzprodukt = null;
        if ($zusatz_id) {
            $zusatzprodukt = $db->table('trucker_zusatzprodukte')->where('id', $zusatz_id)->get()->getRowArray();
        }

        // Frachtpflichtiges Gewicht berechnen
        $gesamt_kg   = 0;
        $gesamt_cbm  = 0;
        $gesamt_ldm  = 0;
        $anzahl_euro = 0;

        $cbm_faktor = $umrechnungsfaktoren['cbm_faktor'];
        $ldm_faktor = $umrechnungsfaktoren['ldm_faktor'];
        $ldm_ab_ep  = $umrechnungsfaktoren['ldm_ab_europaletten'];

        foreach ($positionen as $pos) {
            if (empty($pos['anzahl']) || empty($pos['verpackungsart'])) continue;

            $anzahl         = (float)$pos['anzahl'];
            $kg             = (float)$pos['gewicht'] * $anzahl;
            $laenge         = (float)$pos['laenge'];
            $breite         = (float)$pos['breite'];
            $hoehe          = (float)$pos['hoehe'];
            $verpackungsart = $pos['verpackungsart'];

            // CBM berechnen
            $cbm = ($laenge / 100) * ($breite / 100) * ($hoehe / 100) * $anzahl;

            // LDM berechnen
            $ldm = ($laenge / 100) * ($breite / 100) / 2.4 * $anzahl;

            $gesamt_kg  += $kg;
            $gesamt_cbm += $cbm;
            $gesamt_ldm += $ldm;

            if ($verpackungsart === 'Europalette') {
                $anzahl_euro += $anzahl;
            }
        }

            // Gewicht aus CBM und LDM ermitteln
            $gew_cbm = $gesamt_cbm * $cbm_faktor;
            $gew_ldm = $gesamt_ldm * $ldm_faktor;

            // Ab x Europaletten LDM-Satz verwenden
            if ($anzahl_euro >= $ldm_ab_ep) {
                $abrechnungsgewicht = max($gesamt_kg, $gew_ldm);
            } else {
                $abrechnungsgewicht = max($gesamt_kg, $gew_cbm);
            }

            // Mindestgewicht je Verpackungsart prüfen
            foreach ($positionen as $pos) {
                if (empty($pos['verpackungsart'])) continue;
                $va = $pos['verpackungsart'];
                if (isset($vpa[$va]) && $vpa[$va]['min_gewicht'] > 0) {
                    $abrechnungsgewicht = max($abrechnungsgewicht, $vpa[$va]['min_gewicht']);
                }
            }

        // Gewichtsklasse ermitteln
        $gewichtsklasse = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->where('gewicht_von <=', $abrechnungsgewicht)
            ->where('gewicht_bis >=', $abrechnungsgewicht)
            ->get()->getRowArray();

        // Falls über Maximum, letzte Klasse nehmen
        if (!$gewichtsklasse) {
            $gewichtsklasse = $db->table('trucker_gewichtsklassen')
                ->where('trucker_id', $trucker_id)
                ->orderBy('gewicht_bis', 'DESC')
                ->get()->getRowArray();
        }

        // Preis ermitteln
        $preis_eintrag = null;
        $frachtpreis   = 0;

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

// Lademittelgebühren berechnen
        $lademittel_gesamt = 0;
        foreach ($positionen as $pos) {
            if (!empty($pos['lademittel']) && (float)$pos['lademittel'] > 0) {
                $lademittel_gesamt += (float)$pos['lademittel'] * (float)$pos['anzahl'];
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
            'gesamt_kg'          => round($gesamt_kg, 2),
            'gesamt_cbm'         => round($gesamt_cbm, 4),
            'gesamt_ldm'         => round($gesamt_ldm, 4),
            'gew_cbm'            => round($gew_cbm, 2),
            'gew_ldm'            => round($gew_ldm, 2),
            'gewichtsklasse'     => $gewichtsklasse ? $gewichtsklasse['gewicht_bis'] : null,
            'frachtpreis'        => round($frachtpreis, 2),
            'aufschlag'          => round($aufschlag, 2),
            'gesamtpreis'        => round($gesamtpreis, 2),
            'diesel_betrag'      => round($diesel_betrag, 2),
            'dieselzuschlag'     => $dieselzuschlag,
            'trucker'            => $trucker['name'],
            'richtung'           => $richtung,
            'plz'                => $plz,
            'lademittel_gesamt'  => round($lademittel_gesamt, 2),
        ]);

        // Lademittelgebühren berechnen
        $lademittel_gesamt = 0;
        foreach ($positionen as $pos) {
            if (!empty($pos['lademittel']) && (float)$pos['lademittel'] > 0) {
                $lademittel_gesamt += (float)$pos['lademittel'] * (float)$pos['anzahl'];
            }
        }

        $gesamtpreis = $frachtpreis + $aufschlag + $diesel_betrag + $lademittel_gesamt;
    }

    public function vergleichen()
    {
        $db         = \Config\Database::connect();
        $plz        = $this->request->getPost('plz');
        $richtung   = $this->request->getPost('richtung');
        $dieselzuschlag = (float)$this->request->getPost('dieselzuschlag');
        $positionen = $this->request->getPost('positionen');

        $trucker_alle = $db->table('trucker')->where('aktiv', 1)->orderBy('name')->get()->getResultArray();

        $ergebnisse = [];

        foreach ($trucker_alle as $trucker) {
            $trucker_id          = $trucker['id'];
            $umrechnungsfaktoren = $db->table('trucker_umrechnungsfaktoren')->where('trucker_id', $trucker_id)->get()->getRowArray();
            $verpackungsarten    = $db->table('trucker_verpackungsarten')->where('trucker_id', $trucker_id)->where('aktiv', 1)->get()->getResultArray();

            $vpa = [];
            foreach ($verpackungsarten as $v) {
                $vpa[$v['bezeichnung']] = $v;
            }

            $gesamt_kg   = 0;
            $gesamt_cbm  = 0;
            $gesamt_ldm  = 0;
            $anzahl_euro = 0;

            $cbm_faktor = $umrechnungsfaktoren['cbm_faktor'];
            $ldm_faktor = $umrechnungsfaktoren['ldm_faktor'];
            $ldm_ab_ep  = $umrechnungsfaktoren['ldm_ab_europaletten'];

            foreach ($positionen as $pos) {
                if (empty($pos['anzahl']) || empty($pos['verpackungsart'])) continue;

                $anzahl         = (float)$pos['anzahl'];
                $kg             = (float)$pos['gewicht'] * $anzahl;
                $laenge         = (float)$pos['laenge'];
                $breite         = (float)$pos['breite'];
                $hoehe          = (float)$pos['hoehe'];
                $verpackungsart = $pos['verpackungsart'];

                $cbm = ($laenge / 100) * ($breite / 100) * ($hoehe / 100) * $anzahl;
                $ldm = ($laenge / 100) * ($breite / 100) / 2.4 * $anzahl;

                $gesamt_kg  += $kg;
                $gesamt_cbm += $cbm;
                $gesamt_ldm += $ldm;

                if ($verpackungsart === 'Europalette') {
                    $anzahl_euro += $anzahl;
                }
            }

            $gew_cbm = $gesamt_cbm * $cbm_faktor;
            $gew_ldm = $gesamt_ldm * $ldm_faktor;

            if ($anzahl_euro >= $ldm_ab_ep) {
                $abrechnungsgewicht = max($gesamt_kg, $gew_ldm);
            } else {
                $abrechnungsgewicht = max($gesamt_kg, $gew_cbm);
            }

            foreach ($positionen as $pos) {
                if (empty($pos['verpackungsart'])) continue;
                $va = $pos['verpackungsart'];
                if (isset($vpa[$va]) && $vpa[$va]['min_gewicht'] > 0) {
                    $abrechnungsgewicht = max($abrechnungsgewicht, $vpa[$va]['min_gewicht']);
                }
            }

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

           // Lademittelgebühren und Dieselzuschlag berechnen
            $lademittel_gesamt = 0;
            foreach ($positionen as $pos) {
                if (!empty($pos['lademittel']) && (float)$pos['lademittel'] > 0) {
                    $lademittel_gesamt += (float)$pos['lademittel'] * (float)$pos['anzahl'];
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

        // Günstigsten Trucker markieren
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