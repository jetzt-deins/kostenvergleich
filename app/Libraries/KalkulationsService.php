<?php

namespace App\Libraries;

use App\Models\TruckerModel;
use App\Models\TruckerUmrechnungsfaktorenModel;
use App\Models\TruckerVerpackungsartenModel;
use App\Models\TruckerZusatzprodukteModel;
use App\Models\TruckerGewichtsklassenModel;
use App\Models\PreistabellenModel;

class KalkulationsService
{
    private TruckerModel                     $truckerModel;
    private TruckerUmrechnungsfaktorenModel  $umrechnungsfaktorenModel;
    private TruckerVerpackungsartenModel     $verpackungsartenModel;
    private TruckerZusatzprodukteModel       $zusatzprodukteModel;
    private TruckerGewichtsklassenModel      $gewichtsklassenModel;
    private PreistabellenModel               $preistabellenModel;

    // Fixe Deckelungsregeln aus dem Kundenkalkulationstool
    private const MAX_GEWICHT_STANDARD      = 400;   // kg, gilt bis 120x80 cm
    private const MIN_GEWICHT_INDUSTRIEPAL  = 250;   // kg, ab 121x81 cm
    private const MIN_GEWICHT_EURO_GITTER   = 200;   // kg, bis 120x80 cm
    private const MIN_GEWICHT_HALBPALETTE   = 100;   // kg, bis 80x60 cm
    private const MIN_GEWICHT_VIERTELPAL    = 50;    // kg, bis 60x40 cm
    private const INDUSTRIEPAL_AB_LAENGE    = 121;
    private const INDUSTRIEPAL_AB_BREITE    = 81;
    private const LDM_TEILER                = 2.4;

    public function __construct()
    {
        $this->truckerModel             = new TruckerModel();
        $this->umrechnungsfaktorenModel = new TruckerUmrechnungsfaktorenModel();
        $this->verpackungsartenModel    = new TruckerVerpackungsartenModel();
        $this->zusatzprodukteModel      = new TruckerZusatzprodukteModel();
        $this->gewichtsklassenModel     = new TruckerGewichtsklassenModel();
        $this->preistabellenModel       = new PreistabellenModel();
    }

    /**
     * Validiert die Basis-Eingaben einer Kalkulationsanfrage.
     * Gibt ein Fehler-Array zurück, oder null wenn alles ok ist.
     */
    public function validiereEingabe(?string $truckerId, ?string $plz, ?string $richtung, ?array $positionen): ?array
    {
        if (empty($truckerId) || empty($plz) || empty($richtung)) {
            return ['message' => 'Bitte Trucker, PLZ und Richtung angeben.'];
        }

        if (strlen($plz) > 2 || !is_numeric($plz)) {
            return ['message' => 'PLZ muss zweistellig und numerisch sein.'];
        }

        if (empty($positionen)) {
            return ['message' => 'Bitte mindestens eine Position eingeben.'];
        }

        return null;
    }

    public function ladeTrucker(int $truckerId): ?array
    {
        return $this->truckerModel->where('id', $truckerId)->where('aktiv', 1)->first();
    }

    public function ladeAktiveTrucker(): array
    {
        return $this->truckerModel->getAktive();
    }

    public function plzHatPreise(int $truckerId, string $richtung, string $plz): bool
    {
        return $this->preistabellenModel->plzHatPreise($truckerId, $richtung, $plz);
    }

    public function ladeUmrechnungsfaktoren(int $truckerId): ?array
    {
        return $this->umrechnungsfaktorenModel->getByTruckerId($truckerId);
    }

    public function ladeZusatzprodukt(?string $zusatzId, int $truckerId): ?array
    {
        if (!$zusatzId) {
            return null;
        }
        return $this->zusatzprodukteModel->getAktivesByIdUndTruckerId((int)$zusatzId, $truckerId);
    }

    /**
     * Berechnet Gesamtgewicht, CBM, LDM und Europaletten-Anzahl aus den Positionen.
     * Berücksichtigt die abmessungsbasierte Max-Gewicht-Deckelung pro Stück.
     */
    public function berechneGewicht(array $positionen): array
    {
        $gesamtKg   = 0.0;
        $gesamtCbm  = 0.0;
        $gesamtLdm  = 0.0;
        $anzahlEuro = 0.0;

        foreach ($positionen as $pos) {
            if (empty($pos['anzahl']) || empty($pos['verpackungsart'])) {
                continue;
            }

            $anzahl        = (float)$pos['anzahl'];
            $kgProStueck   = (float)$pos['gewicht'];
            $laenge        = (float)$pos['laenge'];
            $breite        = (float)$pos['breite'];
            $hoehe         = (float)$pos['hoehe'];
            $verpackungsart = $pos['verpackungsart'];

            if (!$this->istIndustriepaletteGross($laenge, $breite)) {
                $kgProStueck = min($kgProStueck, self::MAX_GEWICHT_STANDARD);
            }

            $gesamtKg  += $kgProStueck * $anzahl;
            $gesamtCbm += ($laenge / 100) * ($breite / 100) * ($hoehe / 100) * $anzahl;
            $gesamtLdm += ($laenge / 100) * ($breite / 100) / self::LDM_TEILER * $anzahl;

            if ($verpackungsart === 'Europalette') {
                $anzahlEuro += $anzahl;
            }
        }

        return [
            'gesamt_kg'   => $gesamtKg,
            'gesamt_cbm'  => $gesamtCbm,
            'gesamt_ldm'  => $gesamtLdm,
            'anzahl_euro' => $anzahlEuro,
        ];
    }

    /**
     * Ermittelt das Abrechnungsgewicht aus tatsächlichem Gewicht, CBM- und LDM-Umrechnung,
     * unter Berücksichtigung der abmessungsbasierten Mindestgewichte.
     */
    public function berechneAbrechnungsgewicht(array $gewichte, array $positionen, array $umrechnungsfaktoren): float
    {
        $cbmFaktor = (float)($umrechnungsfaktoren['cbm_faktor'] ?? 200);
        $ldmFaktor = (float)($umrechnungsfaktoren['ldm_faktor'] ?? 1000);
        $ldmAbEp   = (int)($umrechnungsfaktoren['ldm_ab_europaletten'] ?? 5);

        $gewCbm = $gewichte['gesamt_cbm'] * $cbmFaktor;
        $gewLdm = $gewichte['gesamt_ldm'] * $ldmFaktor;

        if ($gewichte['anzahl_euro'] >= $ldmAbEp) {
            $abrechnungsgewicht = max($gewichte['gesamt_kg'], $gewLdm);
        } else {
            $abrechnungsgewicht = max($gewichte['gesamt_kg'], $gewCbm);
        }

        foreach ($positionen as $pos) {
            if (empty($pos['anzahl']) || empty($pos['verpackungsart'])) {
                continue;
            }

            $laenge = (float)$pos['laenge'];
            $breite = (float)$pos['breite'];
            $anzahl = (float)$pos['anzahl'];

            $minProStueck = $this->ermittleMindestgewichtProStueck($laenge, $breite);
            $abrechnungsgewicht = max($abrechnungsgewicht, $minProStueck * $anzahl);
        }

        return $abrechnungsgewicht;
    }

    public function berechneGewichtAusCbm(array $gewichte, array $umrechnungsfaktoren): float
    {
        return $gewichte['gesamt_cbm'] * (float)($umrechnungsfaktoren['cbm_faktor'] ?? 200);
    }

    public function berechneGewichtAusLdm(array $gewichte, array $umrechnungsfaktoren): float
    {
        return $gewichte['gesamt_ldm'] * (float)($umrechnungsfaktoren['ldm_faktor'] ?? 1000);
    }

    public function berechneLademittel(array $positionen): float
    {
        $lademittelGesamt = 0.0;
        foreach ($positionen as $pos) {
            if (!empty($pos['lademittel']) && (float)$pos['lademittel'] > 0) {
                $lademittelGesamt += (float)$pos['lademittel'] * (float)$pos['anzahl'];
            }
        }
        return $lademittelGesamt;
    }

    public function findeGewichtsklasse(int $truckerId, float $abrechnungsgewicht): ?array
    {
        return $this->gewichtsklassenModel->findePassendeKlasse($truckerId, $abrechnungsgewicht);
    }

    public function findeFrachtpreis(int $truckerId, string $richtung, string $plz, ?array $gewichtsklasse): float
    {
        if (!$gewichtsklasse) {
            return 0.0;
        }

        $preisEintrag = $this->preistabellenModel->findePreis(
            $truckerId,
            $richtung,
            $plz,
            (int)$gewichtsklasse['id']
        );

        return $preisEintrag ? (float)$preisEintrag['preis'] : 0.0;
    }

    public function berechneZusatzproduktAufschlag(?array $zusatzprodukt, float $frachtpreis): float
    {
        if (!$zusatzprodukt || (float)$zusatzprodukt['aufschlag'] <= 0) {
            return 0.0;
        }

        if ($zusatzprodukt['aufschlag_typ'] === 'prozent') {
            return $frachtpreis * ((float)$zusatzprodukt['aufschlag'] / 100);
        }

        return (float)$zusatzprodukt['aufschlag'];
    }

    public function berechneDieselbetrag(float $frachtpreis, float $dieselzuschlag): float
    {
        if ($dieselzuschlag <= 0) {
            return 0.0;
        }
        return $frachtpreis * ($dieselzuschlag / 100);
    }

    /**
     * Führt die komplette Kalkulation für einen einzelnen Trucker durch.
     * Wirft keine Exceptions - liefert 'frachtpreis' = 0 wenn keine Preise hinterlegt sind.
     */
    public function kalkuliereFuerTrucker(
        int $truckerId,
        string $richtung,
        string $plz,
        array $positionen,
        float $dieselzuschlag,
        ?array $zusatzprodukt = null
    ): ?array {
        $umrechnungsfaktoren = $this->ladeUmrechnungsfaktoren($truckerId);
        if (!$umrechnungsfaktoren) {
            return null;
        }

        $gewichte           = $this->berechneGewicht($positionen);
        $abrechnungsgewicht = $this->berechneAbrechnungsgewicht($gewichte, $positionen, $umrechnungsfaktoren);
        $lademittelGesamt   = $this->berechneLademittel($positionen);

        $gewichtsklasse = $this->findeGewichtsklasse($truckerId, $abrechnungsgewicht);
        $frachtpreis    = $this->findeFrachtpreis($truckerId, $richtung, $plz, $gewichtsklasse);

        $aufschlag    = $this->berechneZusatzproduktAufschlag($zusatzprodukt, $frachtpreis);
        $dieselBetrag = $this->berechneDieselbetrag($frachtpreis, $dieselzuschlag);

        $gesamtpreis = $frachtpreis + $aufschlag + $dieselBetrag + $lademittelGesamt;

        return [
            'abrechnungsgewicht' => round($abrechnungsgewicht, 2),
            'gesamt_kg'          => round($gewichte['gesamt_kg'], 2),
            'gesamt_cbm'         => round($gewichte['gesamt_cbm'], 4),
            'gesamt_ldm'         => round($gewichte['gesamt_ldm'], 4),
            'gew_cbm'            => round($this->berechneGewichtAusCbm($gewichte, $umrechnungsfaktoren), 2),
            'gew_ldm'            => round($this->berechneGewichtAusLdm($gewichte, $umrechnungsfaktoren), 2),
            'gewichtsklasse'     => $gewichtsklasse ? $gewichtsklasse['gewicht_bis'] : null,
            'frachtpreis'        => round($frachtpreis, 2),
            'aufschlag'          => round($aufschlag, 2),
            'diesel_betrag'      => round($dieselBetrag, 2),
            'dieselzuschlag'     => $dieselzuschlag,
            'lademittel_gesamt'  => round($lademittelGesamt, 2),
            'gesamtpreis'        => round($gesamtpreis, 2),
            'hat_preise'         => $frachtpreis > 0,
        ];
    }

    // -------------------- Private Hilfsmethoden --------------------

    private function istIndustriepaletteGross(float $laenge, float $breite): bool
    {
        return $laenge >= self::INDUSTRIEPAL_AB_LAENGE || $breite >= self::INDUSTRIEPAL_AB_BREITE;
    }

    private function ermittleMindestgewichtProStueck(float $laenge, float $breite): float
    {
        if ($this->istIndustriepaletteGross($laenge, $breite)) {
            return self::MIN_GEWICHT_INDUSTRIEPAL;
        }
        if ($laenge <= 60 && $breite <= 40) {
            return self::MIN_GEWICHT_VIERTELPAL;
        }
        if ($laenge <= 80 && $breite <= 60) {
            return self::MIN_GEWICHT_HALBPALETTE;
        }
        return self::MIN_GEWICHT_EURO_GITTER;
    }
}