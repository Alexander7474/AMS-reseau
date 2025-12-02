<nav class="sidebar">
    <div class="logo">Box Admin</div>
    <ul class="nav-menu">
    <li>
    <a href="<?php echo $racine_path; ?>src/control/network.php" <?php if(isset($page_reseau)) { echo 'class="active"';}?>>Réseau</a>
    </li>
    <li>
    <a href="<?php echo $racine_path; ?>src/control/dns.php" <?php if(isset($page_dns)) { echo 'class="active"';}?>>DNS</a>
    </li>
        <li><a href="#">Wi-Fi</a></li>
        <li><a href="#">Sécurité</a></li>
        <li><a href="#">Périphériques</a></li>
        <li><a href="#">Paramètres</a></li>
    </ul>
</nav>
