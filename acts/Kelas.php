<?php
global $app;

if (!$app) {
    header("Location:../index.php");
}

class ControllerKelas extends Controller
{
    public function __construct()
    { }

    public function index()
    {
        $model = new ModelKelas();
        $view = new ViewKelas();
        $view->index($model->findAll());
    }

    public function login()
    {
        $model = new ModelKelas();
        $model->login();
    }

    public function logout()
    {
        $model = new ModelKelas();
        $model->logout();
    }

    public function entry($id = 0)
    {
        $model = new ModelKelas();
        $view = new ViewKelas();

        $view->entry($model->find($id));
    }
    public function save($id)
    {
        $model = new ModelKelas();
        $model->save($id);
    }

    public function delete($id = 0)
    {
        $model = new ModelKelas();
        $model->delete($id);
    }
}

class ModelKelas extends Model
{
    public $id = 0;
    public $username = '';
    public $password = '';
    public $level = '';
    public $name = '';
    public $position = '';
    public $matkul = '';

    public function delete($id = 0)
    {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "DELETE FROM advisorstudents
                    WHERE id=:id";
            $params = array(
                ':id' => $id
            );

            try {
                $stmt = $app->connection->prepare($sql);
                $stmt->setFetchMode(PDO::FETCH_OBJ);
                $stmt->execute($params);

                $stmt->closeCursor();
            } catch (PDOException $ex) {
                die($ex->getMessage());
            }
        }

        header("Location:" . $app->website . "/Kelas/index");
    }

    public function save($id)
    {
        global $app;
        $name = isset($_POST['name']) ? $_POST['name'] : '';

        $matkul = isset($_POST['position']) ? $_POST['position'] : '';

        $message = array();
        if ($username == '') {
            $message[] = "Username harus diisi";
        }

        if ($id > 0) {
            if ($password != '') {
                $sql = "UPDATE advisorstudents
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
                $sql = "UPDATE advisorstudents
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
            $sql = "INSERT INTO advisorstudents (
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
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        header("Location:" . $app->website . "/Kelas/index?message=" . implode('<br>', $message));
    }

    public function find($id)
    {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "SELECT *
                    FROM teachingcredits
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
            } catch (PDOException $ex) {
                die($ex->getMessage());
            }
        } else {
            $result = $this;
        }

        return $result;
    }

    public function findAll()
    {
        global $app;

        $result = array();

        $sql = "SELECT CONCAT(c.name ,' - ',tc.name) as matkul, d.name as dosen, count(*) as jumlah
        FROM classstudents cs, teachingcredits tc, educators d, courses c
        Where cs.teachingcredits_id = tc.id and tc.educators_id = d.id AND tc.courses_id = c.id
        GROUP BY matkul, dosen";
        try {
            $stmt = $app->connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();

            while (($obj = $stmt->fetch()) == true) {
                $result[] = $obj;
            }

            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        return $result;
    }

    public function login()
    {
        global $app;

        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($username == '' || $password == '') {
            header("Location:" . $app->website);
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
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        if ($result) {
            $objUser = new stdClass();
            $objUser->ID = $result->id;
            $objUser->nama = $result->username;
            $objUser->level = $result->level;
            $objUser->ayam = $result->name;

            $_SESSION =    array();
            $_SESSION['user'] = $objUser;

            header('Location:' . $app->website . '/Beranda/dashboard');
        } else {
            header("Location:" . $app->website);
        }
    }
    public function logout()
    {
        global $app;

        $_SESSION = array();

        header('Location:' . $app->website);
    }
}

class ViewKelas extends View
{
    public function entry($result)
    {
        global $app;
        ?>
    <form action="<?php echo $app->website; ?>/Kelas/save" method="POST">
        <input type="hidden" name="id" value="<?php echo $result->id; ?>">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="pmd-card pmd-z-depth pmd-card-custom-form">
                    <div class="pmd-card-body">
                        <h1>Mahasiswa</h1>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo $result->username; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="password" class="control-label">Password</label>
                            <input id="password" name="password" class="form-control" type="password" value="<?php echo $result->password; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="name" class="control-label">Nama</label>
                            <input id="name" name="name" class="form-control" value="<?php echo $result->name; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="position" class="control-label">Posisi</label>
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
                        <a href="<?php echo $app->website; ?>/Mahasiswa/index" class="btn pmd-ripple-effect btn-danger"> Batal </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
}

public function index($result)
{
    global $app;
    ?>
    <div style="margin-top:-70px; margin-bottom:20px;">
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Kelas/entry/0">Tambah</a>
    </div>
    <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Aksi</th>
                        <th>Mata Kuliah - Kelas</th>
                        <th>Dosen</th>
                        <th>Jumlah</th>
                        <th>Mahasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $obj) {
                        ?>
                        <tr>
                            <td data-title="Aksi">
                                <a href="<?php echo $app->website; ?>/Kelas/entry/<?php echo $obj->id; ?>">
                                <i class="fas fa-edit fa-2x" style="color:blue"></i>
                                </a>
                                <a href="javascript:hapus('<?php echo $app->act; ?>', '<?php echo $obj->id; ?>', '<?php echo $obj->nim; ?>');">
                                <i class="fas fa-trash fa-2x" style="color:red"></i>
                                </a>
                            </td>
                            <td data-title="matkul"><?php echo $obj->matkul?></td>
                            <!-- <td data-title="kelas"><?php echo $obj->kelas; ?></td> -->
                            <td data-title="dosen"><?php echo $obj->dosen; ?></td>
                            <td data-title="jumlah"><?php echo $obj->jumlah; ?></td>
                            <td data-title="mahasiswa">
                                <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Alproa/index">Mahasiswa</a>
                            </td>
                            
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