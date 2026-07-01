<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= esc($title) ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= base_url('verwaltung/trucker/bearbeiten/' . $trucker_id) ?>" class="btn btn-outline-secondary">
                    Zurück zum Trucker
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <form action="<?= $eintrag ? base_url('verwaltung/trucker/verpackungsart/aktualisieren/' . $eintrag['id']) : base_url('verwaltung/trucker/verpackungsart/speichern') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="trucker_id" value="<?= esc($trucker_id) ?>">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Verpackungsart</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label required">Bezeichnung</label>
                            <input type="text" name="bezeichnung" class="form-control" value="<?= esc($eintrag['bezeichnung'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sortierung</label>
                            <input type="number" name="sortierung" class="form-control" value="<?= esc($eintrag['sortierung'] ?? 0) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Min. Gewicht (kg)</label>
                            <input type="number" step="0.001" name="min_gewicht" class="form-control" value="<?= esc($eintrag['min_gewicht'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Max. Gewicht (kg)</label>
                            <input type="number" step="0.001" name="max_gewicht" class="form-control" value="<?= esc($eintrag['max_gewicht'] ?? '') ?>">
                            <small class="text-muted">Leer lassen = keine Deckelung</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Max. Gewicht/Kolli (kg)</label>
                            <input type="number" step="0.01" name="max_gewicht_kolli" class="form-control" value="<?= esc($eintrag['max_gewicht_kolli'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Aktiv</label>
                            <div class="mt-2">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="aktiv" value="1" <?= (!isset($eintrag) || $eintrag['aktiv']) ? 'checked' : '' ?>>
                                    <span class="form-check-label">Aktiv</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max. Länge (cm)</label>
                            <input type="number" step="0.01" name="max_laenge" class="form-control" value="<?= esc($eintrag['max_laenge'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max. Breite (cm)</label>
                            <input type="number" step="0.01" name="max_breite" class="form-control" value="<?= esc($eintrag['max_breite'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max. Höhe (cm)</label>
                            <input type="number" step="0.01" name="max_hoehe" class="form-control" value="<?= esc($eintrag['max_hoehe'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="<?= base_url('verwaltung/trucker/bearbeiten/' . $trucker_id) ?>" class="btn btn-outline-secondary ms-2">Abbrechen</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>