<?php 
$racine_path = "../../";
$page_dns = true;

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/dns-form.php");
include($racine_path."src/templates/footer.php");

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_dns'])){

}

?>
