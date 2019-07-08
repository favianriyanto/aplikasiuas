<?php
global $app;

if (!$app) {
   header("Location:../index.php");
}
else if($_SESSION['user']->level!="Sekjur"){
    header("Location:../403.html");
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
    public $identity_type = '';
    public $identity_number = '';
    public $nidn = '';
    public $code = '';
    public $code_alias = '';
    public $name = '';
    public $sex = '';
    public $active = '';
    public $employment_status = '';
    public $place_of_birth = '';
    public $date_of_birth = '';
    public $adress = '';
    public $job = '';


    public function save($id)
    {
        global $app;

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $identity_type = isset($_POST['identity_type']) ? $_POST['identity_type'] : '';
        $identity_number = isset($_POST['identity_number']) ? $_POST['identity_number'] : '';
        $nidn = isset($_POST['nidn']) ? $_POST['nidn'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $code_alias = isset($_POST['code_alias']) ? $_POST['code_alias'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $sex = isset($_POST['sex']) ? $_POST['sex'] : '';
        $active = isset($_POST['active']) ? $_POST['active'] : '';
        $employment_status = isset($_POST['employment_status']) ? $_POST['employment_status'] : '';
        $place_of_birth = isset($_POST['place_of_birth']) ? $_POST['place_of_birth'] : '';
        $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
        $address = isset($_POST['adress']) ? $_POST['adress'] : '';
        $job = isset($_POST['job']) ? $_POST['job'] : '';


                $sql = "UPDATE educators
                        SET identity_type=:identity_type, identity_number=:identity_number, nidn=:nidn,
                        code=:code, code_alias=:code_alias, name=:name, sex=:sex, active=:active, employment_status=:employment_status,
                        place_of_birth=:place_of_birth, date_of_birth=:date_of_birth, address=:address, job=:job
                        WHERE id=:id";
                $params = array(
                    ':id' => $id,
                    ':identity_type' => $identity_type,
                    ':identity_number' => $identity_number,
                    ':nidn' => $nidn,
                    ':code' => $code,
                    ':code_alias' => $code_alias,
                    ':name' => $name,
                    ':sex' => $sex,
                    ':active' => $active,
                    ':employment_status' => $employment_status,
                    ':place_of_birth' => $place_of_birth,
                    ':date_of_birth' => $date_of_birth,
                    ':address' => $adress,
                    ':job' => $job
                );

        try {
            $stmt = $app->connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($params);

            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        header("Location:".$app->website."/Dosen/index?message=".implode('<br>', $message));
    }

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

        $sql = "SELECT d.id as id, d.identity_number as nip, d.code as kode, d.name as nama, d.place_of_birth as pob, DATE_FORMAT(d.date_of_birth,'%d/%M/%Y') as dob, d.education_level as pendidikan, d.job as jabatan, d.field_of_expertise as keahlian, d.email1 as email, d.phone1 as phone
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
                        <div>
                            <label for="name" class="control-label">Nama Lengkap</label>
                            <input type="text" id="username" name="name" class="form-control" value="<?php echo $result->name; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="date_of_birth" class="control-label">Tanggal Lahir</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $result->date_of_birth; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="place_of_birth" class="control-label">Tempat Lahir</label>
                            <input id="place_of_birth" name="place_of_birth" class="form-control" value="<?php echo $result->place_of_birth; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="sex" class="control-label">Jenis Kelamin</label>
                            <select class="form-control" id="sex" name="sex">
                        <?php
                            $sex = array("Laki-laki", "Perempuan");
                            foreach ($sex as $v) {
                        ?>
                            <option value="<?php echo $v; ?>" <?php echo ($v == $result->sex) ? 'selected' : ''; ?>><?php echo $v; ?></option>
                        <?php
                            }
                        ?>
                            </select>
                        </div>
                        <div>
                            <label for="code" class="control-label">Kode</label>
                            <input id="code" name="code" class="form-control" value="<?php echo $result->code; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="code_alias" class="control-label">Kode Alias</label>
                            <input id="code_alias" name="code_alias" class="form-control" value="<?php echo $result->code_alias; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="identity_type" class="control-label">Tipe Identitas</label>
                            <select class="form-control" id="identity_type" name="identity_type">
                        <?php
                            $identity_type = array("NIP", "NIK");
                            foreach ($identity_type as $v) {
                        ?>
                            <option value="<?php echo $v; ?>" <?php echo ($v == $result->identity_type) ? 'selected' : ''; ?>><?php echo $v; ?></option>
                        <?php
                            }
                        ?>
                            </select>
                        </div>
                        <div>
                            <label for="job" class="control-label">Job</label>
                            <input id="job" name="job" class="form-control" value="<?php echo $result->job; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="active" class="control-label">Status</label>
                            <select class="form-control" id="active" name="active">
                        <?php
                            $active = array("Aktif", "Tugas Belajar", "Tidak Aktif");
                            foreach ($active as $v) {
                        ?>
                             <option value="<?php echo $v; ?>" <?php echo ($v == $result->active) ? 'selected' : ''; ?>><?php echo $v; ?></option>
                        <?php
                            }
                        ?>
                            </select>
                        </div>
                        <div>
                            <label for="adress" class="control-label">Alamat</label>
                            <input id="adress" name="adress" class="form-control" value="<?php echo $result->address; ?>"><span class="pmd-textfield-focused"></span>
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
<div style="margin-bottom:20px;">
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
                        <a onClick="javascript:return confirm('are you sure you want to delete this?');" href="<?php echo $app->website; ?>/Pengguna/delete/<?php echo $obj->id; ?>">
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