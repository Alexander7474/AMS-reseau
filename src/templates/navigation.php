<nav class="sidebar d-flex flex-column">
    <div class="logo">Box Admin</div>
    <ul class="nav-menu">
    <li>
    <a href="<?php echo $racine_path; ?>src/control/network.php" <?php if(isset($page_reseau)) { echo 'class="active"';}?>>Réseau</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/dns.php" <?php if(isset($page_dns)) { echo 'class="active"';}?>>Nom de domaine</a>
    </li>
        <li><a href="#">Hébergement Web</a></li>
        <li><a href="#">Sécurité</a></li>
        <li><a href="#">Paramètres</a></li>
    </ul>
      <!-- Mode avancé -->
    
      <div class="mt-auto p-3 border-top container">
      <div class="row">
      <div class="col-auto">
      <a href="advanced_mode.php" class="<?php 
      if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
        echo 'btn btn-primary';
      }else{
        echo 'btn btn-secondary';
      }
      ?>">Mode avancé</a>
      </div>
      <div class="col d-flex align-items-center">
      <div style="width: 20px; height: 20px; background:
      <?php
      if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
        echo 'green';
      }else{
        echo 'red';
      }?>; border-radius: 50%;"></div>
      </div>
</div>

</nav>
