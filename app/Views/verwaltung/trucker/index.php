<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Trucker Verwaltung</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="<?= base_url('verwaltung/trucker/neu') ?>" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Neuer Trucker
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Kurzname</th>
                            <th>Ort</th>
                            <th>Telefon</th>
                            <th>Status</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trucker)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Keine Trucker vorhanden</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($trucker as $t): ?>
                                <tr>
                                    <td><?= esc($t['name']) ?></td>
                                    <td><?= esc($t['kurzname']) ?></td>
                                    <td><?= esc($t['plz']) ?> <?= esc($t['ort']) ?></td>
                                    <td><?= esc($t['telefon']) ?></td>
                                    <td>
                                        <?php if ($t['aktiv']): ?>
                                            <span class="badge bg-success">Aktiv</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inaktiv</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('verwaltung/trucker/bearbeiten/' . $t['id']) ?>" class="btn btn-sm btn-outline-primary">Bearbeiten</a>
                                            <a href="<?= base_url('verwaltung/trucker/loeschen/' . $t['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Trucker wirklich löschen?')">Löschen</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>