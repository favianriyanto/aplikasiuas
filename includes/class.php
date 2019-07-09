<?php

class Aplikasi {
    public $driver;
    public $host;
    public $dbname;
    public $username;
    public $password;
    public $name;
    public $website;
    public $server;

    public $connection;
    public $act;
    public $task;
    public $id;

    public function __construct($cfg) {
        $this->driver   = $cfg['driver'];
        $this->host     = $cfg['host'];
        $this->dbname   = $cfg['dbname'];
        $this->username = $cfg['username'];
        $this->password = $cfg['password'];
        $this->website  = $cfg['website'];
        $this->server   = $cfg['server'];

        $this->act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'Beranda';
        $this->task = isset($_REQUEST['task']) ? $_REQUEST['task'] : 'index';
        $this->id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        session_name("akademik");
        session_start();
    }

    public function connect(){
        try {
            $this->connection = new PDO("mysql:host=".$this->host.";port=3306;dbname=".$this->dbname,$this->username,$this->password,array());
            $this->connection -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo 'Connection failed: '.$ex->getMessage();
            exit();
        }

        require_once $this->server.'/acts/'.strtolower($this->act).'.php';

        $controllerName = 'Controller'.$this->act;
        $controller = new $controllerName();

        ob_start();	
        
        $methodName = $this->task;
        $controller->{$methodName}();

        $html = ob_get_contents();
		    ob_clean();

        ob_end_flush();
?>
<html>
    <head>
        <title>Admin - Favian & Ferdian</title>
        <link rel="icon" href="<?php echo $this->website; ?>/images/logo.png">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/propeller.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/bootstrap-datetimepicker.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/sb-admin-2.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/datatables/dataTables.bootstrap4.min.css">      
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/util.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/main.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/css-hamburgers/hamburgers.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/animate/animate.css">
    </head>
    <script>
    // Script untuk timer
    var countDownDate = new Date("<?php echo $_SESSION['user']->kambing?>").getTime();

    var x = setInterval(function() {

      var now = new Date().getTime();
        
      var distance = countDownDate - now;
      
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      document.getElementById("timer").innerHTML = days + " Hari " + hours + " Jam <br>"
      + minutes + " Menit " + seconds + " detik ";
        
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("timer").style.display = "none";
        document.getElementById("menu3").style.display = "none";
      }
    }, 1000);
    </script>
    <script>
      $levelakses = "<?php echo $_SESSION['user']->level; ?>"
      </script>
    <body id="page-top"> 
        <?php
            if ($this->act == 'Beranda' &&  $this->task == 'index') {
                echo $html;
            } else {
				?>
<?php
$levelakses = $_SESSION['user']->level;?>
<!-- Page Wrapper -->
<div id="wrapper">

<!-- Sidebar -->
<ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
  <div class="sidebar-brand-icon" style="margin-left:20px;">
	<i class="fas"><img src="<?php echo $app->website; ?>/images/logo-putih.png" alt="logo" style="width:50px;height:50px;"></i>
  </div>
  <div class="sidebar-brand-text mx-3">FavFer Admin</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
  <a class="nav-link" href="<?php echo $this->website; ?>/Beranda/dashboard">
	<i class="fas fa-fw fa-home"></i>
	<span>Beranda</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
  Menu
</div>

<!-- Nav Item - Utilities Collapse Menu -->
<li id="menusekjur" class="nav-item active" <?php if ($levelakses == 'Mahasiswa'){?>style="display:none"<?php } ?>>
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Menu Sekjur</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
            <a class="collapse-item" href="<?php echo $this->website; ?>/Dosen/index"><i class="fas fa-fw fa-chalkboard-teacher"></i>&nbsp;  Data Dosen</a>
            <a class="collapse-item" href="<?php echo $this->website; ?>/Mahasiswa/index"><i class="fas fa-fw fa-users"></i>&nbsp;  Data Mahasiswa</a>
            <a class="collapse-item" href="<?php echo $this->website; ?>/Pengguna/index"><i class="fas fa-fw fa-users-cog"></i>&nbsp;  Data Pengguna</a>
            <a class="collapse-item" href="<?php echo $this->website; ?>/DataKelas/index"><i class="fas fa-fw fa-table"></i>&nbsp;  Data Kelas</a>
            <a class="collapse-item" href="<?php echo $this->website; ?>/PengaturanKRS/entry/1"><i class="fas fa-fw fa-book"></i>&nbsp;  Pengaturan KRS</a>
          </div>
        </div>
      </li>

<!-- Nav Item - Pages Collapse Menu -->
<li id="menuriwayatkrs" class="nav-item active" <?php if ($levelakses == 'Sekjur'){?>style="display:none"<?php } ?>>
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
	<i class="fas fa-fw fa-folder"></i>
	<span>Riwayat KRS</span>
  </a>
  <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	<div class="bg-white py-2 collapse-inner rounded">
	  <!-- <h6 class="collapse-header">Login Screens:</h6> -->
    <a class="collapse-item" href="<?php echo $this->website; ?>/KRS/index"><i class="fas fa-fw fa-book"></i>&nbsp;  Semester 1</a>
    <a class="collapse-item" href="<?php echo $this->website; ?>/KRS/index"><i class="fas fa-fw fa-book"></i>&nbsp;  Semester 2</a>
    <a class="collapse-item" href="<?php echo $this->website; ?>/KRS/index"><i class="fas fa-fw fa-book"></i>&nbsp;  Semester 3</a>
	  <a class="collapse-item" href="<?php echo $this->website; ?>/KRS/index"><i class="fas fa-fw fa-book"></i>&nbsp;  Semester 4</a>
	</div>
  </div>
</li>

<!-- Nav Item - Charts -->

<li id="menu3" class="nav-item active" <?php if ($levelakses == 'Sekjur'){?>style="display:none"<?php } ?>>
  <a class="nav-link" href="<?php echo $this->website; ?>/Isikrs/index">
	<i class="fas fa-fw fa-table"></i>
	<span>Isi KRS</span></a>
</li>

<li id="menu4" class="nav-item active" <?php if ($levelakses == 'Sekjur'){?>style="display:none"<?php } ?>>
  <a class="nav-link" href="<?php echo $this->website; ?>/Matkul/index">
	<i class="fas fa-fw fa-book"></i>
	<span>Mata Kuliah</span></a>
</li>

<li id="menu5" class="nav-item active" <?php if ($levelakses == 'Sekjur'){?>style="display:none"<?php } ?>>
  <a class="nav-link" href="<?php echo $this->website; ?>/Jadwal/index">
	<i class="fas fa-fw fa-clock"></i>
	<span>Jadwal</span></a>
</li>

<li id="menu6" class="nav-item active" <?php if ($levelakses == 'Mahasiswa'){?>style="display:none"<?php } ?>>
  <a class="nav-link" href="<?php echo $this->website; ?>/DataJadwal/index">
	<i class="fas fa-fw fa-clock"></i>
	<span>Data Jadwal</span></a>
</li>

<li id="menu7" class="nav-item active" <?php if ($levelakses == 'Sekjur'){?>style="display:none"<?php } ?>>
  <a class="nav-link" href="<?php echo $this->website; ?>/Kelas/index">
	<i class="fas fa-fw fa-table"></i>
	<span>Kelas</span></a>
</li>

<li id="menu8" class="nav-item active" <?php if ($levelakses == 'Mahasiswa'){?>style="display:none"<?php } ?>>
  <a class="nav-link" href="<?php echo $this->website; ?>/DataMatkul/index">
	<i class="fas fa-fw fa-table"></i>
	<span>Data Mata Kuliah</span></a>
</li>
    
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
  <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-dark topbar static-top" style="border-radius: 0px;">

	<!-- Sidebar Toggle (Topbar) -->
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
	  <i class="fa fa-bars"></i>
	</button>

	

	<!-- Topbar Navbar -->
	<ul class="navbar-nav ml-auto">

              <!-- Nav Item - Alerts -->
              <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="<?php echo $this->website; ?>/Faq/index" id="alertsDropdown" role="button" aria-haspopup="true" aria-expanded="false">
              F.A.Q &nbsp;  <i class="fas fa-question-circle fa-fw"></i>
              </a>
            </li>

<!-- Nav Item - Search Dropdown (Visible Only XS) -->
<li class="nav-item dropdown no-arrow d-sm-none">
  <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-search fa-fw"></i>
  </a>
  <!-- Dropdown - Messages -->
  <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
    <form class="form-inline mr-auto w-100 navbar-search">
      <div class="input-group">
        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search fa-sm"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
</li>

	  <div class="topbar-divider d-none d-sm-block"></div>

	  <!-- Nav Item - User Information -->
	  <li class="nav-item dropdown arrow">
		<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <span class="mr-2 d-none d-lg-inline text-white-600 small font-weight-bold"><?php echo $_SESSION['user']->ayam?></span>
		  <img class="img-profile rounded-circle" src="<?php echo $this->website; ?>/images/logo-icon.png">
		</a>
		<!-- Dropdown - User Information -->
		<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
		  <a id="menuprofile" class="dropdown-item" <?php if ($levelakses == 'Mahasiswa'){?>style="display:none"<?php } ?> href="<?php echo $this->website; ?>/Pengguna/index">
			<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
			Profile
		  </a>
		  <div id="menugaris" class="dropdown-divider" <?php if ($levelakses == 'Mahasiswa'){?>style="display:none"<?php } ?>></div>
		  <a class="dropdown-item" href="<?php echo $this->website; ?>/Pengguna/logout">
			<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
			Logout
		  </a>
		</div>
	  </li>

	</ul>

  </nav>
  <!-- End of Topbar -->

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard" >
<?php echo $html; ?>
</div>
 
                <?php
            }
        ?>
<?php
        
?>
		<script src="<?php echo $this->website; ?>/vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
		<script src="<?php echo $this->website; ?>/js/sb-admin-2.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/chart.js/Chart.min.js"></script>
    <script src="<?php echo $this->website; ?>/js/demo/datatables-demo.js"></script>
    <script src="<?php echo $this->website; ?>/js/jquery-3.3.1.js"></script>
    <script src="<?php echo $this->website; ?>/js/bootstrap-datetimepicker.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/chart.js/Chart.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/select2/select2.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/tilt/tilt.jquery.min.js"></script>


    <!-- script untuk tabel -->
    <script src="<?php echo $this->website; ?>/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $this->website; ?>/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo $this->website; ?>/js/buttons.flash.min.js"></script>
    <script src="<?php echo $this->website; ?>/js/jszip.min.js"></script>
    <script src="<?php echo $this->website; ?>/js/pdfmake.min.js"></script>
    <script src="<?php echo $this->website; ?>/js/vfs_fonts.js"></script>
    <script src="<?php echo $this->website; ?>/vendor/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#tabelisikrs').DataTable( {
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ]
      } );
  } );
    </script>
		<script>
			$(document).ready( function () {
				$('#dataTable').DataTable();
			} );
		</script>
		<script >
			$('.js-tilt').tilt({
				scale: 1.1
			})
		</script>
		<script src="<?php echo $this->website; ?>/js/main.js"></script>
		<script>
          var ctx = document.getElementById("chart");
          var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ["Angkatan 2012", "Angkatan 2013", "Angkatan 2014", "Angkatan 2015", "Angkatan 2016", "Angkatan 2017", "Angkatan 2018", ],
              datasets: [{
                data: [156, 250, 265, 262, 183, 212, 146],
                backgroundColor: ['#FFC100', '#A6FF00', '#00FF7C', '#00FBFF', '#0070FF', '#5500FF', '#E000FF'],
                hoverBackgroundColor: ['#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
              }],
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
              },
              legend: {
                display: false
              },
              cutoutPercentage: 80,
            },
          });


          var ctx = document.getElementById("chart2");
          var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ["Laki-Laki", "Perempuan" ],
              datasets: [{
                data: [156, 250],
                backgroundColor: ['#FFC100', '#A6FF00'],
                hoverBackgroundColor: ['#FF0000', '#FF0000'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
              }],
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
              },
              legend: {
                display: false
              },
              cutoutPercentage: 80,
            },
          });
    </script>
    <script type="text/javascript">
      $(function () {
        $("#datetimepicker1").datetimepicker({
        format: "dd M yyyy hh:ii:ss",
        todayBtn: true,
    });

      });
    </script>
    </body>
</html>
<?php
    }

    
}

class Controller {
    public function __construct() {
        
    }
}

class Model {
    public function __construct() {
        
    }
}

class View {
    public function __construct() {
        
    }
}
?>