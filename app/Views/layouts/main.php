<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?= $title ?? 'Kostenvergleich' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/tabler.min.css') ?>"/>
</head>
<body class="antialiased">
    <div class="wrapper">

        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-dark sticky-top d-print-none" style="background-color: #206bc4;">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link <?= (current_url() == base_url('/')) ? 'active' : '' ?>" href="<?= base_url('/') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="5 12 3 12 12 3 21 12 19 12"/>
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                                    </svg>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos(current_url(), 'kalkulation') !== false) ? 'active' : '' ?>" href="<?= base_url('kalkulation') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                                        <line x1="8" y1="7" x2="16" y2="7"/>
                                        <line x1="8" y1="11" x2="16" y2="11"/>
                                        <line x1="8" y1="15" x2="12" y2="15"/>
                                    </svg>
                                </span>
                                <span class="nav-link-title">Kalkulation</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= (strpos(current_url(), 'verwaltung') !== false) ? 'active' : '' ?>" href="#" data-bs-toggle="dropdown">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06 .06a2 2 0 0 1 -2.83 2.83l-.06 -.06a1.65 1.65 0 0 0 -1.82 -.33a1.65 1.65 0 0 0 -1 1.51v.17a2 2 0 0 1 -4 0v-.09a1.65 1.65 0 0 0 -1 -1.51a1.65 1.65 0 0 0 -1.82 .33l-.06 .06a2 2 0 0 1 -2.83 -2.83l.06 -.06a1.65 1.65 0 0 0 .33 -1.82a1.65 1.65 0 0 0 -1.51 -1h-.17a2 2 0 0 1 0 -4h.09a1.65 1.65 0 0 0 1.51 -1a1.65 1.65 0 0 0 -.33 -1.82l-.06 -.06a2 2 0 0 1 2.83 -2.83l.06 .06a1.65 1.65 0 0 0 1.82 .33h.09a1.65 1.65 0 0 0 1 -1.51v-.17a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82 -.33l.06 -.06a2 2 0 0 1 2.83 2.83l-.06 .06a1.65 1.65 0 0 0 -.33 1.82v.09a1.65 1.65 0 0 0 1.51 1h.17a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0 -1.51 1z"/>
                                    </svg>
                                </span>
                                <span class="nav-link-title">Verwaltung</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('verwaltung/trucker') ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="7" r="4"/>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                    Trucker
                                </a>
                                <a class="dropdown-item" href="<?= base_url('verwaltung/preistabellen') ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                                        <line x1="8" y1="7" x2="16" y2="7"/>
                                        <line x1="8" y1="11" x2="16" y2="11"/>
                                        <line x1="8" y1="15" x2="12" y2="15"/>
                                    </svg>
                                    Preistabellen
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url('verwaltung/gewichtsklassen') ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="3" y1="12" x2="21" y2="12"/>
                                        <line x1="3" y1="6" x2="21" y2="6"/>
                                        <line x1="3" y1="18" x2="21" y2="18"/>
                                    </svg>
                                    Gewichtsklassen
                                </a>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="page-wrapper">
            <div class="container-xl">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible mt-3" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible mt-3" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
            <?= $this->renderSection('content') ?>
        </div>

    </div>
    <script src="<?= base_url('assets/js/tabler.min.js') ?>"></script>
    <style>
@media print {
    .navbar, .page-header, #berechnen-btn, #position-hinzufuegen,
    .btn-outline-secondary, .col-md-7 { display: none !important; }
    .col-md-5 { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
    #ergebnis-card { display: block !important; box-shadow: none !important; border: 1px solid #ccc; }
}
</style>
</body>
</html>