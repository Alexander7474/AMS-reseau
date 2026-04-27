    <div class="page-header">
        <h1><i class="bi bi-shield-lock me-2" style="color:var(--primary)"></i>Contrôle d'accès</h1>
        <p>
            Planifiez les plages de blocage Internet par appareil.
            <span class="badge" style="background:#d4edda;color:#155724">Vert</span> = autorisé &nbsp;
            <span class="badge" style="background:#f8d7da;color:#721c24">Rouge</span> = bloqué.
        </p>
    </div>

    <div class="card">

        <?php
        /*
         * Variables injectées par apply_filter.php :
         *   $filter_data      — tableau PHP issu de filter.json
         *   $hosts_by_mac     — ["mac" => ["name" => ..., "addr" => ...]]
         *   $macs_filter_only — MACs avec filtre mais absentes de hosts.json
         *   $form_message     — message de retour après POST
         *   $form_success     — bool
         */

        $days       = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
        $days_short = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];

        function mac_has_filter($mac, $filter_data) {
            foreach ($filter_data as $day => $hours) {
                foreach ($hours as $hour => $macs) {
                    if (in_array($mac, array_map('strtolower', $macs))) return true;
                }
            }
            return false;
        }
        ?>

        <div class="form-group">
            <label class="form-label" for="hostSelect">
                <i class="bi bi-laptop me-1"></i>Appareil
            </label>
            <select class="text-input" id="hostSelect" style="width:420px;">

                <?php if (!empty($hosts_by_mac)): ?>
                <optgroup label="En ligne (détectés par le scan)">
                    <?php foreach ($hosts_by_mac as $mac => $info):
                        $has_filter = mac_has_filter($mac, $filter_data);
                        $label = htmlspecialchars($info['name']);
                        if ($info['name'] !== $info['addr'] && $info['addr'] !== '') {
                            $label .= ' — ' . htmlspecialchars($info['addr']);
                        }
                        $label .= ' (' . htmlspecialchars($mac) . ')';
                        if ($has_filter) $label .= ' ★';
                    ?>
                    <option value="<?= htmlspecialchars($mac) ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>

                <?php if (!empty($macs_filter_only)): ?>
                <optgroup label="Hors ligne (filtre actif)">
                    <?php foreach ($macs_filter_only as $mac): ?>
                    <option value="<?= htmlspecialchars($mac) ?>">
                        <?= htmlspecialchars($mac) ?> ★
                    </option>
                    <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>

                <?php if (empty($hosts_by_mac) && empty($macs_filter_only)): ?>
                <option value="">— Aucun appareil trouvé —</option>
                <?php endif; ?>

            </select>
            <small style="color:#7f8c8d; margin-left:8px;">★ = filtre actif sur au moins un créneau</small>
        </div>

        <div id="hostInfo" class="mb-3" style="font-size:13px; color:#7f8c8d; min-height:20px;"></div>

        <script id="filterData" type="application/json">
            <?= json_encode($filter_data, JSON_UNESCAPED_UNICODE) ?>
        </script>
        <script id="hostsData" type="application/json">
            <?= json_encode($hosts_by_mac, JSON_UNESCAPED_UNICODE) ?>
        </script>

        <form method="POST" action="safety.php" id="filterForm">

            <input type="hidden" name="selected_mac" id="selectedMac" value="">

            <div class="table-responsive">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th class="hour-col"></th>
                            <?php foreach ($days_short as $d): ?>
                            <th><?= $d ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($h = 0; $h <= 23; $h++): ?>
                        <tr>
                            <td class="hour-label"><?= sprintf('%02d', $h) ?>h</td>
                            <?php foreach ($days as $day): ?>
                            <td class="slot-cell">
                                <input
                                    type="checkbox"
                                    id="slot_<?= $day ?>_<?= $h ?>"
                                    name="slots[<?= $day ?>][<?= $h ?>]"
                                    value="1"
                                >
                                <label
                                    for="slot_<?= $day ?>_<?= $h ?>"
                                    title="<?= ucfirst($day) ?> <?= sprintf('%02d', $h) ?>h"
                                ></label>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <div class="button-group">
                <button type="submit" class="mybtn mybtn-primary">
                    <i class="bi bi-check2-circle me-2"></i>Appliquer
                </button>
                <button type="button" class="mybtn mybtn-secondary" id="btnReset">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser
                </button>
                <button type="button" class="mybtn mybtn-secondary" id="btnClear">
                    <i class="bi bi-x-circle me-1"></i>Tout effacer
                </button>
            </div>

            <?php if (!empty($form_message)): ?>
            <div class="alert <?= $form_success ? 'alert-success' : 'alert-danger' ?> mt-3" role="alert">
                <i class="bi <?= $form_success ? 'bi-check-circle' : 'bi-exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($form_message) ?>
            </div>
            <?php endif; ?>

        </form>
    </div>

<script>
(function () {
    var filterData = JSON.parse(document.getElementById('filterData').textContent || '{}');
    var hostsData  = JSON.parse(document.getElementById('hostsData').textContent  || '{}');
    var hostSelect  = document.getElementById('hostSelect');
    var selectedMac = document.getElementById('selectedMac');
    var hostInfo    = document.getElementById('hostInfo');
    var days = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];

    /* ── Charge la grille pour une MAC ─────────────────── */
    function loadGrid(mac) {
        for (var d = 0; d < days.length; d++) {
            for (var h = 0; h <= 23; h++) {
                var cb      = document.getElementById('slot_' + days[d] + '_' + h);
                var macList = (filterData[days[d]] && filterData[days[d]][h]) ? filterData[days[d]][h] : [];
                cb.checked  = macList.indexOf(mac) !== -1;
            }
        }
    }

    /* ── Met à jour le bandeau d'info de l'hôte ────────── */
    function updateHostInfo(mac) {
        var info = hostsData[mac];
        if (info) {
            hostInfo.innerHTML =
                '<span class="badge-online"></span>'
                + '<strong>' + info.name + '</strong>'
                + (info.addr ? ' &nbsp;—&nbsp; ' + info.addr : '')
                + ' &nbsp;—&nbsp; <code>' + mac + '</code>';
        } else {
            hostInfo.innerHTML =
                '<span class="badge-offline"></span>'
                + '<code>' + mac + '</code>'
                + ' &nbsp;<span style="color:#adb5bd">(hors ligne)</span>';
        }
    }

    /* ── Événements ─────────────────────────────────────── */
    hostSelect.addEventListener('change', function () {
        loadGrid(this.value);
        updateHostInfo(this.value);
    });

    document.getElementById('btnReset').addEventListener('click', function () {
        loadGrid(hostSelect.value);
    });

    document.getElementById('btnClear').addEventListener('click', function () {
        for (var d = 0; d < days.length; d++) {
            for (var h = 0; h <= 23; h++) {
                document.getElementById('slot_' + days[d] + '_' + h).checked = false;
            }
        }
    });

    document.getElementById('filterForm').addEventListener('submit', function () {
        selectedMac.value = hostSelect.value;
    });

    /* ── Init ───────────────────────────────────────────── */
    if (hostSelect.value) {
        loadGrid(hostSelect.value);
        updateHostInfo(hostSelect.value);
    }
})();
</script>
