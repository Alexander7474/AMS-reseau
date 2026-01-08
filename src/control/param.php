<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
include $racine_path."src/model/Validator.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_param = true;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2'])){
  if($_POST['password'] == $_POST['password2']){
    if(Validator::isSafeString($_POST['username'])){
      $user = new User($_POST['username'], $_POST['password']);
      $user->save();
    }else{
      $errorChangeLogin = "Le nom d'utilisateur est invalide/non sécurisé";
    }
  }else{
    $errorChangeLogin = "Les deux mots de passe doivent être identique";
  }
}

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/param.php");
include($racine_path."src/templates/footer.php");
?>
