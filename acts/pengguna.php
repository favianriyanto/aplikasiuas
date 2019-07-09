<?php
global $app;

if (!$app) {
    header("Location:../beranda.php");
}
if($_SESSION['user']->level!="Sekjur"){
    header("Location:../403.html");
}

class ControllerPengguna extends Controller
{
    public function __construct()
    { }

    public function index()
    {
        $model = new ModelPengguna();
        $view = new ViewPengguna();
        $view->index($model->findAll());
    }

    public function login()
    {
        $model = new ModelPengguna();
        $model->login();
    }

    public function logout()
    {
        $model = new ModelPengguna();
        $model->logout();
    }

    public function entry($id = 0)
    {
        $model = new ModelPengguna();
        $view = new ViewPengguna();

        $view->entry($model->find($id));
    }
    public function save($id)
    {
        $model = new ModelPengguna();
        $model->save($id);
    }

    public function delete($id = 0)
    {
        $model = new ModelPengguna();
        $model->delete($id);
    }
}

class ModelPengguna extends Model
{
    public $id = 0;
    public $username = '';
    public $password = '';
    public $level = '';
    public $name = '';
    public $position = '';

    public function delete($id = 0)
    {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "DELETE FROM users
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

        header("Location:" . $app->website . "/Pengguna/index");
    }

    public function save($id)
    {
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
            if ($password != '') {
                $sql = "UPDATE users
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
                $sql = "UPDATE users
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
            $sql = "INSERT INTO users (
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

        header("Location:" . $app->website . "/Pengguna/index?message=" . implode('<br>', $message));
    }

    public function find($id)
    {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "SELECT *
                    FROM users
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

        $sql = "SELECT *
                FROM users
                ORDER BY username";
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
				FROM users a, times_krs b
				WHERE a.username=:username
				AND a.password=MD5(:password)";
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
            $objUser->kambing = $result->jadwalisikrs;
            $objUser->katak = $result->jumlahmhs;

            $_SESSION = array();
            $_SESSION['user'] = $objUser;

            header('Location:' . $app->website . '/Beranda/dashboard');
        } else {
            header("Location:" . $app->website . '/errorlogin.html');
        }
    }
    public function logout()
    {
        global $app;

        $_SESSION = array();

        header('Location:' . $app->website);
    }
}

class ViewPengguna extends View
{
    public function entry($result)
    {
        global $app;
        ?>
    <form action="<?php echo $app->website; ?>/Pengguna/save" method="POST">
        <input type="hidden" name="id" value="<?php echo $result->id; ?>">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="pmd-card pmd-z-depth pmd-card-custom-form">
                    <div class="pmd-card-body">
                        <h1>Pengguna</h1><br><br>
                        <div>
                            <label for="username" class="control-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo $result->username; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="password" class="control-label">Password</label>
                            <input id="password" placeholder="*Masukkan password jika ingin mengubah" onfocus="this.value=''" onblur="this.value=''" name="password" class="form-control" type="password" value="<?php echo $result->password; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="name" class="control-label">Nama</label>
                            <input id="name" name="name" class="form-control" value="<?php echo $result->name; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="position" class="control-label">Posisi</label>
                            <input id="position" name="position" class="form-control" value="<?php echo $result->position; ?>"><span class="pmd-textfield-focused"></span>
                        </div>
                        <div>
                            <label for="level" class="control-label">Level Akses</label>
                            <select class="form-control" id="level" name="level">
                                <?php
                                $level = array("Sekjur", "Mahasiswa");
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
                        <a href="<?php echo $app->website; ?>/Pengguna/index" class="btn pmd-ripple-effect btn-danger"> Batal </a>
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
    <div style="margin-bottom:20px;">
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Pengguna/entry/0">Tambah</a>
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Pengguna/index">Semua</a>
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/PenggunaSekjur/index">Sekjur</a>
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/PenggunaMahasiswa/index">Mahasiswa</a>
    </div>
    <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Aksi</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>Level Akses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $obj) {
                        ?>
                        <tr>
                            <td data-title="Aksi">
                                <a href="<?php echo $app->website; ?>/Pengguna/entry/<?php echo $obj->id; ?>">
                                <i class="fas fa-edit fa-2x" style="color:#4e73df"></i>
                                </a>
                                <a onClick="javascript:return confirm('Apakah anda ingin menghapus data ini?');" href="<?php echo $app->website; ?>/Pengguna/delete/<?php echo $obj->id; ?>">
                                <i class="fas fa-trash fa-2x" style="color:#e74a3b"></i>
                                </a>
                            </td>
                            <td data-title="Username"><?php echo $obj->username; ?></td>
                            <td data-title="Nama"><?php echo $obj->name; ?></td>
                            <td data-title="Posisi"><?php echo $obj->position; ?></td>
                            <td data-title="Level Akses">
                                <a href="#" <?php if ($obj->level == 'Sekjur'){?>class="btn btn-icon-split btn-primary"<?php } else {?>class="btn btn-icon-split btn-success"<?php } ?>>
                                <span class="icon text-white-50">
                                </span>
                                <span class="text"><?php echo $obj->level; ?></span>
                                </a></td>
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