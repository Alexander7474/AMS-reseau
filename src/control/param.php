<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_param = true;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2'])){
  if($_POST['password'] == $_POST['password2']){
    $user = new User($_POST['username'], $_POST['password']);
    $user->save();
  }else{
    $errorChangeLogin = "Les deux mots de passe doivent être identique";
  }
}

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/param.php");
include($racine_path."src/templates/footer.php");
?>
