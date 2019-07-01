<?php
global $app;

if (!$app) {
   header("Location:../index.php");
}

class ControllerBerandaMahasiswa extends Controller {
    public function __construct() {
        
    }

    public function index() {
        $view = new ViewBerandaMahasiswa();
        $view->login();
    }

    public function dashboard() {
        $view = new ViewBerandaMahasiswa();
        $view->dashboard();
    }
}

class ModelBerandaMahasiswa extends Model {

}

class ViewBerandaMahasiswa extends View {
    public function dashboard() {
        global $app;
        
        if (!isset($_SESSION['user'])) {
            header("Location:".$app->website);
        }
        echo "<h1>Semangat yang lagi ngerjain ini website :*</h1>";
    }

    public function login() {
        global $app;
?>
    

<?php
    }
}
?>