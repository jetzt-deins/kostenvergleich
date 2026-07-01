<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= esc($title) ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= base_url('verwaltung/gewichtsklassen/' . $trucker['id']) ?>" class="btn btn-outline-secondary">
                    Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <form action="<?= $eintrag ? base_url('verwaltung/gewichtsklassen/aktualisieren/' . $eintrag['id']) : base_url('verwaltung/gewichtsklassen/speichern') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="trucker_id" value="<?= esc($trucker['id']) ?>">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gewichtsklasse — <?= esc($trucker['name']) ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Gewicht von (kg)</label>
                            <input type="number" step="0.001" name="gewicht_von" class="form-control" value="<?= esc($eintrag['gewicht_von'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Gewicht bis (kg)</label>
                            <input type="number" step="0.001" name="gewicht_bis" class="form-control" value="<?= esc($eintrag['gewicht_bis'] ?? '') ?>" required>
                        </div>
                        <?php if ($eintrag): ?>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sortierung</label>
                            <input type="number" name="sortierung" class="form-control" value="<?= esc($eintrag['sortierung'] ?? 0) ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="<?= base_url('verwaltung/gewichtsklassen/' . $trucker['id']) ?>" class="btn btn-outline-secondary ms-2">Abbrechen</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>