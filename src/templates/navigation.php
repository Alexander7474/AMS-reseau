<nav class="sidebar d-flex flex-column">
    <a href="/"><div class="logo">MyBox</div></a>
    <ul class="nav-menu">
    <li>
    <a href="<?php echo $racine_path; ?>src/control/network.php" <?php if(isset($page_reseau)) { echo 'class="active"';}?>>Réseau</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/dns.php" <?php if(isset($page_dns)) { echo 'class="active"';}?>>Nom de domaine</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/safety.php" <?php if(isset($page_safety)) { echo 'class="active"';}?>>Internet et Sécurité</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/forum.php" <?php if(isset($page_forum)) { echo 'class="active"';}?>>Forum</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/param.php" <?php if(isset($page_param)) { echo 'class="active"';}?>>Paramètres</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/web.php" <?php if(isset($page_web)) { echo 'class="active"';}?>>Hébergement Web</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/disconnect.php">Deconnexion</a>
    </li>
    </ul>
      <!-- Mode avancé -->
    
      <div class="mt-auto p-3 border-top container">
      <div class="row">
      <div class="col-auto">
      <a href="<?php echo $racine_path; ?>src/control/advanced_mode.php" class="<?php 
      if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
        echo 'mybtn mybtn-primary';
      }else{
        echo 'mybtn mybtn-secondary';
      }
      ?>">Mode avancé</a>
      </div>
      <div class="col d-flex align-items-center">
      <div style="width: 15px; height: 15px; background:
      <?php
      if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
        echo 'green';
      }else{
        echo 'red';
      }?>; border-radius: 50%;"></div>
      </div>
</div>

</nav>
