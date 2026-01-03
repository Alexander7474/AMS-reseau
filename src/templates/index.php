<main class="content">
  <div class="page-header">
      <h1>MyBox</h1>
  </div>

  <div class="container">
    <div class="row">

      <div class="col">
        <p>Service(s)</p>
        <div class="card">
          <?php 
          foreach ($services as $sv) {
            ?>
            <div class="w-75 mb-3 d-flex align-items-center gap-2">
              <p class="config-info mb-0">
                <?php echo $sv[0]; ?>
              </p>

              <div class="ms-auto" style="
                width: 15px;
                height: 15px;
                border-radius: 50%;
                background: <?php echo ($sv[1] == "1") ? 'green' : 'red'; ?>;
              "></div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>

      <div class="col">
        <p>Appareils connecté(s)</p>
        <div class="card">
          <?php 
          foreach ($hosts as $host){
            $hname = $host[1];
            $hname = preg_replace('/\s+/u', '', $hname);
            $hip = $host[0];
            if($hip == $ip){
              echo '
              <p class="config-info">'.$hip.' (hostname: '.$hname.') MyBox</p>
              ';
            }else{
              echo '
              <p class="config-info">'.$hip.' (hostname: '.$hname.')</p>
              ';
            }
          }
          ?>
        </div>
      </div>

    </div>
  </div>
</main>
