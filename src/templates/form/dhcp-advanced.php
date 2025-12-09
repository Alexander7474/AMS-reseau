<p>Paramètrer le DHCP</p>
<div class="card">
  <form id="networkForm" method="POST">
    <!-- Adresse IP -->
    <div class="form-group">
      <label class="form-label">Range d'adresse de DHCP</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1a" min="0" max="255" value="192" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2a" min="0" max="255" value="168" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3a" min="0" max="255" value="<?php echo $ip3a; ?>" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4a" min="0" max="255" value="<?php echo $ip4a; ?>" required>
      </div>
      <br>
      <p>à</p>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1b" min="0" max="255" value="192" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2b" min="0" max="255" value="168" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3b" min="0" max="255" value="<?php echo $ip3b; ?>" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4b" min="0" max="255" value="<?php echo $ip4b; ?>" required>
      </div>
    </div>
    <!-- Boutons d'action -->
    <div class="button-group">
        <button type="reset" class="btn btn-secondary">Annuler</button>
        <button type="submit" class="btn btn-primary" name="submit_dhcp">Enregistrer</button>
    </div>
  </form>
</div>
