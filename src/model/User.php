<?php
/**
 * @brief Gère l'uilisateur stocké dans config/user.json, cette classe n'utilise pas de base de données
 */
class User
{
  public $username;
  public $password;
  private static $path = "/var/www/html/src/config/user.json";

  public function __construct($username = "admin", $password = "admin"){
    $this->username = $username;
    $this->password = $password;
  }

  public function displayJson() {
    echo json_encode($this);
  }

  /**
   * @brief valide que l'utilisteur passé en paramètre est le même que this
   *
   * @param $user utilisateur a valider
   */
  public function validate($user){
    if($user->username == $this->username && password_verify($user->password, $this->password)){
      return true; 
    }else{
      return false;
    }
  }

  /**
   * @brief charge l'utilisateur du fichier user.json
   *
   * @param $racine_path racine de l'application
   */
  public static function load(){
    if(!file_exists(User::$path)){
      $user = new static();
      $user->save();
      return $user;
    }else{
      $jsonUser = file_get_contents(User::$path);
      $data = json_decode($jsonUser);
      $user = new static();
      $user->username = $data->username;
      $user->password  = $data->password;
      return $user;
    }
  }

  /**
   * @brief Sauvegarde l'utilisateur this dans user.json 
   */ 
  public function save() {
    $file = fopen(User::$path, "w");
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    fwrite($file, json_encode($this));
    fclose($file);
  }

  /**
   * @brief connect l'utilisateur en paramètre
   */
  public function connect() {
    // chargement de l'ulisateur valide
    $validUser = User::load();

    if($validUser->validate($this)){
      $_SESSION["authenticated"] = true;
      return true;
    }else {
      return false;
    }
  }

  public static function checkIfConnected(){
    if (empty($_SESSION['authenticated'])) {
        header('Location: /src/control/login.php');
        exit;
    }
    return false;
  }
}
?>
