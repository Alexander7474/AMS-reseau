<main class="content">
  <div class="page-header">
      <h1>Configuration internet et sécurité</h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col">
        <p>Test de débit</p>
        <div class="card" id="debit">
          <?php include($racine_path."src/templates/form/debit.php");?>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">
        <?php include($racine_path."src/templates/form/internet-access.php");?>
      </div>
      <div class="col">
        <p>Configuration internet actuelle</p>
        <div class="card">
          <div class="col d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              Accès à internet (ipv4 forward)
            </p>

            <div style="
              width: 15px;
              height: 15px;
              background: <?php echo ($ipForward == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
<?php
if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
?> 
      <div class="col">  
<?php
  include($racine_path."src/templates/form/port-forward.php");
  ?>
      </div>
      <div class="col">
        <p>Liste des redirections</p>
        <div class="card">
          <?php 
        foreach ($forwards as $sub){
          if(!strpos($sub, "/")) { echo "Aucune"; break; }
          $port = explode('/', $sub)[0];
          $ip_dest = explode('/', $sub)[1];
          $port_dest = explode('/', $sub)[2];
          echo '
          <form method="POST">
            <div class="button-group">
              <p class="config-info">'.$port.' -> '.$ip_dest.':'.$port_dest.'</p>
              <input type="text" value="'.$port.'" name="port" hidden>
              <button type="submit" class="mybtn mybtn-secondary" name="rm_forward">Supprimer</button>
            </div>
          </form>
          ';
        }
        ?>          
       </div>
      </div>
<?php 
}
?>
    </div>
    <br>
    <div class="row">
<?php
if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
?> 
      <div class="col">  
<?php
  include($racine_path."src/templates/form/block-ip.php");
  ?>
      </div>
      <div class="col">
        <p>Liste des ip bloquées</p>
        <div class="card">
          TODO         
       </div>
      </div>
<?php 
}
?>
    </div>
  </div>    
</main>
