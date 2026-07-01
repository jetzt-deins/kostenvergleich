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
        <form action="<?= $eintrag ? base_url('verwaltung/trucker/zusatzprodukt/aktualisieren/' . $eintrag['id']) : base_url('verwaltung/trucker/zusatzprodukt/speichern') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="trucker_id" value="<?= esc($trucker_id) ?>">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Zusatzprodukt</h3>
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Aufschlag</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="aufschlag" class="form-control" value="<?= esc($eintrag['aufschlag'] ?? '0.00') ?>" required>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Aufschlag Typ</label>
                            <select name="aufschlag_typ" class="form-select">
                                <option value="fix" <?= isset($eintrag) && $eintrag['aufschlag_typ'] === 'fix' ? 'selected' : '' ?>>Fix (€)</option>
                                <option value="prozent" <?= isset($eintrag) && $eintrag['aufschlag_typ'] === 'prozent' ? 'selected' : '' ?>>Prozentual (%)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktiv</label>
                            <div class="mt-2">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="aktiv" value="1" <?= (!isset($eintrag) || $eintrag['aktiv']) ? 'checked' : '' ?>>
                                    <span class="form-check-label">Aktiv</span>
                                </label>
                            </div>
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