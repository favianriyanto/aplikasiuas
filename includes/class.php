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
        <title>Test</title>

        <link rel="stylesheet" href="<?php echo $this->website; ?>/fonts/material-icons/material-icons.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/propeller.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/custom.css">

        <link rel="stylesheet" href="<?php echo $this->website; ?>/js/components/datetimepicker/css/bootstrap-datetimepicker.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/js/components/datetimepicker/css/pmd-datetimepicker.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/js/components/select2/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/js/components/select2/css/select2-bootstrap.css">
        <link rel="stylesheet" href="<?php echo $this->website; ?>/js/components/select2/css/pmd-select2.css">

        <link rel="stylesheet" href="<?php echo $this->website; ?>/css/propeller-admin.css">
    </head>
    <body>
        <?php
            if ($this->act == 'Beranda' &&  $this->task == 'index') {
                echo $html;
            } else {
                ?>
                <!-- Header Starts -->
<!--Start Nav bar -->
<nav class="navbar navbar-inverse navbar-fixed-top pmd-navbar pmd-z-depth">

<div class="container-fluid">
    <div class="pmd-navbar-right-icon pull-right navigation">
        <!-- Notifications -->
        <div class="dropdown notification icons pmd-dropdown">
        
            <a href="javascript:void(0)" title="Notification" class="dropdown-toggle pmd-ripple-effect"  data-toggle="dropdown" role="button" aria-expanded="true">
                <div data-badge="3" class="material-icons md-light pmd-sm pmd-badge  pmd-badge-overlap">notifications_none</div>
            </a>
        
            <div class="dropdown-menu dropdown-menu-right pmd-card pmd-card-default pmd-z-depth" role="menu">
                <!-- Card header -->
                <div class="pmd-card-title">
                    <div class="media-body media-middle">
                        <a href="notifications.html" class="pull-right">3 new notifications</a>
                        <h3 class="pmd-card-title-text">Notifications</h3>
                    </div>
                </div>
                
                <!-- Notifications list -->
                <ul class="list-group pmd-list-avatar pmd-card-list">
                    <li class="list-group-item" style="display:none">
                        <p class="notification-blank">
                            <span class="dic dic-notifications-none"></span> 
                            <span>You don´t have any notifications</span>
                        </p>
                    </li>
                    <li class="list-group-item unread">
                        <a href="javascript:void(0)">
                            <div class="media-left">
                                <span class="avatar-list-img40x40">
                                    <img alt="40x40" data-src="holder.js/40x40" class="img-responsive" src="<?php echo $this->website; ?>/themes/images/profile-1.png" data-holder-rendered="true">
                                </span>
                            </div>
                            <div class="media-body">
                                <span class="list-group-item-heading"><span>Prathit</span> posted a new challanegs</span>
                                <span class="list-group-item-text">5 Minutes ago</span>
                            </div>
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="javascript:void(0)">
                            <div class="media-left">
                                <span class="avatar-list-img40x40">
                                    <img alt="40x40" data-src="holder.js/40x40" class="img-responsive" src="<?php echo $this->website; ?>/themes/images/profile-2.png" data-holder-rendered="true">
                                </span>
                            </div>
                            <div class="media-body">
                                <span class="list-group-item-heading"><span>Keel</span> Cloned 2 challenges.</span>
                                <span class="list-group-item-text">15 Minutes ago</span>
                            </div>
                        </a>
                    </li>
                    <li class="list-group-item unread">
                        <a href="javascript:void(0)">
                            <div class="media-left">
                                <span class="avatar-list-img40x40">
                                    <img alt="40x40" data-src="holder.js/40x40" class="img-responsive" src="<?php echo $this->website; ?>/themes/images/profile-3.png" data-holder-rendered="true">
                                </span>
                            </div>
                        
                            <div class="media-body">
                                <span class="list-group-item-heading"><span>John</span> posted new collection.</span>
                                <span class="list-group-item-text">25 Minutes ago</span>
                            </div>
                        </a>
                    </li>
                    <li class="list-group-item unread">
                        <a href="javascript:void(0)">
                            <div class="media-left">
                                <span class="avatar-list-img40x40">
                                    <img alt="40x40" data-src="holder.js/40x40" class="img-responsive" src="<?php echo $this->website; ?>/themes/images/profile-4.png" data-holder-rendered="true">
                                </span>
                            </div>
                            <div class="media-body">
                                <span class="list-group-item-heading"><span>Valerii</span> Shared 5 collection.</span>
                                <span class="list-group-item-text">30 Minutes ago</span>
                            </div>
                        </a>
                    </li>
                </ul><!-- End notifications list -->

            </div>
            
            
        </div> <!-- End notifications -->
    </div>
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <a href="javascript:void(0);" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle"><i class="material-icons">menu</i></a>	
      <a href="index.html" class="navbar-brand" style="font-weight:bold; color:white">
          FAVIAN - FERDIAN (BETA)
      </a>
    </div>
</div>

</nav><!--End Nav bar -->
<!-- Header Ends -->

<!-- Sidebar Starts -->
<div class="pmd-sidebar-overlay"></div>

<!-- Left sidebar -->
<aside class="pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons" role="navigation">
	<ul class="nav pmd-sidebar-nav">
                
		
		<li class="dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg">
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" aria-expandedhref="javascript:void(0);">
				<div class="media-left">
					<img src="<?php echo $this->website; ?>/images/user-icon.png" alt="New User">
				</div>
				<div class="media-body media-middle"><?php echo $_SESSION['user']->ayam?></div>
                <li><a href="<?php echo $this->website; ?>/Pengguna/logout">Logout</a></li>
				<div class="media-right media-middle"><i class="dic-more-vert dic"></i></div>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo $this->website; ?>/Pengguna/logout">Logout</a></li>
			</ul>
		</li>
		
		<!-- <li> 
			<a class="pmd-ripple-effect" href="index.html">	
				<i class="media-left media-middle"><svg version="1.1" x="0px" y="0px" width="19.83px" height="18px" viewBox="287.725 407.535 19.83 18" enable-background="new 287.725 407.535 19.83 18"
	 xml:space="preserve">
<g>
	<path fill="#C9C8C8" d="M307.555,407.535h-9.108v10.264h9.108V407.535z M287.725,407.535v6.232h9.109v-6.232H287.725z
		 M296.834,415.271h-9.109v10.264h9.109V415.271z M307.555,419.303h-9.108v6.232h9.108V419.303z"/>
</g>
</svg></i>
				<span class="media-body">Dashboard</span>
			</a> 
		</li> --> -->
		
		<!-- <li class="dropdown pmd-dropdown"> 
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" href="javascript:void(0);">	
				<i class="media-left media-middle"><svg version="1.1" x="0px" y="0px" width="18px" height="18.001px" viewBox="0 0 18 18.001" enable-background="new 0 0 18 18.001" xml:space="preserve">
<path fill="#C9C8C8" d="M6.188,0.001C5.232,0.001,4.5,0.732,4.5,1.688c0,0.394,0.166,0.739,0.334,1.02L5.45,3.71
	c0.113,0.113,0.176,0.341,0.176,0.51v0.281c0,0.619-0.506,1.125-1.125,1.125H0.282c-0.169,0-0.281,0.112-0.281,0.281V17.72
	c0,0.168,0.112,0.281,0.281,0.281h4.219c0.619,0,1.125-0.506,1.125-1.125v-0.281c0-0.168-0.063-0.397-0.176-0.509
	c0,0-0.615-0.946-0.615-1.002C4.666,14.802,4.5,14.457,4.5,14.063c0-0.956,0.731-1.688,1.688-1.688s1.688,0.731,1.688,1.688
	c0,0.394-0.166,0.739-0.334,1.02l-0.616,1.002c-0.056,0.112-0.176,0.341-0.176,0.509v0.281c0,0.619,0.506,1.125,1.125,1.125h4.219
	c0.168,0,0.281-0.113,0.281-0.281V13.5c0-0.619,0.506-1.125,1.125-1.125h0.281c0.169,0,0.396,0.063,0.51,0.176
	c0,0,0.945,0.616,1.002,0.616c0.337,0.168,0.626,0.334,1.02,0.334c0.956,0,1.687-0.731,1.687-1.687c0-0.957-0.731-1.688-1.687-1.688
	c-0.394,0-0.738,0.166-1.02,0.334l-1.002,0.616c-0.113,0.056-0.341,0.176-0.51,0.176H13.5c-0.619,0-1.125-0.506-1.125-1.125V5.908
	c0-0.168-0.113-0.281-0.281-0.281H7.875c-0.619,0-1.125-0.506-1.125-1.125V4.221c0-0.168,0.063-0.397,0.176-0.51
	c0,0,0.616-0.945,0.616-1.001c0.168-0.281,0.334-0.626,0.334-1.02C7.875,0.733,7.144,0.002,6.188,0.001L6.188,0.001z"/>
</svg></i> 
				<span class="media-body">UI Elements</span>
				<div class="media-right media-bottom"><i class="dic-more-vert dic"></i></div>
			</a> 
			<ul class="dropdown-menu">
				<li><a href="typography.html">Typography</a></li>
				<li><a href="icons.html">Icons</a></li>
				<li><a href="shadow.html">Shadow</a></li>
				<li><a href="accordion.html">Accordion</a></li>
				<li><a href="alert.html">Alert</a></li>
				<li><a href="badge.html">Badge</a></li>
				<li><a href="button.html">Button</a></li>
				<li><a href="modal.html">Modal</a></li>
				<li><a href="dropdown.html">Dropdown</a></li>
				<li><a href="list.html">List</a></li>
				<li><a href="navbar.html">Navbar</a></li>
				<li><a href="popover.html">Popover</a></li>
				<li><a href="progressbar.html">Progressbar</a></li>
				<li><a href="sidebar.html">Sidebar</a></li>
				<li><a href="tab.html">Tab</a></li>
				<li><a href="tooltip.html">Tooltip</a></li>
				<li><a href="card.html">Card</a></li>
				<li><a href="floating-button.html">Floating Action Button</a></li>
			</ul>
		</li>
		<li class="dropdown pmd-dropdown"> 
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" href="javascript:void(0);">	
				<i class="material-icons media-left pmd-sm">swap_calls</i> 
				<span class="media-body">Third Party Elements</span>
				<div class="media-right media-bottom"><i class="dic-more-vert dic"></i></div>
			</a> 
			<ul class="dropdown-menu">
				<li><a href="custom-scroll.html">Custom Scrollbar</a></li>
				<li><a href="datetimepicker.html">Datetimepicker</a></li>
				<li><a href="range-slider.html">Range Slider</a></li>
				<li><a href="select2.html">Select2</a></li>
			</ul>
		</li> -->
		
		<!-- <li class="dropdown pmd-dropdown"> 
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" href="javascript:void(0);">	
				<i class="media-left media-middle"><svg version="1.1" x="0px" y="0px" width="14.187px" height="18px" viewBox="0 0 14.187 18" enable-background="new 0 0 14.187 18" xml:space="preserve">
<path fill="#C9C8C8" d="M0,0v18h14.187V0H0z M3.121,3.293h2.023v4.767H3.121V3.293z M11.211,14.764H2.948v-2.022h8.263V14.764
	L11.211,14.764z M11.211,11.585H2.948V9.563h8.263V11.585L11.211,11.585z M11.211,8.407H7.455V6.385h3.756V8.407z M11.211,5.229
	H7.455V3.207h3.756V5.229z"/>
</svg></i>
				<span class="media-body">Form</span>
				<div class="media-right media-bottom"><i class="dic-more-vert dic"></i></div>
			</a> 
			<ul class="dropdown-menu">
				<li><a href="form-element.html">Form Elements</a></li>
				<li><a href="form.html">Form Examples</a></li>
			</ul>
		</li>
		<li class="dropdown pmd-dropdown"> 
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" href="javascript:void(0);">	
				<i class="media-left media-middle"><svg version="1.1" x="0px" y="0px" width="18px" height="12.706px" viewBox="0 0 18 12.706" enable-background="new 0 0 18 12.706" xml:space="preserve">
<path fill="#C9C8C8" d="M0,0v12.706h18V0H0z M12.706,4.235v3.176H9.108V4.235H12.706z M8.049,4.235v3.176h-6.99V4.235H8.049z
	 M1.059,8.47h6.99v3.177h-6.99V8.47z M9.108,11.647V8.47h3.599v3.177H9.108z M13.766,11.647V8.47h3.176v3.177H13.766z M16.942,7.412
	h-3.176V4.235h3.176V7.412L16.942,7.412z"/>
</svg></i> 
				<span class="media-body">Table</span>
				<div class="media-right media-bottom"><i class="dic-more-vert dic"></i></div>
			</a> 
			<ul class="dropdown-menu">
				<li><a href="table.html">Normal Table</a></li>
				<li><a href="data-table.html">Data Table</a></li>
				<li><a href="table-with-expand-collapse.html">Table with Expand/Collapse</a></li>
			</ul>
		</li> -->

		<!-- <li class="dropdown pmd-dropdown"> 
			<a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" href="javascript:void(0);">	
				<i class="media-left media-middle">
				<svg x="0px" y="0px" width="18px" height="18px" viewBox="288.64 337.535 18 18" enable-background="new 288.64 337.535 18 18" xml:space="preserve">
					<title>022-layout view</title>
					<desc>Created with Sketch.</desc>
					<g>
						<g>
							<path fill="#C9C8C8" d="M298.765,353.285v-2.25h3.375v-3.375h2.25v5.625H298.765z M290.89,347.66h2.25v3.375h3.375v2.25h-5.625
								V347.66z M296.515,339.785v2.25h-3.375v3.375h-2.25v-5.625H296.515z M295.39,348.785h4.5v-4.5h-4.5V348.785z M304.39,345.41h-2.25
								v-3.375h-3.375v-2.25h5.625V345.41z M288.64,355.535h18v-18h-18V355.535z"/>
						</g>
					</g>
					<text transform="matrix(1 0 0 1 -0.0154 1202.2578)" font-family="'HelveticaNeue-Bold'" font-size="186.0251">Created by Richard Wearn</text>
					<text transform="matrix(1 0 0 1 -0.0154 1388.2891)" font-family="'HelveticaNeue-Bold'" font-size="186.0251">from the Noun Project</text>
				</svg></i> 
				<span class="media-body">Pages</span>
				<div class="media-right media-bottom"><i class="dic-more-vert dic"></i></div>
			</a> 
			<ul class="dropdown-menu">
				<li><a href="about.html">About</a></li>
				<li><a href="contact.html">Contact</a></li>
				<li><a href="404.html">404</a></li>
				<li><a href="blank.html">Blank</a></li>
				<li><a href="profile.html">Profile</a></li>
			</ul>
		</li> -->
		<li> 
			<a class="pmd-ripple-effect" href="<?php echo $this->website; ?>/Pengguna/index">	
				<i class="material-icons media-left md-light pmd-sm">people</i>
				<span class="media-body">Pengguna</span>
			</a> 
		</li>
        <li> 
			<a class="pmd-ripple-effect" href="<?php echo $this->website; ?>/Matkul/index">	
				<i class="material-icons media-left md-light pmd-sm">people</i>
				<span class="media-body">Mata Kuliah</span>
			</a> 
		</li>
		<li> 
			<a class="pmd-ripple-effect" href="<?php echo $this->website; ?>/Mahasiswa/index">	
				<i class="media-left media-middle">
				<svg version="1.1" x="0px" y="0px" width="18px" height="12.479px" viewBox="288.64 363.118 18 12.479" enable-background="new 288.64 363.118 18 12.479" xml:space="preserve">
					<g transform="translate(641.29613,1096.2351)">
						<path fill="#C9C8C8" d="M-352.656-726.466v-5.828l4.484,4.484c2.467,2.466,4.499,4.484,4.516,4.484s2.049-2.018,4.516-4.484
							l4.484-4.484v5.828v5.828h-9h-9V-726.466z M-347.854-728.929l-4.188-4.188h8.385h8.386l-4.188,4.188
							c-2.304,2.303-4.192,4.188-4.198,4.188S-345.551-726.626-347.854-728.929z"/>
					</g>
				</svg></i> 
				<span class="media-body">Mahasiswa</span>
			</a> 
		</li>
		<li> 
			<a class="pmd-ripple-effect" href="<?php echo $this->website; ?>/Dosen/index">	
				<i class="media-left media-middle">
				<svg version="1.1" id="Layer_1" x="0px" y="0px" width="15.3px" height="18px" viewBox="289.99 337.535 15.3 18" enable-background="new 289.99 337.535 15.3 18" xml:space="preserve">
					<g>
						<g>
							<path fill="#C9C8C8" d="M297.64,355.535c0.99,0,1.8-0.81,1.8-1.8h-3.6C295.84,354.725,296.65,355.535,297.64,355.535z
								 M303.49,350.135v-4.95c0-2.79-1.891-5.041-4.501-5.67v-0.63c0-0.72-0.63-1.35-1.35-1.35c-0.72,0-1.35,0.63-1.35,1.35v0.63
								c-2.61,0.629-4.5,2.88-4.5,5.67v4.95l-1.8,1.8v0.9h15.3v-0.9L303.49,350.135z"/>
						</g>
					</g>
				</svg></i> 
				<span class="media-body">Dosen</span>
			</a> 
		</li>
		
	</ul>
</aside><!-- End Left sidebar -->
<!-- Sidebar Ends --> 

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">
<?php echo $html; ?>
</div>
 
                <?php
            }
        ?>
<?php
        
?>
        <script src="<?php echo $this->website; ?>/js/jquery-1.12.2.min.js"></script>
        <script src="<?php echo $this->website; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->website; ?>/js/propeller.min.js"></script>

        <script src="<?php echo $this->website; ?>/js/components/datetimepicker/js/moment-with-locales.js"></script>
        <script src="<?php echo $this->website; ?>/js/components/datetimepicker/js/locale/id.js"></script>
        <script src="<?php echo $this->website; ?>/js/components/datetimepicker/js/bootstrap-datetimepicker.js"></script>
        <script src="<?php echo $this->website; ?>/js/components/select2/js/select2.full.js"></script>
        <script src="<?php echo $this->website; ?>/js/components/select2/js/pmd-select2.js"></script>
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