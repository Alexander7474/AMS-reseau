<p class="mx-auto">Changer le nom d'utilisateur</p>
<div class="card mx-auto">
  <form id="add-dns-form" method="POST">

    <div class="form-group p-1">
      <label class="form-label">Nouveau nom d'utilisateur</label>
      <input type="text" class="text-input w-100" name="username" required>
    </div>
 
    <div class="form-group p-1">
      <label class="form-label">Nouveau mot de passe</label>
      <input type="password" class="text-input w-100" name="password" required>
    </div>

    <div class="form-group p-1">
      <label class="form-label">Répéter le mot de passe</label>
      <input type="password" class="text-input w-100" name="password2" required>
    </div>

    <div class="button-group p-1">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit">Enregistrer</button>
    </div>
<?php if(isset($errorChangeLogin)){ $error = $errorChangeLogin; include $racine_path."src/templates/error.php"; }?>
  </form>
</div>
