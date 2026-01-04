<main class="content">
  <div class="page-header">
      <h1>Configuration internet et sécurité</h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col">
        <p>Test de débit</p>
        <div class="card" id="test-debit">
          <?php include($racine_path."src/templates/form/debit.php");?>
          <?php if(isset($upSpeed)){ echo $upSpeed;}?>
          <?php if(isset($dlSpeed)){ echo $dlSpeed;}?>
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
      <div class="col">  
        <?php include($racine_path."src/templates/form/block-ip.php");?>      
      </div>
      <div class="col">
        <p>Liste des ip bloquées</p>
        <div class="card">
          TODO         
       </div>
      </div>
    </div>
  </div>    
</main>
