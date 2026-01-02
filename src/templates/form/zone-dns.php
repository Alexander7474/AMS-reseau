<p>Paramètres DNS de votre box</p>
<div class="card">
  <form id="dns-form" method="POST">

    <div class="form-group" id="zone-dns-form">
      <label class="form-label">Nom du domaine</label>
      <input type="text" class="text-input" name="domain_name" required>
      <button type="button" class="mybtn mybtn-info" onclick='infoBox("Nom de votre zone DNS. Votre nom de domaine sera ensuite &#39;nom.ceri.com&#39;. ", "zone-dns-form", "https://fr.wikipedia.org/wiki/Domain_Name_System")'>?</button>
        <div class="mt-2 mb-2 alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Attention!</strong> Changer la zone dns supprimera les sous-domaines.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>
      </div>

      <div class="button-group">
        <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
        <button type="submit" class="mybtn mybtn-primary" name="submit_zone_dns">Enregistrer</button>
      </div>
  </form>
</div>
