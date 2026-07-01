<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Preistabellen</h2>
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
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="<?= base_url('verwaltung/preistabellen/anzeigen/' . $t['id'] . '/distribution') ?>" class="btn btn-outline-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12l3 -3l3 3l3 -3l3 3l3 -3l3 3"/>
                                        </svg>
                                        Distribution
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="<?= base_url('verwaltung/preistabellen/anzeigen/' . $t['id'] . '/beschaffung') ?>" class="btn btn-outline-secondary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12l3 3l3 -3l3 3l3 -3l3 3l3 -3"/>
                                        </svg>
                                        Beschaffung
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>