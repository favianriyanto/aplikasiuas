<?php
global $app;

if (!$app) {
    header("Location:../beranda.php");
}
if($_SESSION['user']->level!="Sekjur"){
    header("Location:../403.html");
}

class ControllerPengaturanKRS extends Controller
{
    public function __construct()
    { }

    public function index()
    {
        $model = new ModelPengaturanKRS();
        $view = new ViewPengaturanKRS();
        $view->index($model->findAll());
    }

    public function entry($id = 0)
    {
        $model = new ModelPengaturanKRS();
        $view = new ViewPengaturanKRS();

        $view->entry($model->find($id));
    }
    public function save($id)
    {
        $model = new ModelPengaturanKRS();
        $model->save($id);
    }
}

class ModelPengaturanKRS extends Model
{
    public $id = 0;
    public $jadwalisikrs = '';

    public function save($id)
    {
        global $app;

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $jadwalisikrs = isset($_POST['jadwalisikrs']) ? $_POST['jadwalisikrs'] : '';

                $sql = "UPDATE times_krs
                        SET jadwalisikrs=:jadwalisikrs
                        WHERE id=:id";
                $params = array(
                    ':id' => $id,
                    ':jadwalisikrs' => $jadwalisikrs);

        try {
            $stmt = $app->connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($params);

            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        header("Location:" . $app->website . "/beranda/dashboard");
    }

    public function find($id)
    {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "SELECT *
                    FROM times_krs
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
                FROM times_krs";
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

class ViewPengaturanKRS extends View
{
    public function entry($result)
    {
        global $app;
        $date = $result->jadwalisikrs;
        ?>
    <form action="<?php echo $app->website; ?>/PengaturanKRS/save" method="POST">
        <input type="hidden" name="id" value="<?php echo $result->id; ?>">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="pmd-card pmd-z-depth pmd-card-custom-form">
                    <div class="pmd-card-body">
                        <h1>Pengaturan jadwal batas isi KRS</h1><br><br>
                        <h5>*Set jadwal yang telah lewat untuk menokaktifkan timer</h5><br>
                            <div class="row">
                                <div class='col-sm-4'>
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker1'>
                                            <input type='text' id="jadwalisikrs" name="jadwalisikrs" class="form-control" value="<?php echo $date?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="pmd-card-action" style="padding:20px;">
                        <button type="submit" class="btn pmd-ripple-effect btn-success"> Simpan </button>
                        <a href="<?php echo $app->website; ?>/beranda/dashboard" class="btn pmd-ripple-effect btn-danger"> Batal </a>
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
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/PengaturanKRS/entry/0">Tambah</a>
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/PengaturanKRS/index">Semua</a>
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/PengaturanKRSAdmin/index">Admin</a>
        <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/PengaturanKRSMahasiswa/index">Mahasiswa</a>
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
                        <th>Terakhir Login</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $obj) {
                        ?>
                        <tr>
                            <td data-title="Aksi">
                                <a href="<?php echo $app->website; ?>/PengaturanKRS/entry/<?php echo $obj->id; ?>">
                                <i class="fas fa-edit fa-2x"></i>
                                </a>
                                <a onClick="javascript:return confirm('are you sure you want to delete this?');" href="<?php echo $app->website; ?>/PengaturanKRS/delete/<?php echo $obj->id; ?>">
                                <i class="fas fa-trash fa-2x"></i>
                                </a>
                            </td>
                            <td data-title="Username"><?php echo $obj->username; ?></td>
                            <td data-title="Nama"><?php echo $obj->name; ?></td>
                            <td data-title="Posisi"><?php echo $obj->position; ?></td>
                            <td data-title="Level Akses"><?php echo $obj->level; ?></td>
                            <td data-title="Terakhir Login"><?php echo $obj->login_at; ?></td>
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