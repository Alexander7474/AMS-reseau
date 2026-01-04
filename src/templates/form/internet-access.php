<p>Accès à internet</p>
<div class="card">
  <form method="POST">
    <div class="form-group" id="ip-forward-form">
      <div class="form-check">
        <input class="form-check-input mt-2" type="checkbox" id="flexCheckDefault" value="ok" name="ip_forward" <?php if ($ipForward == "1") { echo "checked"; }?>>
        <label class="form-check-label" for="flexCheckDefault">
          Ipv4 forward
        </label>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("Activer Ipv4 forward vous permettra d&#39;accéder à internet. ", "ip-forward-form", "https://fr.wikipedia.org/wiki/Dynamic_Host_Configuration_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
        <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
        <button type="submit" class="mybtn mybtn-primary" name="submit_ip_forward">Enregistrer</button>
    </div>
  </form>
</div>
