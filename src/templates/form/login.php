<p class="mx-auto" style="width:40%;">Connexion MyBox</p>
<div class="card mx-auto" style="width:40%;">
  <form id="add-dns-form" method="POST">

    <div class="form-group p-1">
      <label class="form-label">Utilisateur</label>
      <input type="text" class="text-input w-100" name="username" required>
    </div>

    <div class="form-group p-1">
      <label class="form-label">Mot de passe</label>
      <input type="password" class="text-input w-100" name="password" required>
    </div>

    <!-- Boutons d'action -->
    <div class="button-group p-1">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit">Connexion</button>
    </div>
  </form>
</div>
