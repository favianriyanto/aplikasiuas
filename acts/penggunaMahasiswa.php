<?php
global $app;

if (!$app) {
    header("Location:../index.php");
}
if($_SESSION['user']->level!="Sekjur"){
    header("Location:../403.html");
}

class ControllerPenggunaMahasiswa extends Controller
{
    public function __construct()
    { }

    public function index()
    {
        $model = new ModelPenggunaMahasiswa();
        $view = new ViewPenggunaMahasiswa();
        $view->index($model->findAll());
    }

    public function entry($id = 0)
    {
        $model = new ModelPenggunaMahasiswa();
        $view = new ViewPenggunaMahasiswa();

        $view->entry($model->find($id));
    }
    public function save($id)
    {
        $model = new ModelPenggunaMahasiswa();
        $model->save($id);
    }

    public function delete($id = 0)
    {
        $model = new ModelPenggunaMahasiswa();
        $model->delete($id);
    }
}

class ModelPenggunaMahasiswa extends Model
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

        header("Location:" . $app->website . "/PenggunaMahasiswa/index");
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
                WHERE level='Mahasiswa'
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
}

class ViewPenggunaMahasiswa extends View
{
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
                        <th>Terakhir Login</th>
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
                                <a href="javascript:hapus('<?php echo $app->act; ?>', '<?php echo $obj->id; ?>', '<?php echo $obj->username; ?>');">
                                <i class="fas fa-trash fa-2x" style="color:#e74a3b"></i>
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