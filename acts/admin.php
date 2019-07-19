<?php

global $app;

if (!$app) {
    header("Location:../index.php");
}

class ControllerAdmin extends Controller
{

 
    public function hapus($id = 0)
    {
        $model = new ModelAdmin();
        $model->hapus($id);
    }
    public function approve($id = 0)
    {
        $model = new ModelAdmin();
        $model->setuju($id);
    }

    public function batal($id = 0)
    {
        $model = new ModelAdmin();
        $model->batal($id);
    }
    public function mhspa()
    {
        $model = new ModelAdmin();
        $view = new ViewAdmin();
        $view->mhspa($model->mhspa());
    }

    public function krs($id = 0)
    {
        $model = new ModelAdmin();
        $view = new ViewAdmin();
        $view->krs($model->tampil($id));
    }

    
}

class ModelAdmin extends Model
{

    public $id = 0;
    public $username = '';
    public $password = '';
    public $departements_id = '';
    public $name = '';
    public $level = '';
    public $allowed = '';
    public $identity_number = '';
    public $position = '';
    public $login_at = '';


    public function hapus($id)
    {
        global $app;
        $id = $app->id;
        $nim = $_SESSION['nim'];

        $query = "DELETE from krs where teachingcredits_id = $id and nim = $nim";

        try {
            $stmt = $app->connection->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();

            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
        header("Location:" . $app->website . "/Admin/krs/" . $_SESSION['nim']);
        unset($_SESSION['nim']);
        exit;
    }
    public function batal($id)
    {
        global $app;
        $id = $app->id;
        $nim = $_SESSION['nim'];

        $query = "UPDATE krs set status = 0 where teachingcredits_id = $id and nim = $nim";

        try {
            $stmt = $app->connection->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();

            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
        header("Location:" . $app->website . "/Admin/krs/" . $_SESSION['nim']);
        unset($_SESSION['nim']);
        exit;
    }
    public function setuju($id)
    {
        global $app;
        $id = $app->id;
        $nim = $_SESSION['nim'];

        $query = "UPDATE krs set status = 1 where teachingcredits_id = $id and nim = $nim";
        try {
            $stmt = $app->connection->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();

            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
        header("Location:" . $app->website . "/Admin/krs/" . $_SESSION['nim']);
        unset($_SESSION['nim']);
        exit;
    }
    public function mhspa()
    {
        global $app;

        $result = array();

        $query = "SELECT *
              FROM users WHERE level = 'Mahasiswa'
              ORDER BY username";

        try {
            $stmt = $app->connection->prepare($query);
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

    public function tampil($id)
    {
        global $app;

        $result = array();
        $id = $app->id;

        $sql = "SELECT nim, courses_code, courses_name, courses_credit, educators_name, teachingcredits_name, courses_year, teachingcredits_id, status
                FROM krs";
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

class ViewAdmin extends View
{
  
public function mhspa($result)
{
    global $app;
    ?>

    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div >
                            <table class="table">
                                <thead>
                                    <tr role="row">
                                        <th rowspan="1" colspan="1">NIM</th>
                                        <th rowspan="1" colspan="1">Nama</th>
                                        <th rowspan="1" colspan="1">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">NIM</th>
                                        <th rowspan="1" colspan="1">Nama</th>
                                        <th rowspan="1" colspan="1">Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    foreach ($result as $obj) {
                                        ?>
                                        <tr>
                                            <td><?php echo $obj->username ?></td>
                                            <td><?php echo $obj->name ?></td>
                                            <td class="text-nowrap">
                                                <a href="<?php echo $app->website ?>/Admin/krs/<?php echo $obj->username; ?>" data-toggle="tooltip" data-original-title="Lihat"> <i class="fa fa-search text-inverse m-r-10"></i> </a>

                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

<?php
}

public function krs($result)
{
    global $app;

    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table">
                            <thead>
                                <tr>
                                    <th rowspan="1" colspan="1">No</th>
                                    <th rowspan="1" colspan="1">Kode MK</th>
                                    <th rowspan="1" colspan="1">Nama MK</th>
                                    <th rowspan="1" colspan="1">SKS</th>
                                    <th rowspan="1" colspan="1">Nama Dosen</th>
                                    <th rowspan="1" colspan="1">Kelas</th>
                                    <th rowspan="1" colspan="1">Kurikulum</th>
                                    <th rowspan="1" colspan="1">Status</th>
                                    <th rowspan="1" colspan="1">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $i = 1;
                                $sudah = false;
                                foreach ($result as $obj) {
                                    ?>

                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?php echo $obj->courses_code ?></td>
                                        <td><?php echo $obj->courses_name ?></td>
                                        <td><?php echo $obj->courses_credit ?></td>
                                        <td><?php echo $obj->educators_name ?></td>
                                        <td><?php echo $obj->teachingcredits_name ?></td>
                                        <td><?php echo $obj->courses_year ?></td>




                                        <?php
                                        switch ($obj->status) {
                                            case 1:
                                                $stat = 'Sudah disetujui';
                                                $sudah = true;
                                                break;

                                            default:
                                                $stat = 'Belum disetujui';
                                                $sudah = false;
                                                break;
                                        }
                                        ?>
                                        <?php
                                        if ($stat == 'Sudah disetujui') {
                                            ?>
                                            <td><span class="label label-success"><?php echo $stat ?></span></td>
                                        <?php
                                        } else {
                                            ?>
                                            <td><span class="label label-danger"><?php echo $stat ?></span></td>
                                        <?php
                                        }
                                        ?>
                                        <td>
                                            <a href="<?php echo $app->website ?>/Admin/approve/<?php echo $obj->teachingcredits_id;
                                                                                                $_SESSION['nim'] = $obj->nim ?>" data-toggle="tooltip" data-original-title="Setujui"> <i class="fa fa-check text-inverse m-r-10"></i> </a>
                                            <?php
                                            if ($sudah) {

                                                ?>

                                                <a href="<?php echo $app->website ?>/Admin/batal/<?php echo $obj->teachingcredits_id;
                                                                                                    $_SESSION['nim'] = $obj->nim ?>" data-toggle="tooltip" data-original-title="Batalkan"> <i class="fa fa-undo text-inverse m-r-10"></i> </a>
                                            <?php
                                            }

                                            ?>
                                            <a href="<?php echo $app->website ?>/Admin/hapus/<?php echo $obj->teachingcredits_id;
                                                                                                $_SESSION['nim'] = $obj->nim ?>" data-toggle="tooltip" data-original-title="Hapus"> <i class="fa fa-trash text-danger m-r-10"></i> </a>

                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php
}

}
