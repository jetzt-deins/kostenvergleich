<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= esc($title) ?></h2>
            </div>
            <div class="col-auto ms-auto d-flex gap-2">
                <a href="<?= base_url('verwaltung/preistabellen/anzeigen/' . $trucker['id'] . '/' . ($richtung === 'distribution' ? 'beschaffung' : 'distribution')) ?>" class="btn btn-outline-secondary">
                    Wechsel zu <?= $richtung === 'distribution' ? 'Beschaffung' : 'Distribution' ?>
                </a>
                <a href="<?= base_url('verwaltung/preistabellen/plz/neu/' . $trucker['id'] . '/' . $richtung) ?>" class="btn btn-primary">
                    + Neue PLZ-Zone
                </a>
                <a href="<?= base_url('verwaltung/preistabellen') ?>" class="btn btn-outline-secondary">
                    Zurück
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-vcenter card-table">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="min-width:60px;">PLZ</th>
                                <?php foreach ($gewichtsklassen as $gk): ?>
                                    <th class="text-center" style="min-width:55px;">
                                        <?= esc($gk['gewicht_bis']) ?>
                                    </th>
                                <?php endforeach; ?>
                                <th class="text-center" style="min-width:80px;">Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preise as $plz => $plz_preise): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($plz) ?></td>
                                    <?php foreach ($gewichtsklassen as $gk): ?>
                                        <td class="text-end" style="font-size:0.75rem;">
                                            <?= isset($plz_preise[$gk['id']]) ? number_format($plz_preise[$gk['id']], 2, ',', '.') : '-' ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-center">
                                        <a href="<?= base_url('verwaltung/preistabellen/bearbeiten/' . $trucker['id'] . '/' . $richtung . '/' . $plz) ?>" class="btn btn-sm btn-outline-primary">
                                            Bearbeiten
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>