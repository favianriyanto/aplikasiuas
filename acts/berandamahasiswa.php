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
        global $app;
        
        if (!isset($_SESSION['user'])) {
            header("Location:".$app->website);
        }
        ?>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">ISI KRS BERAKHIR PADA</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><p id="timer"></p></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <?php
    }

    public function login() {
        global $app;
?>
    

<?php
    }
}
?>