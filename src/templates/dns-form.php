<main class="content">
        <div class="page-header">
            <h1>Configuration des DNS</h1>
        </div>

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

        <br>
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
    </main>
