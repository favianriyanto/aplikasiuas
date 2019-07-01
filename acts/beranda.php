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

      if($_SESSION['user']->level!="Administrator"){
        header("location:../BerandaMahasiswa/dashboard");
      }
        global $app;
        
        if (!isset($_SESSION['user'])) {
            header("Location:".$app->website);
        }
        echo "<h1>Semangat yang lagi ngerjain ini website :*</h1>";
        ?>
        <!-- Donut Chart -->
        <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Grafik Jumlah Mahasiswa per Angkatan</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4">
                    <canvas id="chart"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Referral
                    </span>
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
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>

				<form action="<?php echo $app->website; ?>/Pengguna/login" method="post" class="login100-form validate-form">
					<span class="login100-form-title">
						Silahkan Login😍
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Masukkan Username">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-users" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Masukkan Password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
          </div>
          
          <div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Login
						</button>
          </div>
					<div class="text-center p-t-60">
						<a class="txt2">
							Copyright Favian - Ferdian 2019.
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

<?php
    }
}
?>