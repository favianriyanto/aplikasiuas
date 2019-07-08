<?php
global $app;

if (!$app) {
    header("Location:../index.php");
}

class ControllerIsikrs extends Controller
{
    public function __construct()
    { }

    public function index()
    {
        $model = new ModelIsikrs();
        $view = new ViewIsikrs();
        $view->index($model->findAll());
    }

    public function entry($id = 0)
    {
        $model = new ModelIsikrs();
        $view = new ViewIsikrs();

        $view->entry($model->find($id));
    }
    public function save($id)
    {
        $model = new ModelIsikrs();
        $model->save($id);
    }

    public function delete($id = 0)
    {
        $model = new ModelIsikrs();
        $model->delete($id);
    }
}

class ModelIsikrs extends Model
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

        header("Location:" . $app->website . "/Isikrs/index");
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

        header("Location:" . $app->website . "/Isikrs/index?message=" . implode('<br>', $message));
    }

    public function find($id)
    {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "SELECT *
                    FROM advisorstudents
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

        $sql = "SELECT CONCAT(c.name,' - ',tc.name) as matkul, c.credit_unit as sks, case c.semester when '0' then 'Pilihan' else c.semester end as semester, c.year as kurikulum, d.name as dosen, r.name as ruang, c.prerequisite as syarat, t.day_name as hari, t.start_time as masuk, max(t.end_time) as keluar
        FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t
        Where tc.educators_id = d.id AND tc.courses_id = c.id and sc.rooms_id = r.id and sc.teachingcredits_id = tc.id and tc.educators_id = d.id and sc.times_id = t.id
       group by matkul order by semester";
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
}

class ViewIsikrs extends View
{
    public function entry($result)
    {
        global $app;
        ?>
    <form action="<?php echo $app->website; ?>/Isikrs/save" method="POST">
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
    <div style="margin-bottom:20px;">
        <a class="btn pmd-ripple-effect btn-success" href="#">Simpan</a>
    </div>
    <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Dosen</th>
                        <th>Ruang</th>
                        <th>Syarat</th>
                        <th>Kurikulum</th>
                        <th>Hari</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $obj) {
                        ?>
                        <tr>
                            <td data-title="matkul"><?php echo $obj->matkul; ?></td>
                            <td data-title="sks"><?php echo $obj->sks; ?></td>
                            <td data-title="semester"><?php echo $obj->semester; ?></td>
                            <td data-title="dosen"><?php echo $obj->dosen; ?></td>
                            <td data-title="ruang"><?php echo $obj->ruang; ?></td>
                            <td data-title="syarat"><?php echo $obj->syarat; ?></td>
                            <td data-title="kurikulum"><?php echo $obj->kurikulum; ?></td>
                            <td data-title="hari"><?php echo $obj->hari; ?></td>
                            <td data-title="masuk"><?php echo $obj->masuk; ?></td>
                            <td data-title="keluar"><?php echo $obj->keluar; ?></td>
                            <td data-title="aksi">
                                <input type="checkbox" value="">
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