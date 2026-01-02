<!-- TODO -- formulaire inutilisé -->
<p>Paramètrer l'IP de votre box</p>
<div class="card">
  <form id="networkForm" method="POST">
    <!-- Adresse IP -->
    <div class="form-group" id="ip-form">
      <label class="form-label">Adresse IP de la Box</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1" min="0" max="255" value="192" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2" min="0" max="255" value="168" readonly>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3" min="0" max="255" value="<?php echo $ip3; ?>" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4" min="0" max="255" value="<?php echo $ip4; ?>" required>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("L&#39;adresse ip de votre box lui permet de communiquer avec les autres appareils de votre réseau. ", "ip-form", "https://fr.wikipedia.org/wiki/Internet_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit_ip">Enregistrer</button>
    </div>
  </form>
</div>
