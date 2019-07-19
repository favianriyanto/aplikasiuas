<?php

global $app;

if (!$app) {
    header("Location:../index.php");
}

class ControllerIsikrs extends Controller
{


    public function krs()
    {
        $model = new ModelIsikrs();
        $view = new ViewIsikrs();
        $view->krs($model->tampil());
    }

    public function pilih()
    {
        $model = new ModelIsikrs();
        $model->krs();
    }
  

    public function isi()
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();
        $view->isi($model->isi());
    }
  
}

class ModelIsikrs extends Model
{
    public function tampil()
    {
        global $app;
        $id = $_SESSION['akademik']->username;

        $result = array();


        $sql = "SELECT nim, courses_code, courses_name, courses_credit, educators_name, teachingcredits_name, courses_year, teachingcredits_id, status
                        FROM krs where nim = $id";


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


    public function krs()
    {
        global $app;
        $nim = $_SESSION['user']->ayam;
        $krs = $_POST['krs'];
        if (isset($_POST['simpan'])) {
            foreach ($krs as $id) {
                $sql = "INSERT INTO krs(nim, courses_code, courses_name, courses_credit, educators_name, teachingcredits_name, courses_year, teachingcredits_id)
                        SELECT m.nim, c.code, c.name, c.credit_unit, e.name, tc.name, c.year, tc.id
                        FROM courses c, educators e, teachingcredits tc, advisorstudents m
                        WHERE c.id = tc.courses_id AND e.id = tc.educators_id AND c.semester=4 AND tc.id='$id' AND m.nama='$nim'";
                try {
                    $stmt = $app->connection->prepare($sql);
                    $stmt->setFetchMode(PDO::FETCH_OBJ);
                    $stmt->execute();

                    $stmt->closeCursor();
                } catch (PDOException $ex) {
                    die($ex->getMessage());
                }
            }
            header("Location:" . $app->website . "/Isikrs/isi");
            exit;
        } else {
            header("Location:" . $app->website . "/Isikrs/isi");
            exit;
        }
    }
    public function isi()
    {
        global $app;

        $result = array();

        $semester = 4;

        if ($semester % 2 == 0) {
            $query = "SELECT tc.id AS id, CONCAT(c.name,' - ', tc.name) AS matkul, c.credit_unit AS sks, CASE c.semester WHEN '0' THEN 'Pilihan' ELSE c.semester END AS semester, c.year AS kurikulum, d.name AS dosen, r.name AS ruang, c.prerequisite AS syarat, t.day_name AS hari, t.start_time AS masuk, MAX(t.end_time) AS keluar
            FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t
            WHERE tc.educators_id = d.id AND tc.courses_id = c.id AND sc.rooms_id = r.id AND sc.teachingcredits_id = tc.id AND tc.educators_id = d.id AND sc.times_id = t.id AND c.semester = 4
            GROUP BY matkul ORDER BY matkul";
        } else {
            $query = "SELECT tc.id AS id,  CONCAT(c.name,' - ', tc.name) AS matkul, c.credit_unit AS sks, CASE c.semester WHEN '0' THEN 'Pilihan' ELSE c.semester END AS semester, c.year AS kurikulum, d.name AS dosen, r.name AS ruang, c.prerequisite AS syarat, t.day_name AS hari, t.start_time AS masuk, MAX(t.end_time) AS keluar
            FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t
            WHERE tc.educators_id = d.id AND tc.courses_id = c.id AND sc.rooms_id = r.id AND sc.teachingcredits_id = tc.id AND tc.educators_id = d.id AND sc.times_id = t.id AND c.semester MOD 2 = 1
            GROUP BY matkul ORDER BY matkul";
        }



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

    
}

class ViewIsikrs extends View
{
  
public function isi($result)
{
    global $app;
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <div>
                            <form action="<?php echo $app->website ?>/Isikrs/pilih" method="post">
                                <table class=" table table-hover table-striped table-bordered " cellspacing="0" width="100%" role="grid" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <td colspan="11">
                                                <!-- <button type="button" class="btn btn-info btn-rounded" data-toggle="modal" data-target="#add-contact">Tambah</button> -->
                                                <!-- <a class="btn btn-primary waves-effect waves-light m-r-10" href="<?php echo $app->website; ?>/Admin/entry/0">Tambah</a> -->
                                                <button type="submit" name="simpan" class="btn btn-success waves-effect waves-light m-r-10">Simpan</button>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th rowspan="1" colspan="1">Mata Kuliah</th>
                                            <th rowspan="1" colspan="1">Syarat</th>
                                            <th rowspan="1" colspan="1">SKS</th>
                                            <th rowspan="1" colspan="1">Semester</th>
                                            <th rowspan="1" colspan="1">Kurikulum</th>
                                            <th rowspan="1" colspan="1">Nama Dosen</th>
                                            <th rowspan="1" colspan="1">Ruangan</th>
                                            <th rowspan="1" colspan="1">Hari</th>
                                            <th rowspan="1" colspan="1">Masuk</th>
                                            <th rowspan="1" colspan="1">Selesai</th>
                                            <th rowspan="1" colspan="1">Pilih</th>

                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $i = 0;
                                        foreach ($result as $obj) {
                                            ?>

                                            <tr>
                                                <td data-title="matkul"><?php echo $obj->matkul ?></td>
                                                <td data-title="syarat"><?php echo $obj->syarat ?></td>
                                                <td data-title="sks"><?php echo $obj->sks ?></td>
                                                <td data-title="semester"><?php echo $obj->semester ?></td>
                                                <td data-title="kurikulum"><?php echo $obj->kurikulum ?></td>
                                                <td data-title="dosen"><?php echo $obj->dosen ?></td>
                                                <td data-title="ruang"><?php echo $obj->ruang ?></td>
                                                <td data-title="hari"><?php echo $obj->hari ?></td>
                                                <td data-title="masuk"><?php echo $obj->masuk ?></td>
                                                <td data-title="keluar"><?php echo $obj->keluar ?></td>
                                                <td data-title="aksi">
                                                    <input name="krs[]" type="checkbox" value="<?php echo $obj->id; ?>" class="checkbox" id="basic_checkbox_<?= $i ?>" />
                                                    <label for="basic_checkbox_<?= $i ?>"></label>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $i = 1;
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
                                                break;

                                            default:
                                                $stat = 'Belum disetujui';
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
