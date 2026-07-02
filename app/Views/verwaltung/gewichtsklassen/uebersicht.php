<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gewichtsklassen</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <?php foreach ($trucker as $t): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= esc($t['name']) ?></h3>
                        </div>
                        <div class="card-body">
                            <a href="<?= base_url('verwaltung/gewichtsklassen/' . $t['id']) ?>" class="btn btn-outline-primary w-100">
                                Gewichtsklassen verwalten
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>