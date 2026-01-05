<?php
session_start();

// si utilisateur connecté on repart sur index
if (!empty($_SESSION['authenticated'])) {
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit;
}

$racine_path = "../../";

// TODO -- Donner une utilité au bouton Annuler dans les formulaire

include $racine_path."src/model/User.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])){
  $user = new User($_POST['username'], $_POST['password']);
  if($user->connect()){
    header('Location: /index.php');
    exit;
  }else{
    $errorLogin = "Utilisateur ou mot de passe incorrect.";
  }
}

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/login.php");
include($racine_path."src/templates/footer.php");
