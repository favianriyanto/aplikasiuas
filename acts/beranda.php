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

      if($_SESSION['user']->level=="Mahasiswa"){
        header("location:../BerandaMahasiswa/dashboard");
      }
        global $app;
        
        if (!isset($_SESSION['user'])) {
            header("Location:".$app->website);
        }
        ?>

<div class="container-fluid">
        <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jumlah Mahasiswa</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $_SESSION['user']->katak?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Mahasiswa Laki-laki</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">1024</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Mahasiswa Perempuan</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">1024</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 mb-4"> 
              
        <!-- Donut Chart -->
        <div class="col-xl-10 col-lg-6">
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
                    <!-- <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Referral
                    </span> -->
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
					<img src="<?php echo $app->website; ?>/images/logo.png" alt="IMG">
				</div>

				<form action="<?php echo $app->website; ?>/Pengguna/login" method="post" class="login100-form validate-form">
					<span class="login100-form-title">
            Selamat Datang<br>
						Silahkan Login
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