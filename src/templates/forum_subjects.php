<main class="content">
  <div class="page-header">
      <h1>Forum d'entraide</h1>
  </div>

  <div class="container">
<?php
if(isset($subjects)){
  if(count($subjects) <= 0){
    echo '
      <a style="text-decoration: none;" href="?subject_id='.$sub['id'].'">
      <div class="card">
        <h4>Aucun sujet créé</h4>
        <p>Soyez le premier</p>
      </div>
      </a>
      <br>
    ';  
  }

  foreach ($subjects as $sub) {
    echo '
      <a style="text-decoration: none;" href="?subject_id='.$sub['id'].'">
      <div class="card">
        <h4>'.ucfirst($sub['title']).'</h4>
        <p>Créé par: '.$sub['pseudo'].'</p>
      </div>
      </a>
      <br>
    ';  
  }
}
?>
  <br>
  <?php include($racine_path."src/templates/form/subject.php"); ?>
  </div>

</main>

