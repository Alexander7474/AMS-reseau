<main class="content">
        <div class="page-header d-flex align-items-center justify-content-between">
            <h1>Configuration Réseau</h1>
            <?php include($racine_path."src/templates/form/reset.php"); ?>
        </div>
 
       <div class="container">
          <div class="row">
<?php
if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
  ?><div class="col"><?php
  include($racine_path."src/templates/form/ip-advanced.php"); 
  ?></div><?php
}
?>            

            <div class="col">
              <p>Configuration IP actuelle</p>
              <div class="card">
              <p class="config-info">Adresse de la box: <?php echo $ip;?></p>
                <p class="config-info">Adresse du réseau: <?php echo $network;?></p>
                <p class="config-info">Masque de sous-réseau: <?php echo $mask;?></p>
              </div>
            </div>
          </div>

          <br>
          <div class ="row">
            <div class="col">
<?php
if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
  include($racine_path."src/templates/form/dhcp-advanced.php"); 
}else{
  include($racine_path."src/templates/form/dhcp.php"); 
}
?>
            </div>
            <div class="col">
              <p>Configuration DHCP actuelle</p>
              <div class="card">
                <p class="config-info">Nombre d'adresse disponnible: <?php echo $totalAddr;?></p>
                <p class="config-info">Plage d'adresse: <?php echo $rangea;?> - <?php echo $rangeb;?></p>
                <div class="col d-flex align-items-center gap-2">
                  <p class="config-info mb-0">
                    Détection de conflit d'adresse
                  </p>

                  <div style="
                    width: 15px;
                    height: 15px;
                    background: <?php echo ($conflictDetection == "1") ? 'green' : 'red'; ?>;
                    border-radius: 50%;
                  "></div>
                </div>
              </div>
            </div>
          </div>
        </div>

    </main>
