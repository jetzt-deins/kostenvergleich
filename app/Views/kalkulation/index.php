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
                            <div class="col-md-12">
                                <label class="form-label">Zusatzprodukt</label>
                                <select id="zusatzprodukt_id" class="form-select">
                                </select>
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
                        <hr>
                        <div class="row">
                            <div class="col-6 fw-bold fs-4">Gesamtpreis</div>
                            <div class="col-6 text-end fw-bold fs-4 text-success" id="res-gesamtpreis"></div>
                        </div>
                    </div>
                </div>

                <div class="card" id="fehler-card" style="display:none;">
                    <div class="card-body">
                        <div class="alert alert-danger mb-0" id="fehler-text"></div>
                    </div>
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

    let optionen = arten.map(a => `<option value="${a.bezeichnung}">${a.bezeichnung}</option>`).join('');

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center">${posNr}</td>
        <td><input type="number" class="form-control form-control-sm" name="anzahl" value="1" min="1" style="width:60px;"></td>
        <td><select class="form-select form-select-sm verpackungsart-select" name="verpackungsart">${optionen}</select></td>
        <td><input type="number" class="form-control form-control-sm" name="gewicht" value="" placeholder="kg" style="width:80px;"></td>
        <td><input type="number" class="form-control form-control-sm" name="laenge" value="" placeholder="cm" style="width:75px;"></td>
        <td><input type="number" class="form-control form-control-sm" name="breite" value="" placeholder="cm" style="width:75px;"></td>
        <td><input type="number" class="form-control form-control-sm" name="hoehe" value="" placeholder="cm" style="width:75px;"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">×</button></td>
    `;
    document.getElementById('positionen-body').appendChild(row);
}

// Berechnung
document.getElementById('berechnen-btn').addEventListener('click', function() {
    const truckerId = document.getElementById('trucker_id').value;
    const plz       = document.getElementById('plz').value.trim();
    const richtung  = document.getElementById('richtung').value;
    const zusatzId  = document.getElementById('zusatzprodukt_id').value;

    if (!plz) {
        alert('Bitte PLZ eingeben!');
        return;
    }

    // Positionen sammeln
    const positionen = [];
    document.querySelectorAll('#positionen-body tr').forEach(row => {
        positionen.push({
            anzahl:         row.querySelector('[name=anzahl]').value,
            verpackungsart: row.querySelector('[name=verpackungsart]').value,
            gewicht:        row.querySelector('[name=gewicht]').value || 0,
            laenge:         row.querySelector('[name=laenge]').value || 0,
            breite:         row.querySelector('[name=breite]').value || 0,
            hoehe:          row.querySelector('[name=hoehe]').value || 0,
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

// Init
document.getElementById('trucker_id').addEventListener('change', updateZusatzprodukte);
updateZusatzprodukte();
positionHinzufuegen(); // Erste Zeile automatisch hinzufügen
document.getElementById('position-hinzufuegen').addEventListener('click', positionHinzufuegen);
</script>
<?= $this->endSection() ?>