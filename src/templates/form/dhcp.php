<p>Paramètrer le DHCP</p>
<div class="card">
  <form id="networkForm" method="POST">
    <!-- Adresse IP -->
    <div class="form-group">
        <label class="form-label">Nombre de machine sur le réseau</label>
        <div class="ip-input-group">
            <input type="number" class="ip-byte" name="dhcp_client" min="1" max="65536" value="1" required>
        </div>
    </div>
    <!-- Boutons d'action -->
    <div class="button-group">
        <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
        <button type="submit" class="mybtn mybtn-primary" name="submit_dhcp">Enregistrer</button>
    </div>
  </form>
</div>
