<?php
global $app;

if (!$app) {
   header("Location:../index.php");
}

class ControllerBeranda extends Controller {
    public function __construct() {
        
    }

    public function index() {
        $view = new ViewBeranda();
        $view->login();
    }

    public function dashboard() {
        $view = new ViewBeranda();
        $view->dashboard();
    }
}

class ModelBeranda extends Model {

}

class ViewBeranda extends View {
    public function dashboard() {
        global $app;
        
        if (!isset($_SESSION['user'])) {
            header("Location:".$app->website);
        }
    }

    public function login() {
        global $app;
?>
    <body class="bg-gradient-primary">

<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
            <div class="col-lg-6">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Silahkan Login😍</h1>
                </div>
                <form action="<?php echo $app->website; ?>/Pengguna/login" method="post" class="user">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" name="username" placeholder="Username" required autofocus>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" required>
                  </div>
                  <input type="submit" class="btn btn-primary btn-user btn-block" value="LOGIN">
                  <hr>
                </form>
                <hr>
                <div class="text-center">
                  <h3 class="small">Copyright Favian & Ferdian 2019</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>
</body>

<?php
    }
}
?>