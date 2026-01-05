<main class="content">
  <div class="page-header">
      <h1>Configuration des DNS</h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col">
        <?php include($racine_path."src/templates/form/zone-dns.php");?>
      </div>
      <div class="col">
        <p>Configuration DNS actuelle</p>
        <div class="card">
        <p class="config-info">Nom du domaine: <?php echo $zoneDns; ?></p>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">  
        <?php include($racine_path."src/templates/form/add-subdomain.php");?>      
      </div>
      <div class="col">
        <p>Liste des sous domaines ajouté(s)</p>
        <div class="card">
        <?php 
        foreach ($subdomains as $sub){
          $name = explode('/', $sub)[0];
          $ip = explode('/', $sub)[1];
          $name = preg_replace('/\s+/u', '', $name);
          if($name == "box"){
            echo '
            <p class="config-info">'.$name.'.'.$zoneDns.' -> '.$ip.'</p>
            ';
          }else{
            echo '
            <form method="POST">
              <div class="button-group">
                <p class="config-info">'.$name.'.'.$zoneDns.' -> '.$ip.'</p>
                <input type="text" value="'.$name.'" name="subdomain_name" hidden>
                <button type="submit" class="mybtn mybtn-secondary" name="rm_subdomain">Supprimer</button>
              </div>
            </form>
            ';
          }
        }
        ?>
        </div>
      </div>
    </div>
  </div>    
</main>
