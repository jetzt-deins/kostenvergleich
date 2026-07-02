<?php

namespace App\Controllers\Verwaltung;

use App\Controllers\BaseController;

class Trucker extends BaseController
{
    public function index(): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->orderBy('name')->get()->getResultArray();

        return view('verwaltung/trucker/index', [
            'title'   => 'Trucker Verwaltung',
            'trucker' => $trucker,
        ]);
    }

    public function neu(): string
    {
        return view('verwaltung/trucker/form', [
            'title'   => 'Neuen Trucker anlegen',
            'trucker' => null,
        ]);
    }

    public function speichern()
    {
        $db = \Config\Database::connect();

        $data = [
            'name'       => $this->request->getPost('name'),
            'kurzname'   => $this->request->getPost('kurzname'),
            'strasse'    => $this->request->getPost('strasse'),
            'plz'        => $this->request->getPost('plz'),
            'ort'        => $this->request->getPost('ort'),
            'telefon'    => $this->request->getPost('telefon'),
            'email'      => $this->request->getPost('email'),
            'aktiv'      => $this->request->getPost('aktiv') ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('trucker')->insert($data);
        $trucker_id = $db->insertID();

        // Standard-Umrechnungsfaktoren anlegen
        $db->table('trucker_umrechnungsfaktoren')->insert([
            'trucker_id'          => $trucker_id,
            'cbm_faktor'          => 200,
            'ldm_faktor'          => 1000,
            'ldm_ab_europaletten' => 5,
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Trucker wurde erfolgreich angelegt.');
        return redirect()->to(base_url('verwaltung/trucker'));
    }

    public function bearbeiten(int $id): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('id', $id)->get()->getRowArray();
        $umrechnungsfaktoren = $db->table('trucker_umrechnungsfaktoren')->where('trucker_id', $id)->get()->getRowArray();
        $verpackungsarten    = $db->table('trucker_verpackungsarten')->where('trucker_id', $id)->orderBy('sortierung')->get()->getResultArray();
        $zusatzprodukte      = $db->table('trucker_zusatzprodukte')->where('trucker_id', $id)->orderBy('sortierung')->get()->getResultArray();

        return view('verwaltung/trucker/form', [
            'title'               => 'Trucker bearbeiten',
            'trucker'             => $trucker,
            'umrechnungsfaktoren' => $umrechnungsfaktoren,
            'verpackungsarten'    => $verpackungsarten,
            'zusatzprodukte'      => $zusatzprodukte,
        ]);
    }

    public function aktualisieren(int $id)
    {
        $db = \Config\Database::connect();

        $db->table('trucker')->where('id', $id)->update([
            'name'       => $this->request->getPost('name'),
            'kurzname'   => $this->request->getPost('kurzname'),
            'strasse'    => $this->request->getPost('strasse'),
            'plz'        => $this->request->getPost('plz'),
            'ort'        => $this->request->getPost('ort'),
            'telefon'    => $this->request->getPost('telefon'),
            'email'      => $this->request->getPost('email'),
            'aktiv'      => $this->request->getPost('aktiv') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Umrechnungsfaktoren aktualisieren
        $db->table('trucker_umrechnungsfaktoren')->where('trucker_id', $id)->update([
            'cbm_faktor'          => $this->request->getPost('cbm_faktor'),
            'ldm_faktor'          => $this->request->getPost('ldm_faktor'),
            'ldm_ab_europaletten' => $this->request->getPost('ldm_ab_europaletten'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Trucker wurde erfolgreich aktualisiert.');
        return redirect()->to(base_url('verwaltung/trucker'));
    }

    public function loeschen(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('trucker')->where('id', $id)->delete();
        session()->setFlashdata('success', 'Trucker wurde erfolgreich gelöscht.');
        return redirect()->to(base_url('verwaltung/trucker'));
    }

    // ==================== VERPACKUNGSARTEN ====================

    public function verpackungsartNeu(int $trucker_id): string
    {
        return view('verwaltung/trucker/verpackungsart_form', [
            'title'      => 'Neue Verpackungsart',
            'trucker_id' => $trucker_id,
            'eintrag'    => null,
        ]);
    }

    public function verpackungsartSpeichern()
    {
        $db = \Config\Database::connect();

        $db->table('trucker_verpackungsarten')->insert([
            'trucker_id'        => $this->request->getPost('trucker_id'),
            'bezeichnung'       => $this->request->getPost('bezeichnung'),
            'min_gewicht'       => $this->request->getPost('min_gewicht'),
            'max_gewicht'       => $this->request->getPost('max_gewicht') ?: null,
            'max_laenge'        => $this->request->getPost('max_laenge') ?: null,
            'max_breite'        => $this->request->getPost('max_breite') ?: null,
            'max_hoehe'         => $this->request->getPost('max_hoehe') ?: null,
            'max_gewicht_kolli' => $this->request->getPost('max_gewicht_kolli') ?: null,
            'sortierung'        => $this->request->getPost('sortierung') ?: 0,
            'aktiv'             => $this->request->getPost('aktiv') ? 1 : 0,
            'lademittelgebuehr'          => $this->request->getPost('lademittelgebuehr') ?: null,
            'lademittelgebuehr_standard' => $this->request->getPost('lademittelgebuehr_standard') ? 1 : 0,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Verpackungsart wurde erfolgreich angelegt.');
        return redirect()->to(base_url('verwaltung/trucker/bearbeiten/' . $this->request->getPost('trucker_id')));
    }

    public function verpackungsartBearbeiten(int $id): string
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_verpackungsarten')->where('id', $id)->get()->getRowArray();

        return view('verwaltung/trucker/verpackungsart_form', [
            'title'      => 'Verpackungsart bearbeiten',
            'trucker_id' => $eintrag['trucker_id'],
            'eintrag'    => $eintrag,
        ]);
    }

    public function verpackungsartAktualisieren(int $id)
    {
        $db = \Config\Database::connect();

        $db->table('trucker_verpackungsarten')->where('id', $id)->update([
            'bezeichnung'       => $this->request->getPost('bezeichnung'),
            'min_gewicht'       => $this->request->getPost('min_gewicht'),
            'max_gewicht'       => $this->request->getPost('max_gewicht') ?: null,
            'max_laenge'        => $this->request->getPost('max_laenge') ?: null,
            'max_breite'        => $this->request->getPost('max_breite') ?: null,
            'max_hoehe'         => $this->request->getPost('max_hoehe') ?: null,
            'max_gewicht_kolli' => $this->request->getPost('max_gewicht_kolli') ?: null,
            'sortierung'        => $this->request->getPost('sortierung') ?: 0,
            'aktiv'             => $this->request->getPost('aktiv') ? 1 : 0,
            'lademittelgebuehr'          => $this->request->getPost('lademittelgebuehr') ?: null,
            'lademittelgebuehr_standard' => $this->request->getPost('lademittelgebuehr_standard') ? 1 : 0,
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Verpackungsart wurde erfolgreich aktualisiert.');
        return redirect()->to(base_url('verwaltung/trucker/bearbeiten/' . $this->request->getPost('trucker_id')));
    }

    public function verpackungsartLoeschen(int $id)
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_verpackungsarten')->where('id', $id)->get()->getRowArray();
        $db->table('trucker_verpackungsarten')->where('id', $id)->delete();
        session()->setFlashdata('success', 'Verpackungsart wurde erfolgreich gelöscht.');
        return redirect()->to(base_url('verwaltung/trucker/bearbeiten/' . $eintrag['trucker_id']));
    }

    // ==================== ZUSATZPRODUKTE ====================

    public function zusatzproduktNeu(int $trucker_id): string
    {
        return view('verwaltung/trucker/zusatzprodukt_form', [
            'title'      => 'Neues Zusatzprodukt',
            'trucker_id' => $trucker_id,
            'eintrag'    => null,
        ]);
    }

    public function zusatzproduktSpeichern()
    {
        $db = \Config\Database::connect();

        $db->table('trucker_zusatzprodukte')->insert([
            'trucker_id'    => $this->request->getPost('trucker_id'),
            'bezeichnung'   => $this->request->getPost('bezeichnung'),
            'aufschlag'     => $this->request->getPost('aufschlag'),
            'aufschlag_typ' => $this->request->getPost('aufschlag_typ'),
            'sortierung'    => $this->request->getPost('sortierung') ?: 0,
            'aktiv'         => $this->request->getPost('aktiv') ? 1 : 0,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Zusatzprodukt wurde erfolgreich angelegt.');
        return redirect()->to(base_url('verwaltung/trucker/bearbeiten/' . $this->request->getPost('trucker_id')));
    }

    public function zusatzproduktBearbeiten(int $id): string
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_zusatzprodukte')->where('id', $id)->get()->getRowArray();

        return view('verwaltung/trucker/zusatzprodukt_form', [
            'title'      => 'Zusatzprodukt bearbeiten',
            'trucker_id' => $eintrag['trucker_id'],
            'eintrag'    => $eintrag,
        ]);
    }

    public function zusatzproduktAktualisieren(int $id)
    {
        $db = \Config\Database::connect();

        $db->table('trucker_zusatzprodukte')->where('id', $id)->update([
            'bezeichnung'   => $this->request->getPost('bezeichnung'),
            'aufschlag'     => $this->request->getPost('aufschlag'),
            'aufschlag_typ' => $this->request->getPost('aufschlag_typ'),
            'sortierung'    => $this->request->getPost('sortierung') ?: 0,
            'aktiv'         => $this->request->getPost('aktiv') ? 1 : 0,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Zusatzprodukt wurde erfolgreich aktualisiert.');
        return redirect()->to(base_url('verwaltung/trucker/bearbeiten/' . $this->request->getPost('trucker_id')));
    }

    public function zusatzproduktLoeschen(int $id)
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_zusatzprodukte')->where('id', $id)->get()->getRowArray();
        $db->table('trucker_zusatzprodukte')->where('id', $id)->delete();
        session()->setFlashdata('success', 'Zusatzprodukt wurde erfolgreich gelöscht.');
        return redirect()->to(base_url('verwaltung/trucker/bearbeiten/' . $eintrag['trucker_id']));
    }
}