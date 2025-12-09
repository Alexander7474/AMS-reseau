<main class="content">
  <div class="page-header">
      <h1>Configuration des DNS</h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col">
        <p>Paramètres DNS de votre box</p>
        <div class="card">
          <form id="dns-form" method="POST">
            <label class="form-label">Nom du domaine</label>
            <input type="text" class="text-input" name="domain_name">
              <!-- Boutons d'action -->
              <div class="button-group">
                <button type="reset" class="btn btn-secondary">Annuler</button>
                <button type="submit" class="btn btn-primary" name="submit_ip">Enregistrer</button>
              </div>
          </form>
        </div>
      </div>
      <div class="col">
        <p>Configuration DNS actuelle</p>
        <div class="card">
          <p class="config-info">Nom de domaine: TODO</p>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">        
      <p>Ajout d'un nom de domaine</p>
        <div class="card">
          <form id="add-dns-form" method="POST">
            <label class="form-label">Ajouter un sous domaine</label>
            <input type="text" class="text-input" name="domain_name">
              <!-- Boutons d'action -->
              <div class="button-group">
                <button type="reset" class="btn btn-secondary">Annuler</button>
                <button type="submit" class="btn btn-primary" name="submit_ip">Ajouter</button>
              </div>
          </form>
        </div>
      </div>
      <div class="col">
        <p>Liste des noms de domaines ajouté(s)</p>
        <div class="card">
          <p class="config-info">TODO</p>
        </div>
      </div>
    </div>
  </div>    
</main>
