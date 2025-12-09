<p>Paramètrer l'IP de votre box</p>
<div class="card">
  <form id="networkForm" method="POST">
    <!-- Adresse IP -->
    <div class="form-group">
      <label class="form-label">Adresse IP de la Box</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1" min="0" max="255" value="192" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2" min="0" max="255" value="168" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3" min="0" max="255" value="<?php echo $ip3; ?>" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4" min="0" max="255" value="<?php echo $ip4; ?>" required>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
      <button type="reset" class="btn btn-secondary">Annuler</button>
      <button type="submit" class="btn btn-primary" name="submit_ip">Enregistrer</button>
    </div>
  </form>
</div>
