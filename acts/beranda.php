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
    <body style="background-image:url(images/bg4.jpg)">
    <div class="logincard">
  	<div class="pmd-card card-default pmd-z-depth" style="border-style:solid; border-color:#228B22; border-width:thick" >
		<div class="login-card">
			<form action="<?php echo $app->website; ?>/Pengguna/login" method="post">	
				<div class="pmd-card-title card-header-border text-center">
					<div class="loginlogo">
						<a href="javascript:void(0);"><img src="images/uinss.jpg" alt="Logo"></a>
					</div>
					<h3 style="font-family:Poor Richard; color:#228B22">Wellcome to IRAISE</h3>
				</div>
				
				<div class="pmd-card-body" style="font-family:Times New Roman">
					<div class="alert alert-success" role="alert"> Oh snap! Change a few things up and try submitting again. </div>
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i></div>
                            <input type="text" class="form-control" name="username" autofocus required>
                        </div>
                    </div>
                    
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i></div>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                </div>
				<div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
					<input type="submit" style="background-color:#228B22" class="btn pmd-ripple-effect btn-primary btn-block" value="Login">
                </div>
				
			</form>
		</div>
		
		<div class="register-card">
			<div class="pmd-card-title card-header-border text-center">
				<div class="loginlogo">
					<a href="javascript:void(0);"><img src="images/uinss.jpg" alt="Logo"></a>
				</div>
				<h3>Selamat Datang <span>di <strong>IRAISE</strong></span></h3>
			</div>
			<form>	
			  <div class="pmd-card-body">
              
              	<div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i></div>
                            <input type="text" class="form-control" id="exampleInputAmount">
                        </div>
                    </div>
                    
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Email address</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">email</i></div>
                            <input type="text" class="form-control" id="exampleInputAmount">
                        </div>
                    </div>
                    
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i></div>
                            <input type="text" class="form-control" id="exampleInputAmount">
                        </div>
                    </div>
              </div>
			  
			  <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
				<a href="index.html" type="button" class="btn pmd-ripple-effect btn-primary btn-block">Sign Up</a>
			  	<p class="redirection-link">Already have an account? <a href="javascript:void(0);" class="register-login">Sign In</a>. </p>
			  </div>
			</form>
		</div>
		
		<div class="forgot-password-card">
			<form>	
			  <div class="pmd-card-title card-header-border text-center">
				<div class="loginlogo">
					<a href="javascript:void(0);"><img src="images/uinss.jpg" alt="Logo"></a>
				</div>
				<h3>Forgot password?<br><span>Submit your email address and we'll send you a link to reset your password.</span></h3>
			</div>
			  <div class="pmd-card-body">
					<div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Email address</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">email</i></div>
                            <input type="text" class="form-control" id="exampleInputAmount">
                        </div>
                    </div>
				</div>
			  <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
			  	<a href="index.html" type="button" class="btn pmd-ripple-effect btn-primary btn-block">Submit</a>
			  	<p class="redirection-link">Already registered? <a href="javascript:void(0);" class="register-login">Sign In</a></p>
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