<p>Redirection de port</p>
<div class="card">
  <form method="POST">
    <div class="form-group">
      <label class="form-label">Port d'entré > destination</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="port-entry" min="0" max="65535" required>
        <span class="separator"> > </span>
        <input type="number" class="ip-byte" name="port-destination" min="0" max="65535" required>
      </div>
    </div>
    <div class="form-group" id="port-form">
      <label class="form-label">Adresse IP de destination</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4" min="0" max="255" required>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("La redirection de port vous permet d&#39;ouvrir un service de votre réseau local à internet en redirigeant un port de votre box vers le port du service cible. ", "port-form", "https://fr.wikipedia.org/wiki/Internet_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit_port_forward">Créer</button>
    </div>
  </form>
</div>
