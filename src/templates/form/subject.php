<p>Créer un sujet</p>
<div class="card" style="width:50%;">
  <form id="dns-form" method="POST">

    <div class="form-group">
      <label class="form-label">Pseudo</label>
      <input type="text" class="text-input w-50" name="pseudo" required>
    </div>

    <div class="form-group">
      <label class="form-label">Titre du sujet</label>
      <input type="text" class="text-input w-100" name="title" required>
    </div>

    <div class="form-group">
      <label class="form-label">Contenue du message</label>
      <textarea class="text-input w-100" name="content" required></textarea>
    </div>

    <div class="button-group">
      <button type="reset" class="mybtn mybtn-secondary">Annuler</button>
      <button type="submit" class="mybtn mybtn-primary" name="submit_subject">Créer</button>
    </div>
  </form>
</div>
