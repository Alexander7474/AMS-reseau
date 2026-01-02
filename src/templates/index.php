<main class="content">
  <div class="page-header">
      <h1>Welcome</h1>
  </div>

  <div class="container">
    <div class="row">

      <div class="col">
        <p>Service(s)</p>
        <div class="card">
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              DHCP
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($dhcp == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              DNS
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($dns == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              Apache2
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($apache == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              Serveur minecraft
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($minecraft == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              Teamspeak3 
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($ts3 == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              Port Forwarding
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($pat == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
          <div class="w-75 mb-3 d-flex align-items-center gap-2">
            <p class="config-info mb-0">
              Firewall
            </p>

            <div class="ms-auto" style="
              width: 15px;
              height: 15px;
              background: <?php echo ($firewall == "1") ? 'green' : 'red'; ?>;
              border-radius: 50%;
            "></div>
          </div>
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
