<p>Activer dhcp</p>
<div class="card">
  <form method="POST">
    <div class="form-group" id="toggle-dhcp">
      <div class="form-check">
        <input class="form-check-input mt-2" type="checkbox" id="flexCheckDefault" value="ok" name="dhcp" <?php if ($dhcpStatus == "1") { echo "checked"; }?>>
        <label class="form-check-label" for="flexCheckDefault">
          DHCP
        </label>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("DCHP permet de donner des addresses ip au machine de votre réseau. ", "toggle-dhcp", "https://fr.wikipedia.org/wiki/Dynamic_Host_Configuration_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
        <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
        <button type="submit" class="mybtn mybtn-primary" name="submit_toggle_dhcp">Enregistrer</button>
    </div>
  </form>
</div>
