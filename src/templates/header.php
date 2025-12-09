<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box réseau</title>
    <link href="<?php echo $racine_path; ?>src/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo $racine_path; ?>src/css/bootstrap.bundle.min.js"></script>
<?php if(isset($_SESSION['advanced_mode']) && $_SESSION['advanced_mode'] == true){
?> 
  <link rel="stylesheet" href="<?php echo $racine_path; ?>src/css/styles-advanced.css">
<?php
}else {
?> 
  <link rel="stylesheet" href="<?php echo $racine_path; ?>src/css/styles.css">
<?php
}?>
</head>
<body>
