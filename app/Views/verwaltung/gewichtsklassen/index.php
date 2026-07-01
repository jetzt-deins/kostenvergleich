<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= esc($title) ?></h2>
            </div>
            <div class="col-auto ms-auto d-flex gap-2">
                <a href="<?= base_url('verwaltung/gewichtsklassen/neu/' . $trucker['id']) ?>" class="btn btn-primary">
                    + Neue Gewichtsklasse
                </a>
                <a href="<?= base_url('verwaltung/trucker/bearbeiten/' . $trucker['id']) ?>" class="btn btn-outline-secondary">
                    Zurück zum Trucker
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
                            <th>Sortierung</th>
                            <th>Von (kg)</th>
                            <th>Bis (kg)</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($klassen)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Keine Gewichtsklassen vorhanden</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($klassen as $k): ?>
                                <tr>
                                    <td><?= esc($k['sortierung']) ?></td>
                                    <td><?= esc($k['gewicht_von']) ?> kg</td>
                                    <td><?= esc($k['gewicht_bis']) ?> kg</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('verwaltung/gewichtsklassen/bearbeiten/' . $k['id']) ?>" class="btn btn-sm btn-outline-primary">Bearbeiten</a>
                                            <a href="<?= base_url('verwaltung/gewichtsklassen/loeschen/' . $k['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Gewichtsklasse wirklich löschen?')">Löschen</a>
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