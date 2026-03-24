<main class="content">
  <div class="page-header">
      <h1>Paramètres MyBox</h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col">
        <p>Sauvegarde(s) configuration</p>
        <div class="card">
        <form method="POST">
        <button name="save_config" type="submit" class="mb-3 mybtn mybtn-primary">Sauvegarder la configuration actuelle</button>
        </form>
        <div class="list-group">

          <?php foreach ($saves as $s): ?>
          <div class="list-group-item p-0">

            <div class="list-group-item d-flex justify-content-between align-items-center">
              <p class="config-info"></p>
              <div class="d-flex align-items-center gap-2 me-auto">
              <button class="btn btn-outline-secondary"
                      data-bs-toggle="collapse"
                      data-bs-target="#infos-<?= $s["name"] ?>">
                &#9660; <?= htmlspecialchars(basename($s["name"])) ?>
              </button>
              </div>

              <div class="d-flex gap-2">
                <form method="POST">
                <input type="text" value="<?= htmlspecialchars(basename($s["name"])) ?>" name="saved_config" hidden>
                <button name="load_config" type="submit" class="mybtn mybtn-primary">Charger</button>
                <button name="rm_config" type="submit" class="mybtn mybtn-secondary">Supprimer</button>
                </form>
               </div>
            </div>

          <div class="collapse" id="infos-<?= $s["name"] ?>">
            <div class="p-3 border-top bg-light">
              <ul class="mb-0 list-unstyled">
                <?php foreach ($s["dangers"] as $d): ?>
                        <li class="text-danger">✖ <?= htmlspecialchars($d) ?></li>
                <?php endforeach; ?>
                <?php foreach ($s["warnings"] as $w): ?>
                        <li class="text-warning">⚠ <?= htmlspecialchars($w) ?></li>
                <?php endforeach; ?>
                <?php foreach ($s["infos"] as $i): ?>
                        <li class="text-info">ℹ <?= htmlspecialchars($i) ?></li>
                <?php endforeach; 
                if(count($s["warnings"]) == 0 && count($s["dangers"]) == 0){
                        echo '<li class="text-success">✔ Cette configurations correspond à vos besoins</li>';
                }
                ?>
              </ul>
            </div>
          </div>

          </div>
          <?php endforeach; ?>

        </div>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">
        <?php include($racine_path."src/templates/form/change-login.php");?>
      </div>
      <div class="col">
      </div>
    </div>
  </div>    
</main>
