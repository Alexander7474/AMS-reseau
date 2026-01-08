<p>Ajout d'un nom de domaine</p>
<div class="card">
  <form id="add-dns-form" method="POST">

    <div class="form-group" id="subdomain-form">
      <label class="form-label">Ajouter un sous domaine</label>
      <input type="text" class="text-input" name="subdomain_name" required>
      <button type="button" class="mybtn mybtn-info" onclick='infoBox("Nom du nouveau sous domaine. Le sous-domaine final sera &#39;sous-domaine.nom-zone.ceri.com&#39;. Le nom &#39;box&#39; est réservé por votre routeur. ", "subdomain-form", "https://fr.wikipedia.org/wiki/Domain_Name_System")'>?</button>
    </div>

    <!-- Adresse IP -->
    <div class="form-group" id="subdomain-ip-form">
      <label class="form-label">Adresse IP de la Box</label>
      <div class="ip-input-group">
        <input type="number" class="ip-byte" name="ip1" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip2" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip3" min="0" max="255" required>
        <span class="separator">.</span>
        <input type="number" class="ip-byte" name="ip4" min="0" max="255" required>
        <button type="button" class="mybtn mybtn-info" onclick='infoBox("L&#39;adresse ip suivante sera pointé par le sous-domaine et permettra un accès simple et rapide à la machine. ", "subdomain-ip-form", "https://fr.wikipedia.org/wiki/Internet_Protocol")'>?</button>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit_subdomain">Ajouter</button>
    </div>
<?php if(isset($errorDnsAdd)){ $error = $errorDnsAdd; include $racine_path."src/templates/error.php"; }?>
  </form>
</div>
