<?php 
session_start();
$racine_path = "../../";
$page_forum = true;

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

//connection à la db du forum
include $racine_path."src/model/Database.php";
include $racine_path."src/model/Message.php";
include $racine_path."src/model/Subject.php";
include $racine_path."src/model/forum-config.php";
include $racine_path."src/model/Validator.php";

$database = new Database($host, $dbname, $user, $pass);
$messageDb = new Message($database->getConnection());
$subjectDb = new Subject($database->getConnection());

// ajout d'un message
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_message']) && isset($_GET['subject_id'])){
  $content = $_POST['content'];
  $pseudo = $_POST['pseudo'];
  $subjectId = $_GET['subject_id'];

  if(Validator::isSafeString($content) && Validator::isSafeString($pseudo)){
    $messageDb->create($content, $pseudo, $subjectId);
  }else{
    $errorForum = "Contenue du message ou pseudo invalide/non sécurisé";
  }
}

// ajout d'un sujet 
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_subject'])){
  $content = $_POST['content'];
  $pseudo = $_POST['pseudo'];
  $title = $_POST['title'];

  if(Validator::isSafeString($content) && Validator::isSafeString($pseudo) && Validator::isSafeString($title)){
    $subjectId = $subjectDb->create($title, $pseudo);
    $messageDb->create($content, $pseudo, $subjectId);
  }else{
    $errorForum = "Contenue du message/pseudo/titre invalide ou non sécurisé";
  }
}
  
include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
if(isset($_GET['subject_id'])){
  $subjectId = $_GET['subject_id'];

  $messages = $messageDb->getBySubject($subjectId);
  include($racine_path."src/templates/forum_messages.php");
}else{
  $subjects = $subjectDb->getAll();
  include($racine_path."src/templates/forum_subjects.php");
}
include($racine_path."src/templates/footer.php");
?>
