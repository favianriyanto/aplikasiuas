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

        /*if (isset($_REQUEST['act'])) {
            this->act = $_REQUEST['act'];
        } else {
            this->act = 'Beranda';
        }*/
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
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/propeller.min.css">
		
		<link rel="stylesheet" href="<?php echo $this->website; ?>/css/sb-admin-2.css">
		<link rel="stylesheet" href="<?php echo $this->website; ?>/css/sb-admin-2.min.css">
		<link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/fontawesome-free/css/all.min.css">
		<link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/datatables/dataTables.bootstrap4.min.css">
		
		<link rel="stylesheet" href="<?php echo $this->website; ?>/css/util.css">
		<link rel="stylesheet" href="<?php echo $this->website; ?>/css/main.css">
		<link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/css-hamburgers/hamburgers.min.css">
		<link rel="stylesheet" href="<?php echo $this->website; ?>/vendor/animate/animate.css">

    </head>
    <body> 
        <?php
            if ($this->act == 'Beranda' &&  $this->task == 'index') {
                echo $html;
            } else {
				?>

<!-- Sidebar Starts -->
<div class="pmd-sidebar-overlay"></div>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
  <div class="sidebar-brand-icon rotate-n-15">
	<i class="fas fa-laugh-wink"></i>
  </div>
  <div class="sidebar-brand-text mx-3">Favian-Ferdian BETA</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
  <a class="nav-link" href="<?php echo $this->website; ?>/Beranda/dashboard">
	<i class="fas fa-fw fa-tachometer-alt"></i>
	<span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
  Interface
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
	<i class="fas fa-fw fa-cog"></i>
	<span>Components</span>
  </a>
  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
	<div class="bg-white py-2 collapse-inner rounded">
	  <h6 class="collapse-header">Custom Components:</h6>
	  <a class="collapse-item" href="buttons.html">Buttons</a>
	  <a class="collapse-item" href="cards.html">Cards</a>
	</div>
  </div>
</li>

<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
	<i class="fas fa-fw fa-wrench"></i>
	<span>Utilities</span>
  </a>
  <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
	<div class="bg-white py-2 collapse-inner rounded">
	  <h6 class="collapse-header">Custom Utilities:</h6>
	  <a class="collapse-item" href="utilities-color.html">Colors</a>
	  <a class="collapse-item" href="utilities-border.html">Borders</a>
	  <a class="collapse-item" href="utilities-animation.html">Animations</a>
	  <a class="collapse-item" href="utilities-other.html">Other</a>
	</div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
  Addons
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
	<i class="fas fa-fw fa-folder"></i>
	<span>Pages</span>
  </a>
  <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	<div class="bg-white py-2 collapse-inner rounded">
	  <h6 class="collapse-header">Login Screens:</h6>
	  <a class="collapse-item" href="login.html">Login</a>
	  <a class="collapse-item" href="register.html">Register</a>
	  <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
	  <div class="collapse-divider"></div>
	  <h6 class="collapse-header">Other Pages:</h6>
	  <a class="collapse-item" href="404.html">404 Page</a>
	  <a class="collapse-item" href="blank.html">Blank Page</a>
	</div>
  </div>
</li>

<!-- Nav Item - Charts -->
<li id="menu1" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Pengguna/index">
	<i class="fas fa-fw fa-users"></i>
	<span>Pengguna</span></a>
</li>

<li id="menu2" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Mahasiswa/index">
	<i class="fas fa-fw fa-users"></i>
	<span>Mahasiswa</span></a>
</li>

<li id="menu3" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Dosen/index">
  	<i class="fas fa-chalkboard-teacher"></i>
	<span>Dosen</span></a>
</li>

<li id="menu4" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Matkul/index">
	<i class="fas fa-fw fa-book"></i>
	<span>Mata Kuliah</span></a>
</li>

<li id="menu5" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Jadwal/index">
	<i class="fas fa-fw fa-clock"></i>
	<span>Jadwal</span></a>
</li>

<!-- Nav Item - Tables -->
<li id="menu6" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Kelas/index">
	<i class="fas fa-fw fa-table"></i>
	<span>Kelas</span></a>
</li>

<li id="menu7" class="nav-item">
  <a class="nav-link" href="<?php echo $this->website; ?>/Isikrs/index">
	<i class="fas fa-fw fa-table"></i>
	<span>Isi KRS</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

	<!-- Sidebar Toggle (Topbar) -->
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
	  <i class="fa fa-bars"></i>
	</button>

	<!-- Topbar Search -->
	<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
	  <div class="input-group">
		<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
		<div class="input-group-append">
		  <button class="btn btn-primary" type="button">
			<i class="fas fa-search fa-sm"></i>
		  </button>
		</div>
	  </div>
	</form>

	<!-- Topbar Navbar -->
	<ul class="navbar-nav ml-auto">

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

	  <!-- Nav Item - Alerts -->
	  <li class="nav-item dropdown no-arrow mx-1">
		<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <i class="fas fa-bell fa-fw"></i>
		  <!-- Counter - Alerts -->
		  <span class="badge badge-danger badge-counter">3+</span>
		</a>
		<!-- Dropdown - Alerts -->
		<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
		  <h6 class="dropdown-header">
			Alerts Center
		  </h6>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="mr-3">
			  <div class="icon-circle bg-primary">
				<i class="fas fa-file-alt text-white"></i>
			  </div>
			</div>
			<div>
			  <div class="small text-gray-500">December 12, 2019</div>
			  <span class="font-weight-bold">A new monthly report is ready to download!</span>
			</div>
		  </a>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="mr-3">
			  <div class="icon-circle bg-success">
				<i class="fas fa-donate text-white"></i>
			  </div>
			</div>
			<div>
			  <div class="small text-gray-500">December 7, 2019</div>
			  $290.29 has been deposited into your account!
			</div>
		  </a>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="mr-3">
			  <div class="icon-circle bg-warning">
				<i class="fas fa-exclamation-triangle text-white"></i>
			  </div>
			</div>
			<div>
			  <div class="small text-gray-500">December 2, 2019</div>
			  Spending Alert: We've noticed unusually high spending for your account.
			</div>
		  </a>
		  <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
		</div>
	  </li>

	  <!-- Nav Item - Messages -->
	  <li class="nav-item dropdown no-arrow mx-1">
		<a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <i class="fas fa-envelope fa-fw"></i>
		  <!-- Counter - Messages -->
		  <span class="badge badge-danger badge-counter">7</span>
		</a>
		<!-- Dropdown - Messages -->
		<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
		  <h6 class="dropdown-header">
			Message Center
		  </h6>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="dropdown-list-image mr-3">
			  <img class="rounded-circle" src="" alt="">
			  <div class="status-indicator bg-success"></div>
			</div>
			<div class="font-weight-bold">
			  <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
			  <div class="small text-gray-500">Emily Fowler · 58m</div>
			</div>
		  </a>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="dropdown-list-image mr-3">
			  <img class="rounded-circle" src="" alt="">
			  <div class="status-indicator"></div>
			</div>
			<div>
			  <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
			  <div class="small text-gray-500">Jae Chun · 1d</div>
			</div>
		  </a>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="dropdown-list-image mr-3">
			  <img class="rounded-circle" src="" alt="">
			  <div class="status-indicator bg-warning"></div>
			</div>
			<div>
			  <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
			  <div class="small text-gray-500">Morgan Alvarez · 2d</div>
			</div>
		  </a>
		  <a class="dropdown-item d-flex align-items-center" href="#">
			<div class="dropdown-list-image mr-3">
			  <img class="rounded-circle" src="" alt="">
			  <div class="status-indicator bg-success"></div>
			</div>
			<div>
			  <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
			  <div class="small text-gray-500">Chicken the Dog · 2w</div>
			</div>
		  </a>
		  <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
		</div>
	  </li>

	  <div class="topbar-divider d-none d-sm-block"></div>

	  <!-- Nav Item - User Information -->
	  <li class="nav-item dropdown no-arrow">
		<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['user']->ayam?></span>
		  <img class="img-profile rounded-circle" src="<?php echo $this->website; ?>/images/logo-icon.png">
		</a>
		<!-- Dropdown - User Information -->
		<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
		  <a id="menuprofile" class="dropdown-item" href="<?php echo $this->website; ?>/Pengguna/index">
			<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
			Profile
		  </a>
		  <div id="menugaris" class="dropdown-divider"></div>
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
		<script>
			$(document).ready( function () {
				$('#dataTable').DataTable();
			} );
		</script>

		<script>
			if("<?php echo $_SESSION['user']->level?>" == "Mahasiswa")
			{
				document.getElementById("menu1").style.display = "none";
				document.getElementById("menu3").style.display = "none";
				document.getElementById("menugaris").style.display = "none";
				document.getElementById("menuprofile").style.display = "none";
			}
			else{	}
		</script>
		<script src="<?php echo $this->website; ?>/vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
		<script src="<?php echo $this->website; ?>/js/sb-admin-2.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/chart.js/Chart.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
		<script src="<?php echo $this->website; ?>/js/demo/datatables-demo.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/chart.js/Chart.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/select2/select2.min.js"></script>
		<script src="<?php echo $this->website; ?>/vendor/tilt/tilt.jquery.min.js"></script>
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
                data: [30, 40, 50, 60, 70, 80, 90],
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