<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Kalkulation</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Eingabeformular -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sendungsdaten</h3>
                    </div>
                    <div class="card-body">

                        <!-- Allgemeine Daten -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label required">Trucker</label>
                                <select name="trucker_id" id="trucker_id" class="form-select">
                                    <?php foreach ($trucker as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">PLZ (2-stellig)</label>
                                <input type="text" id="plz" class="form-control" maxlength="2" placeholder="z.B. 38" value="38">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label required">Richtung</label>
                                <select id="richtung" class="form-select">
                                    <option value="distribution">Distribution</option>
                                    <option value="beschaffung">Beschaffung</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Zusatzprodukt</label>
                                <select id="zusatzprodukt_id" class="form-select">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dieselzuschlag (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" id="dieselzuschlag" class="form-control" value="0.00" min="0">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Positionen -->
                        <h4 class="mb-3">Positionen</h4>
                        <div class="table-responsive">
                            <table class="table table-sm" id="positionen-table">
                                <thead>
                                    <tr>
                                        <th>Pos.</th>
                                        <th>Anzahl</th>
                                        <th>Verpackungsart</th>
                                        <th>Gewicht (kg)</th>
                                        <th>Länge (cm)</th>
                                        <th>Breite (cm)</th>
                                        <th>Höhe (cm)</th>
                                        <th>Lademittel</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="positionen-body">
                                    <!-- Zeilen werden per JS eingefügt -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="position-hinzufuegen">
                            + Position hinzufügen
                        </button>

                        <div class="mt-4">
                            <button type="button" class="btn btn-primary btn-lg w-100" id="berechnen-btn">
                                Frachtpreis berechnen
                            </button>
                        </div>

                        <div class="mt-2">
                            <button type="button" class="btn btn-outline-primary w-100" id="vergleichen-btn">
                                Alle Trucker vergleichen
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Ergebnis -->
            <div class="col-md-5">
                <div class="card" id="ergebnis-card" style="display:none;">
                    <div class="card-header">
                        <h3 class="card-title">Ergebnis</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="subheader mb-1">Trucker / Richtung / PLZ</div>
                            <div class="fw-bold" id="res-trucker-info"></div>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Tatsächliches Gewicht</div>
                            <div class="col-6 text-end fw-bold" id="res-gesamt-kg"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Volumen (CBM)</div>
                            <div class="col-6 text-end" id="res-cbm"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Gewicht aus CBM</div>
                            <div class="col-6 text-end" id="res-gew-cbm"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Lademeter (LDM)</div>
                            <div class="col-6 text-end" id="res-ldm"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Gewicht aus LDM</div>
                            <div class="col-6 text-end" id="res-gew-ldm"></div>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Gewichtsklasse</div>
                            <div class="col-6 text-end" id="res-gewichtsklasse"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Abrechnungsgewicht</div>
                            <div class="col-6 text-end fw-bold text-primary" id="res-abrechnungsgewicht"></div>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Frachtpreis</div>
                            <div class="col-6 text-end" id="res-frachtpreis"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Zusatzprodukt</div>
                            <div class="col-6 text-end" id="res-aufschlag"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Lademittelgebühren</div>
                            <div class="col-6 text-end" id="res-lademittel"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Dieselzuschlag (<span id="res-diesel-pct"></span>%)</div>
                            <div class="col-6 text-end" id="res-diesel-betrag"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6 fw-bold fs-4">Gesamtpreis</div>
                            <div class="col-6 text-end fw-bold fs-4 text-success" id="res-gesamtpreis"></div>
                        </div>
                    </div>
                    <hr>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button onclick="window.print()" class="btn btn-outline-secondary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"/>
                                        <rect x="7" y="13" width="10" height="8" rx="2"/>
                                    </svg>
                                    Drucken
                                </button>
                            </div>
                        </div>
                </div>

                <div class="card" id="fehler-card" style="display:none;">
                    <div class="card-body">
                        <div class="alert alert-danger mb-0" id="fehler-text"></div>
                    </div>
                </div>
            </div>
            <!-- Vergleich -->
                <div class="card mt-3" id="vergleich-card" style="display:none;">
                    <div class="card-header">
                        <h3 class="card-title">Truckervergleich</h3>
                        <div class="card-options">
                            <small class="text-muted" id="vergleich-info"></small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Trucker</th>
                                    <th class="text-end">Abr.-Gewicht</th>
                                    <th class="text-end">Frachtpreis</th>
                                    <th class="text-end">Lademittel</th>
                                    <th class="text-end">Diesel</th>
                                    <th class="text-end">Gesamt</th>
                                </tr>
                            </thead>
                            <tbody id="vergleich-body">
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>

<script>
// Daten aus PHP
const truckerDaten = <?= json_encode($trucker) ?>;
const zusatzprodukteDaten = <?= json_encode($zusatzprodukte) ?>;
const verpackungsartenDaten = <?= json_encode($verpackungsarten) ?>;

// Zusatzprodukte aktualisieren wenn Trucker wechselt
function updateZusatzprodukte() {
    const truckerId = parseInt(document.getElementById('trucker_id').value);
    const select = document.getElementById('zusatzprodukt_id');
    select.innerHTML = '';
    const produkte = zusatzprodukteDaten[truckerId] || [];
    produkte.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.text = p.bezeichnung + (p.aufschlag > 0 ? ' (+ ' + parseFloat(p.aufschlag).toFixed(2).replace('.', ',') + ' €)' : '');
        select.appendChild(opt);
    });
    updateVerpackungsarten();
}

// Verpackungsarten in allen Zeilen aktualisieren
function updateVerpackungsarten() {
    const truckerId = parseInt(document.getElementById('trucker_id').value);
    const arten = verpackungsartenDaten[truckerId] || [];
    document.querySelectorAll('.verpackungsart-select').forEach(select => {
        const current = select.value;
        select.innerHTML = '';
        arten.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a.bezeichnung;
            opt.text = a.bezeichnung;
            if (a.bezeichnung === current) opt.selected = true;
            select.appendChild(opt);
        });
    });
}

// Neue Positionszeile
let posNr = 0;
function positionHinzufuegen() {
    posNr++;
    const truckerId = parseInt(document.getElementById('trucker_id').value);
    const arten = verpackungsartenDaten[truckerId] || [];

    let optionen = arten.map(a => `<option value="${a.bezeichnung}" data-gebuehr="${a.lademittelgebuehr ?? ''}" data-standard="${a.lademittelgebuehr_standard}">${a.bezeichnung}</option>`).join('');

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center">${posNr}</td>
        <td><input type="number" class="form-control form-control-sm" name="anzahl" value="1" min="1" style="width:60px;"></td>
        <td><select class="form-select form-select-sm verpackungsart-select" name="verpackungsart" onchange="updateLademittel(this)">${optionen}</select></td>
        <td><input type="number" class="form-control form-control-sm" name="gewicht" value="" placeholder="kg" style="width:80px;"></td>
        <td><input type="number" class="form-control form-control-sm" name="laenge" value="" placeholder="cm" style="width:75px;"></td>
        <td><input type="number" class="form-control form-control-sm" name="breite" value="" placeholder="cm" style="width:75px;"></td>
        <td><input type="number" class="form-control form-control-sm" name="hoehe" value="" placeholder="cm" style="width:75px;"></td>
        <td>
            <div class="lademittel-check" style="min-width:140px;"></div>
        </td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">×</button></td>
    `;
    document.getElementById('positionen-body').appendChild(row);

    // Lademittel-Checkbox initialisieren
    const select = row.querySelector('.verpackungsart-select');
    updateLademittel(select);
}

function updateLademittel(select) {
    const row = select.closest('tr');
    const option = select.options[select.selectedIndex];
    const gebuehr = option.getAttribute('data-gebuehr');
    const standard = option.getAttribute('data-standard');
    const container = row.querySelector('.lademittel-check');

    if (gebuehr !== '' && gebuehr !== null && parseFloat(gebuehr) >= 0) {
        const checked = standard === '1' ? 'checked' : '';
        container.innerHTML = `
            <label class="form-check mb-0">
                <input class="form-check-input lademittel-checkbox" type="checkbox" name="lademittel" value="${gebuehr}" ${checked}>
                <span class="form-check-label small">Lademittel<br><strong>${parseFloat(gebuehr).toFixed(2).replace('.', ',')} €/Stk</strong></span>
            </label>
        `;
    } else {
        container.innerHTML = '';
    }
}


// Berechnung
document.getElementById('berechnen-btn').addEventListener('click', function() {
    const truckerId      = document.getElementById('trucker_id').value;
    const plz            = document.getElementById('plz').value.trim();
    const richtung       = document.getElementById('richtung').value;
    const zusatzId       = document.getElementById('zusatzprodukt_id').value;
    const dieselzuschlag = document.getElementById('dieselzuschlag').value;

    if (!plz) {
        alert('Bitte PLZ eingeben!');
        return;
    }

    // Positionen sammeln
 const positionen = [];
    document.querySelectorAll('#positionen-body tr').forEach(row => {
        const checkbox = row.querySelector('.lademittel-checkbox');
        positionen.push({
            anzahl:         row.querySelector('[name=anzahl]').value,
            verpackungsart: row.querySelector('[name=verpackungsart]').value,
            gewicht:        row.querySelector('[name=gewicht]').value || 0,
            laenge:         row.querySelector('[name=laenge]').value || 0,
            breite:         row.querySelector('[name=breite]').value || 0,
            hoehe:          row.querySelector('[name=hoehe]').value || 0,
            lademittel:     (checkbox && checkbox.checked) ? checkbox.value : 0,
        });
    });

    if (positionen.length === 0) {
        alert('Bitte mindestens eine Position eingeben!');
        return;
    }

    // AJAX-Request
    const formData = new FormData();
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    formData.append('trucker_id', truckerId);
    formData.append('plz', plz);
    formData.append('richtung', richtung);
    formData.append('zusatzprodukt_id', zusatzId);
    formData.append('dieselzuschlag', dieselzuschlag);
    positionen.forEach((pos, i) => {
        Object.keys(pos).forEach(key => {
            formData.append(`positionen[${i}][${key}]`, pos[key]);
        });
    });

    fetch('<?= base_url('kalkulation/berechnen') ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('fehler-card').style.display = 'none';
        if (data.success) {
            document.getElementById('ergebnis-card').style.display = 'block';
            document.getElementById('res-trucker-info').textContent = data.trucker + ' / ' + data.richtung + ' / PLZ ' + data.plz;
            document.getElementById('res-gesamt-kg').textContent = formatKg(data.gesamt_kg);
            document.getElementById('res-cbm').textContent = data.gesamt_cbm + ' m³';
            document.getElementById('res-gew-cbm').textContent = formatKg(data.gew_cbm);
            document.getElementById('res-ldm').textContent = data.gesamt_ldm + ' LDM';
            document.getElementById('res-gew-ldm').textContent = formatKg(data.gew_ldm);
            document.getElementById('res-gewichtsklasse').textContent = 'bis ' + data.gewichtsklasse + ' kg';
            document.getElementById('res-abrechnungsgewicht').textContent = formatKg(data.abrechnungsgewicht);
            document.getElementById('res-frachtpreis').textContent = formatEur(data.frachtpreis);
            document.getElementById('res-aufschlag').textContent = formatEur(data.aufschlag);
            document.getElementById('res-lademittel').textContent = formatEur(data.lademittel_gesamt);
            document.getElementById('res-diesel-pct').textContent = data.dieselzuschlag;
            document.getElementById('res-diesel-betrag').textContent = formatEur(data.diesel_betrag);
            document.getElementById('res-gesamtpreis').textContent = formatEur(data.gesamtpreis);
        } else {
            document.getElementById('ergebnis-card').style.display = 'none';
            document.getElementById('fehler-card').style.display = 'block';
            document.getElementById('fehler-text').textContent = data.message || 'Fehler bei der Berechnung.';
        }
    })
    .catch(err => {
        document.getElementById('fehler-card').style.display = 'block';
        document.getElementById('fehler-text').textContent = 'Verbindungsfehler: ' + err;
    });
});

function formatKg(val) {
    return parseFloat(val).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' kg';
}

function formatEur(val) {
    return parseFloat(val).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €';
}

// Vergleich
document.getElementById('vergleichen-btn').addEventListener('click', function() {
    const plz            = document.getElementById('plz').value.trim();
    const richtung       = document.getElementById('richtung').value;
    const dieselzuschlag = document.getElementById('dieselzuschlag').value;

    if (!plz) {
        alert('Bitte PLZ eingeben!');
        return;
    }

 const positionen = [];
    document.querySelectorAll('#positionen-body tr').forEach(row => {
        const checkbox = row.querySelector('.lademittel-checkbox');
        positionen.push({
            anzahl:         row.querySelector('[name=anzahl]').value,
            verpackungsart: row.querySelector('[name=verpackungsart]').value,
            gewicht:        row.querySelector('[name=gewicht]').value || 0,
            laenge:         row.querySelector('[name=laenge]').value || 0,
            breite:         row.querySelector('[name=breite]').value || 0,
            hoehe:          row.querySelector('[name=hoehe]').value || 0,
            lademittel:     (checkbox && checkbox.checked) ? checkbox.value : 0,
        });
    });

    if (positionen.length === 0) {
        alert('Bitte mindestens eine Position eingeben!');
        return;
    }

    const formData = new FormData();
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    formData.append('plz', plz);
    formData.append('richtung', richtung);
    formData.append('dieselzuschlag', dieselzuschlag);
    positionen.forEach((pos, i) => {
        Object.keys(pos).forEach(key => {
            formData.append(`positionen[${i}][${key}]`, pos[key]);
        });
    });

    fetch('<?= base_url('kalkulation/vergleichen') ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('vergleich-card').style.display = 'block';
            document.getElementById('vergleich-info').textContent = 'Richtung: ' + data.richtung + ' / PLZ ' + data.plz;
            const tbody = document.getElementById('vergleich-body');
            tbody.innerHTML = '';
            data.ergebnisse.forEach(e => {
                const tr = document.createElement('tr');
                if (e.guenstigster) tr.classList.add('table-success');
                if (!e.hat_preise) tr.classList.add('table-warning');
                tr.innerHTML = `
                    <td>
                        ${e.trucker}
                        ${e.guenstigster ? '<span class="badge bg-success ms-1">Günstigster</span>' : ''}
                        ${!e.hat_preise ? '<span class="badge bg-warning ms-1">Keine Preise</span>' : ''}
                    </td>
                    <td class="text-end">${formatKg(e.abrechnungsgewicht)}</td>
                    <td class="text-end">${formatEur(e.frachtpreis)}</td>
                    <td class="text-end">${formatEur(e.lademittel)}</td>
                    <td class="text-end">${formatEur(e.diesel_betrag)}</td>
                    <td class="text-end fw-bold">${formatEur(e.gesamtpreis)}</td>
                `;
                tbody.appendChild(tr);
            });
        }
    })
    .catch(err => {
        alert('Fehler: ' + err);
    });
});

// Init
document.getElementById('trucker_id').addEventListener('change', updateZusatzprodukte);
updateZusatzprodukte();
positionHinzufuegen(); // Erste Zeile automatisch hinzufügen
document.getElementById('position-hinzufuegen').addEventListener('click', positionHinzufuegen);
</script>
<?= $this->endSection() ?>