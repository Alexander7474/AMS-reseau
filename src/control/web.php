<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_web = true;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
}

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/web.php");
include($racine_path."src/templates/footer.php");
?>
