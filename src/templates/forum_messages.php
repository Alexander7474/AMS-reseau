<main class="content">
  <div class="page-header">
      <h1>Forum d'entraide</h1>
  </div>

  <div class="container">
    <div class="card">
<?php
if(isset($messages)){
  foreach ($messages as $m) {
    echo '
<div class="message p-2 my-2" style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
  <div class="d-flex justify-content-between mb-1">
    <strong class="text-primary">'.$m['pseudo'].'</strong>
    <small class="text-muted">'.$m['date'].'</small>
  </div>
  '. nl2br(htmlspecialchars($m['content'])) . '
</div>
    ';  
  }
}
?>
    </div>

    <br>
    <?php include($racine_path."src/templates/form/message.php"); ?>
  </div>
</main>

