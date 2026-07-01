<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Dashboard</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">

        <!-- Statistik-Karten -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader mb-2">Aktive Trucker</div>
                        <div class="h1 mb-0"><?= $anzahl_trucker ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader mb-2">PLZ-Zonen</div>
                        <div class="h1 mb-0"><?= $anzahl_plz ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader mb-2">Preiseinträge gesamt</div>
                        <div class="h1 mb-0"><?= number_format($anzahl_preiseintraege, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="subheader mb-2">Schnellzugriff</div>
                        <a href="<?= base_url('kalkulation') ?>" class="btn btn-primary w-100">
                            Zur Kalkulation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trucker-Übersicht -->
        <div class="row row-cards">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aktive Trucker</h3>
                        <div class="card-options">
                            <a href="<?= base_url('verwaltung/trucker') ?>" class="btn btn-sm btn-outline-primary">Verwalten</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Kurzname</th>
                                    <th>Ort</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($trucker_liste as $t): ?>
                                    <tr>
                                        <td><?= esc($t['name']) ?></td>
                                        <td><?= esc($t['kurzname']) ?></td>
                                        <td><?= esc($t['plz']) ?> <?= esc($t['ort']) ?></td>
                                        <td>
                                            <a href="<?= base_url('verwaltung/preistabellen/anzeigen/' . $t['id'] . '/distribution') ?>" class="btn btn-sm btn-outline-secondary">Preise</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Schnellkalkulation</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Starten Sie direkt eine Frachtpreiskalkulation für alle aktiven Trucker.</p>
                        <a href="<?= base_url('kalkulation') ?>" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="4" y="3" width="16" height="18" rx="2"/>
                                <line x1="8" y1="7" x2="16" y2="7"/>
                                <line x1="8" y1="11" x2="16" y2="11"/>
                                <line x1="8" y1="15" x2="12" y2="15"/>
                            </svg>
                            Kalkulation starten
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>