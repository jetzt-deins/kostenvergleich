<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= esc($title) ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= base_url('verwaltung/preistabellen/anzeigen/' . $trucker['id'] . '/' . $richtung) ?>" class="btn btn-outline-secondary">
                    Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('verwaltung/preistabellen/aktualisieren/' . $trucker['id'] . '/' . $richtung . '/' . $plz) ?>" method="post">
            <?= csrf_field() ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?= esc($trucker['name']) ?> —
                        <?= ucfirst(esc($richtung)) ?> —
                        PLZ <?= $plz ? esc($plz) : '' ?>
                    </h3>
                </div>

                <?php if (empty($plz)): ?>
                <div class="card-body border-bottom">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label required">PLZ-Zone (2-stellig)</label>
                            <input type="text" name="plz_neu" class="form-control" maxlength="2" placeholder="z.B. 38" required>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card-body">
                    <div class="row">
                        <?php foreach ($gewichtsklassen as $gk): ?>
                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                <label class="form-label">
                                    bis <?= esc($gk['gewicht_bis']) ?> kg
                                    <small class="text-muted">(ab <?= esc($gk['gewicht_von']) ?> kg)</small>
                                </label>
                                <div class="input-group">
                                    <input type="hidden"
                                           name="preise[<?= $gk['id'] ?>][id]"
                                           value="<?= isset($preise[$gk['id']]) ? $preise[$gk['id']]['id'] : '' ?>">
                                    <input type="number"
                                           step="0.01"
                                           name="preise[<?= $gk['id'] ?>][preis]"
                                           class="form-control form-control-sm"
                                           value="<?= isset($preise[$gk['id']]) ? number_format($preise[$gk['id']]['preis'], 2, '.', '') : '' ?>">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="<?= base_url('verwaltung/preistabellen/anzeigen/' . $trucker['id'] . '/' . $richtung) ?>" class="btn btn-outline-secondary ms-2">Abbrechen</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>