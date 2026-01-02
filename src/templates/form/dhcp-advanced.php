<p>Paramètrer le DHCP</p>
<div class="card">
  <form method="POST">
    <!-- Première adresse IP -->
    <div class="form-group" id="dhcp-start">
      <label class="form-label">Plage d'adresse de DHCP</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="range1a" min="0" max="255" value="192" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="range2a" min="0" max="255" value="168" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="range3a" min="0" max="255" value="<?php echo $range3a; ?>" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="range4a" min="0" max="255" value="<?php echo $range4a; ?>" required>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("Première addresse disponnible de la plage dhcp. ", "dhcp-start", "https://fr.wikipedia.org/wiki/Dynamic_Host_Configuration_Protocol")'>?</button>
      </div>
    </div>
    <p>à</p>
    <!-- Dernière adresse IP -->
    <div class="form-group" id="dhcp-end">
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="range1b" min="0" max="255" value="192" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="range2b" min="0" max="255" value="168" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="range3b" min="0" max="255" value="<?php echo $range3b; ?>" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="range4b" min="0" max="255" value="<?php echo $range4b; ?>" required>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("Dernière addresse disponnible de la plage dhcp. ", "dhcp-end", "https://fr.wikipedia.org/wiki/Dynamic_Host_Configuration_Protocol")'>?</button>
      </div>
    </div>

    <div class="form-group" id="dhcp-conflict">
      <div class="form-check">
        <input class="form-check-input mt-2" type="checkbox" id="flexCheckDefault" value="ok" name="conflict_detection" <?php if ($conflictDetection == "1") { echo "checked"; }?>>
        <label class="form-check-label" for="flexCheckDefault">
          Détection de conflit d'addresse
        </label>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("Le server DHCP va ping une addresse avant de l&#39;attribuer pour vérifier qu&#39;elle est disponnible. Cette option peut avoir un impact sur les performances. ", "dhcp-conflict", "https://fr.wikipedia.org/wiki/Dynamic_Host_Configuration_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
        <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
        <button type="submit" class="mybtn mybtn-primary" name="submit_dhcp_advanced">Enregistrer</button>
    </div>
  </form>
</div>
