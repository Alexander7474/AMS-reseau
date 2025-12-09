<main class="content">
        <div class="page-header">
            <h1>Configuration Réseau</h1>
        </div>

        <div class="container">
          <div class="row">
            <div class="col">
<?php
if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
  include($racine_path."src/templates/form/ip-advanced.php"); 
}else{
  include($racine_path."src/templates/form/ip.php"); 
}
?>            
            </div>

            <div class="col">
              <p>Configuration IP actuelle</p>
              <div class="card">
                <p class="config-info">Adresse de la box: TODO</p>
                <p class="config-info">Adresse du réseau: TODO</p>
                <p class="config-info">Masque de sous-réseau: TODO</p>
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
                <p class="config-info">Nombre d'adresse disponnible: TODO</p>
                <p class="config-info">Range d'adresse: TODO</p>
              </div>
            </div>
          </div>
        </div>

    </main>
