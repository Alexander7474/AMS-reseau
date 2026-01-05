<p>Blocage d'une ip</p>
<div class="card">
  <form method="POST">
    <div class="form-group" id="ip-block-form">
      <label class="form-label">Adresse IP</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4" min="0" max="255" required>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("L&#39;adresse ip suivante sera bloquée et inaccessible depuis le réseau local. ", "ip-block-form", "https://fr.wikipedia.org/wiki/Internet_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit_blocked_ip">Bloquer</button>
    </div>
  </form>
</div>
