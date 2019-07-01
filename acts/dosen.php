<?php
global $app;

if (!$app) {
   header("Location:../index.php");
}
else if($_SESSION['user']->level!="Administrator"){
    header("location:../index.php");
}

class ControllerDosen extends Controller {
    public function __construct() {
        
    }

    public function index()
    {
        $model = new ModelDosen();
        $view = new ViewDosen();
        $view->index($model->findAll());
    }

    public function login() {
        $model = new ModelDosen();
        $model->login();
    }

    public function logout() {
        $model = new ModelDosen();
        $model->logout();
    }

    public function entry($id = 0) {
        $model = new ModelDosen();
        $view = new ViewDosen();

        $view->entry($model->find($id));
    }
    public function save($id) {
        $model = new ModelDosen();
        $model->save($id);
    }

    public function delete($id = 0) {
        $model = new ModelDosen();
        $model->delete($id);
    }
}

class ModelDosen extends Model {
    public $id = 0;
    public $username = '';
    public $password = '';
    public $level = '';
    public $name = '';
    public $position = '';

    public function delete($id = 0) {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "DELETE FROM educators
                    WHERE id=:id";
            $params = array(
                ':id' => $id
            );

            try {
                $stmt = $app->connection->prepare($sql);
                $stmt->setFetchMode(PDO::FETCH_OBJ);
                $stmt->execute($params);
                
                $stmt->closeCursor();
            }catch (PDOException $ex){
                die($ex->getMessage());
            }
        }

        header("Location:".$app->website."/Dosen/index");
    }

    public function save($id) {
        global $app;

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $level = isset($_POST['level']) ? $_POST['level'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $position = isset($_POST['position']) ? $_POST['position'] : '';

        $message = array();
        if ($username == '') {
            $message[] = "Username harus diisi";
        }

        if ($id > 0) {
            if ($password != ''){
                $sql = "UPDATE educators
                        SET username=:username, password=:password, name=:name, position=:position, level=:level
                        WHERE id=:id";
                $params = array(
                    ':id' => $id,
                    ':username' => $username,
                    ':password' => md5($password),
                    ':name' => $name,
                    ':position' => $position,
                    ':level' => $level
                );
            } else {
                $sql = "UPDATE educators
                        SET username=:username, name=:name, position=:position level=:level
                        WHERE id=:id";
                $params = array(
                    ':id' => $id,
                    ':username' => $username,
                    ':name' => $name,
                    ':position' => $position,
                    ':level' => $level
                );
            }
        } else {
            $sql = "INSERT INTO educators (
                        username, password, name, position, level
                    ) VALUES (
                        :username, :password, :name, :position, :level
                    )";
            $params = array(
                ':username' => $username,
                ':password' => md5($password),
                ':name' => $name,
                ':position' => $position,
                ':level' => $level
            );
        }

        try {
			$stmt = $app->connection->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_OBJ);
			$stmt->execute($params);
			
			$stmt->closeCursor();
		}catch (PDOException $ex){
			die($ex->getMessage());
        }

        header("Location:".$app->website."/Dosen/index?message=".implode('<br>', $message));
    }

    public function find($id) {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "SELECT *
                    FROM educators
                    WHERE id=:id";
            $params = array(
                ':id' => $id
            );

            try {
                $stmt = $app->connection->prepare($sql);
                $stmt->setFetchMode(PDO::FETCH_OBJ);
                $stmt->execute($params);
                
                $result = $stmt->fetch();
                
                $stmt->closeCursor();
            }catch (PDOException $ex){
                die($ex->getMessage());
            }
        } else {
            $result = $this;
        }

        return $result;
    }

    public function findAll() {
        global $app;

        $result = array();

        $sql = "SELECT d.identity_number as nip, d.code as kode, d.name as nama, d.place_of_birth as pob, DATE_FORMAT(d.date_of_birth,'%d/%M/%Y') as dob, d.education_level as pendidikan, d.job as jabatan, d.field_of_expertise as keahlian, d.email1 as email, d.phone1 as phone
                FROM educators d
                ORDER BY nama";
        try {
			$stmt = $app->connection->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_OBJ);
			$stmt->execute();
            
            while (($obj = $stmt->fetch()) == true) {
                $result[] = $obj;
            }
			
			$stmt->closeCursor();
		}catch (PDOException $ex){
			die($ex->getMessage());
        }

        return $result;
    }

    public function login() {
        global $app;
		
		$username = isset($_POST['username']) ? $_POST ['username'] : '';
		$password = isset($_POST['password']) ? $_POST ['password'] : '';
        
		if ($username == '' || $password == '') {
			header("Location:".$app->website);
		}
		
		$sql = "SELECT *
				FROM users
				WHERE username=:username
				AND password=MD5(:password)";
		$params = array(
				':username' => $username,
				':password' => $password
		);
		
		$result = null;
		
		try {
			$stmt = $app->connection->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_OBJ);
			$stmt->execute($params);
			
			$result = $stmt->fetch();
			
			$stmt->closeCursor();
		}catch (PDOException $ex){
			die($ex->getMessage());
        }
        
        if($result) {
			$objUser = new stdClass();
			$objUser->ID = $result->id;
			$objUser->nama = $result->username;
            $objUser->level = $result->level;
            $objUser->ayam = $result->name;
			
			$_SESSION =	array();
			$_SESSION['user'] = $objUser;
			
			header('Location:'.$app->website.'/Beranda/dashboard');
		}else {
			header("Location:".$app->website);
		}
    }
    public function logout() {
        global $app;

		$_SESSION = array();
		
		header('Location:'.$app->website);
    }
}

class ViewDosen extends View {
    public function entry($result) {
        global $app;
?>
    <form action="<?php echo $app->website; ?>/Dosen/save" method="POST">
        <input type="hidden" name="id" value="<?php echo $result->id; ?>">
        <div class="row">
			<div class="col-md-6 col-sm-12">
                <div class="pmd-card pmd-z-depth pmd-card-custom-form">
                    <div class="pmd-card-body">
                        <h1>Dosen(BETA)</h1> 
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="username" class="control-label">Nama Lengkap</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo $result->username; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="password" class="control-label">Tanggal Lahir</label>
                            <input id="password" name="password" class="form-control" type="password" value="<?php echo $result->password; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="name" class="control-label">Tempat Lahir</label>
                            <input id="name" name="name" class="form-control" value="<?php echo $result->name; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="position" class="control-label">Kode</label>
                            <input id="position" name="position" class="form-control" value="<?php echo $result->position; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="position" class="control-label">Kode Alias</label>
                            <input id="position" name="position" class="form-control" value="<?php echo $result->position; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield">
                            <label for="position" class="control-label">Tipe Identitas</label>
                            <select class="form-control" id="identity_type" name="identity_type">
<?php
    $level = array("NIP", "NIK");
    foreach ($level as $v) {
?>
                                <option value="<?php echo $v; ?>" <?php echo ($v == $result->level) ? 'selected' : ''; ?>><?php echo $v; ?></option>
<?php
    }
?>
                            </select>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="position" class="control-label">Kode Alias</label>
                            <input id="position" name="position" class="form-control" value="<?php echo $result->position; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield">
                            <label for="level" class="control-label">Level Akses</label>
                            <select class="form-control" id="level" name="level">
<?php
    $level = array("Administrator", "Dosen", "Mahasiswa");
    foreach ($level as $v) {
?>
                                <option value="<?php echo $v; ?>" <?php echo ($v == $result->level) ? 'selected' : ''; ?>><?php echo $v; ?></option>
<?php
    }
?>
                            </select>
                        </div>
                    </div>
                    <div class="pmd-card-action" style="padding:20px;">
                        <button type="submit" class="btn pmd-ripple-effect btn-success"> Simpan </button>
                        <a href="<?php echo $app->website; ?>/Dosen/index" class="btn pmd-ripple-effect btn-danger"> Batal </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
    }

    public function index($result) {
        global $app;
?>
<div style="margin-top:-70px; margin-bottom:20px;">
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Dosen/entry/0">Tambah</a>
    </div>
<div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th width="100px">Aksi</th>
                    <th>NIP</th>
                    <th>Kode</th>
                    <th>Nama Dosen</th>
                    <!-- <th>Tempat Lahir</th> -->
                    <th>Tanggal Lahir</th>
                    <th>Pendidikan</th>
                    <th>Jabatan</th>
                    <th>Keahlian</th>
                    <th>Email</th>
                    <th>Telpon</th>
                </tr>
            </thead>
            <tbody>
<?php
    foreach ($result as $obj) {
?>
                <tr>
                    <td data-title="Aksi">
                        <a href="<?php echo $app->website; ?>/Dosen/entry/<?php echo $obj->id; ?>">
                        <i class="fas fa-edit fa-2x"></i>
                        </a>
                        <a href="javascript:hapus('<?php echo $app->act; ?>', '<?php echo $obj->id; ?>', '<?php echo $obj->nim; ?>');">
                        <i class="fas fa-trash fa-2x"></i>
                        </a>
                    </td>
                    <td data-title="nip"><?php echo $obj->nip; ?></td>
                    <td data-title="kode"><?php echo $obj->kode; ?></td>
                    <td data-title="namap"><?php echo $obj->nama; ?></td>
                    <!-- <td data-title="pob"><?php echo $obj->pob; ?></td> -->
                    <td data-title="dob"><?php echo $obj->dob; ?></td>
                    <td data-title="pendidikan"><?php echo $obj->pendidikan; ?></td>
                    <td data-title="jabatan"><?php echo $obj->jabatan; ?></td>
                    <td data-title="keahlian"><?php echo $obj->keahlian; ?></td>
                    <td data-title="email"><?php echo $obj->email; ?></td>
                    <td data-title="phone"><?php echo $obj->phone ?></td>
                </tr>
<?php
    }
?>
            </tbody>
        </table>
    </div>
</div>
<?php
    }
}
?>