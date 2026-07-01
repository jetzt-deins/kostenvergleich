<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= esc($title) ?></h2>
            </div>
          <div class="col-auto ms-auto d-flex gap-2">
                <?php if ($trucker): ?>
                <a href="<?= base_url('verwaltung/gewichtsklassen/' . $trucker['id']) ?>" class="btn btn-outline-secondary">
                    Gewichtsklassen
                </a>
                <?php endif; ?>
                <a href="<?= base_url('verwaltung/trucker') ?>" class="btn btn-outline-secondary">
                    Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">

        <!-- Stammdaten -->
        <form action="<?= $trucker ? base_url('verwaltung/trucker/aktualisieren/' . $trucker['id']) : base_url('verwaltung/trucker/speichern') ?>" method="post">
            <?= csrf_field() ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Stammdaten</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label required">Name</label>
                            <input type="text" name="name" class="form-control" value="<?= esc($trucker['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kurzname</label>
                            <input type="text" name="kurzname" class="form-control" value="<?= esc($trucker['kurzname'] ?? '') ?>">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Straße</label>
                            <input type="text" name="strasse" class="form-control" value="<?= esc($trucker['strasse'] ?? '') ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">PLZ</label>
                            <input type="text" name="plz" class="form-control" value="<?= esc($trucker['plz'] ?? '') ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Ort</label>
                            <input type="text" name="ort" class="form-control" value="<?= esc($trucker['ort'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="telefon" class="form-control" value="<?= esc($trucker['telefon'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">E-Mail</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($trucker['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="aktiv" value="1" <?= isset($trucker['aktiv']) && $trucker['aktiv'] ? 'checked' : 'checked' ?>>
                                <span class="form-check-label">Aktiv</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Umrechnungsfaktoren -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Umrechnungsfaktoren</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">CBM-Faktor</label>
                            <input type="number" step="0.01" name="cbm_faktor" class="form-control" value="<?= esc($umrechnungsfaktoren['cbm_faktor'] ?? 200) ?>" required>
                            <small class="text-muted">Gewicht = CBM × Faktor</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">LDM-Faktor</label>
                            <input type="number" step="0.01" name="ldm_faktor" class="form-control" value="<?= esc($umrechnungsfaktoren['ldm_faktor'] ?? 1000) ?>" required>
                            <small class="text-muted">Gewicht = LDM × Faktor</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">LDM ab x Europaletten</label>
                            <input type="number" name="ldm_ab_europaletten" class="form-control" value="<?= esc($umrechnungsfaktoren['ldm_ab_europaletten'] ?? 5) ?>" required>
                            <small class="text-muted">Ab x Europaletten wird LDM-Satz verwendet</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-transparent mt-3">
                <button type="submit" class="btn btn-primary">Speichern</button>
                <a href="<?= base_url('verwaltung/trucker') ?>" class="btn btn-outline-secondary ms-2">Abbrechen</a>
            </div>
        </form>

        <!-- Verpackungsarten (nur bei bestehendem Trucker) -->
        <?php if ($trucker): ?>
        <div class="card mb-3 mt-3">
            <div class="card-header">
                <h3 class="card-title">Verpackungsarten / Gutstruktur</h3>
                <div class="card-options">
                    <a href="<?= base_url('verwaltung/trucker/verpackungsart/neu/' . $trucker['id']) ?>" class="btn btn-sm btn-primary">
                        + Neue Verpackungsart
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Bezeichnung</th>
                            <th>Min. Gewicht (kg)</th>
                            <th>Max. Gewicht (kg)</th>
                            <th>Max. Gewicht/Kolli (kg)</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($verpackungsarten)): ?>
                            <tr><td colspan="5" class="text-center text-muted">Keine Verpackungsarten vorhanden</td></tr>
                        <?php else: ?>
                            <?php foreach ($verpackungsarten as $v): ?>
                                <tr>
                                    <td><?= esc($v['bezeichnung']) ?></td>
                                    <td><?= esc($v['min_gewicht']) ?></td>
                                    <td><?= $v['max_gewicht'] ?? '<span class="text-muted">keine Deckelung</span>' ?></td>
                                    <td><?= esc($v['max_gewicht_kolli']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('verwaltung/trucker/verpackungsart/bearbeiten/' . $v['id']) ?>" class="btn btn-sm btn-outline-primary">Bearbeiten</a>
                                            <a href="<?= base_url('verwaltung/trucker/verpackungsart/loeschen/' . $v['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Verpackungsart wirklich löschen?')">Löschen</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Zusatzprodukte -->
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Zusatzprodukte</h3>
                <div class="card-options">
                    <a href="<?= base_url('verwaltung/trucker/zusatzprodukt/neu/' . $trucker['id']) ?>" class="btn btn-sm btn-primary">
                        + Neues Zusatzprodukt
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Bezeichnung</th>
                            <th>Aufschlag (€)</th>
                            <th>Typ</th>
                            <th>Sortierung</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($zusatzprodukte)): ?>
                            <tr><td colspan="5" class="text-center text-muted">Keine Zusatzprodukte vorhanden</td></tr>
                        <?php else: ?>
                            <?php foreach ($zusatzprodukte as $z): ?>
                                <tr>
                                    <td><?= esc($z['bezeichnung']) ?></td>
                                    <td><?= number_format($z['aufschlag'], 2, ',', '.') ?> €</td>
                                    <td><?= esc($z['aufschlag_typ']) ?></td>
                                    <td><?= esc($z['sortierung']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('verwaltung/trucker/zusatzprodukt/bearbeiten/' . $z['id']) ?>" class="btn btn-sm btn-outline-primary">Bearbeiten</a>
                                            <a href="<?= base_url('verwaltung/trucker/zusatzprodukt/loeschen/' . $z['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Zusatzprodukt wirklich löschen?')">Löschen</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>