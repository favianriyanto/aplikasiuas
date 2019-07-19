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
    public function dashboard($id = 0)
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();

        $view->dashboard($model->ambil($id));
    }
    public function ganti($id = 0)
    {
        $model = new ModelIsikrs();
        $model->ganti($id);
    }
    public function save($id = 0)
    {
        $model = new ModelIsikrs();
        $model->save($id);
    }
    public function profil($id = 0)
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();
        $view->profil($model->ambil($id));
    }
    public function jadwal()
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();
        $view->jadwal($model->pribadi(), $model->semua());
    }

    public function isi()
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();
        $view->isi($model->isi());
    }
    public function kurikulum()
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();
        $view->kurikulum($model->kurikulum11(), $model->kurikulum15());
    }
    public function riwayat()
    {
        $view = new ViewIsikrs();
        $model = new ModelIsikrs();
        $view->riwayat($model->pribadi());
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
        $nim = $_SESSION['user']->nama;
        $krs = $_POST['krs'];
        if (isset($_POST['simpan'])) {
            foreach ($krs as $id) {
                $sql = "INSERT INTO krs(nim, courses_code, courses_name, courses_credit, educators_name, teachingcredits_name, courses_year, teachingcredits_id)
                        SELECT m.nim, c.code, c.name, c.credit_unit, e.name, tc.name, c.year, tc.id
                        FROM courses c, educators e, teachingcredits tc, advisorstudents m
                        WHERE c.id = tc.courses_id AND e.id = tc.educators_id AND c.semester=4 AND tc.id='$id' AND m.nim='$nim'";
                try {
                    $stmt = $app->connection->prepare($sql);
                    $stmt->setFetchMode(PDO::FETCH_OBJ);
                    $stmt->execute();

                    $stmt->closeCursor();
                } catch (PDOException $ex) {
                    die($ex->getMessage());
                }
            }
            Flasher::setFlash('berhasil', 'diubah', 'success');
            header("Location:" . $app->website . "/Isikrs/isi");
            exit;
        } else {
            Flasher::setFlash('gagal', 'diubah', 'danger');
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

    public function kurikulum15()
    {
        global $app;

        $result = array();

        $query = "SELECT CONCAT(c.name,' - ', tc.name) AS matkul, c.credit_unit AS sks, CASE c.semester WHEN '0' THEN 'Pilihan' ELSE c.semester END AS semester,c.code AS kodemk, c.year AS kurikulum, d.name AS dosen, r.name AS ruang, c.prerequisite AS syarat, t.day_name AS hari, t.start_time AS masuk, MAX(t.end_time) AS keluar
        FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t
        WHERE tc.educators_id = d.id AND tc.courses_id = c.id AND sc.rooms_id = r.id AND sc.teachingcredits_id = tc.id AND tc.educators_id = d.id AND sc.times_id = t.id AND c.year=2015 
        GROUP BY matkul ORDER BY semester";

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

    public function kurikulum11()
    {
        global $app;

        $result = array();

        $query = "SELECT CONCAT(c.name,' - ', tc.name) AS matkul, c.credit_unit AS sks, CASE c.semester WHEN '0' THEN 'Pilihan' ELSE c.semester END AS semester, c.code AS kodemk, c.year AS kurikulum, d.name AS dosen, r.name AS ruang, c.prerequisite AS syarat, t.day_name AS hari, t.start_time AS masuk, MAX(t.end_time) AS keluar
        FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t
        WHERE tc.educators_id = d.id AND tc.courses_id = c.id AND sc.rooms_id = r.id AND sc.teachingcredits_id = tc.id AND tc.educators_id = d.id AND sc.times_id = t.id AND c.year=2011 
        GROUP BY matkul ORDER BY semester";

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


    public function semua()
    {
        global $app;

        $result = array();

        $query = "SELECT CONCAT(c.name,' - ', tc.name) AS matkul, c.credit_unit AS sks, CASE c.semester WHEN '0' THEN 'Pilihan' ELSE c.semester END AS semester, c.year AS kurikulum, d.name AS dosen, r.name AS ruang, c.prerequisite AS syarat, t.day_name AS hari, t.start_time AS masuk, MAX(t.end_time) AS keluar
        FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t
        WHERE tc.educators_id = d.id AND tc.courses_id = c.id AND sc.rooms_id = r.id AND sc.teachingcredits_id = tc.id AND tc.educators_id = d.id AND sc.times_id = t.id
        GROUP BY matkul ORDER BY matkul";

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

    public function pribadi()
    {
        global $app;

        $name = isset($_SESSION['akademik']->username) ? $_SESSION['akademik']->username : '';
        $result = array();

        $query = "SELECT CONCAT(c.name,' - ', tc.name) AS matkul, c.credit_unit AS sks, CASE c.semester WHEN '0' THEN 'Pilihan' ELSE c.semester END AS semester, c.year AS kurikulum, d.name AS dosen, r.name AS ruang, c.prerequisite AS syarat, t.day_name AS hari, t.start_time AS masuk, MAX(t.end_time) AS keluar
        FROM teachingcredits tc, educators d, courses c, rooms r, schedules sc, times t, classstudents cs
        WHERE tc.id=cs.teachingcredits_id AND tc.educators_id = d.id AND tc.courses_id = c.id AND sc.rooms_id = r.id AND sc.teachingcredits_id = tc.id AND tc.educators_id = d.id AND sc.times_id = t.id AND cs.nim = upper(:name)
        GROUP BY matkul ORDER BY SEMESTER";

        $params = array(
            ':name' => $name
        );

        try {
            $stmt = $app->connection->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($params);

            while (($obj = $stmt->fetch()) == true) {
                $result[] = $obj;
            }
            $stmt->closeCursor();
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
        return $result;
    }

    public function ambil($id)
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

    public function ganti($id)
    {
        global $app;
        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $old = isset($_POST['oldpass']) ? $_POST['oldpass'] : '';
        $new = isset($_POST['newpass']) ? $_POST['newpass'] : '';
        $konf = isset($_POST['konfrpass']) ? $_POST['konfrpass'] : '';
        $test = $_SESSION['akademik']->password;
        $a = md5($old);
        if ($id > 0) {
            if ($test == $a) {
                if ($new == $konf) {
                    $sql = "UPDATE users
                        SET  password=:password
                        WHERE id=:id";
                    $params = array(
                        ':id' => $id,
                        ':password' => md5($new)
                    );

                    try {
                        $stmt = $app->connection->prepare($sql);
                        $stmt->setFetchMode(PDO::FETCH_OBJ);
                        $stmt->execute($params);

                        $stmt->closeCursor();
                    } catch (PDOException $ex) {
                        die($ex->getMessage());
                    }

                    $query = "SELECT *
                    FROM users
                    WHERE id=:id";
                    $params = array(
                        ':id' => $id,
                    );
                    $result = null;

                    try {
                        $stmt = $app->connection->prepare($query);
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
                        $objUser->username = $result->username;
                        $objUser->password = $result->password;
                        $objUser->nama = $result->name;
                        $objUser->level = $result->level;
                        $objUser->loginat = $result->login_at;
                        $objUser->identity_number = $result->identity_number;
                        $objUser->departement = $result->departements_id;
                        $objUser->posisi = $result->position;
                        $objUser->allowed = $result->allowed;

                        if ($_SESSION['akademik']->ID == $objUser->ID) {
                            $_SESSION = array();
                            $_SESSION['akademik'] = $objUser;
                        }
                    }
                    Flasher::setFlash('berhasil', 'diubah', 'success');
                    header("Location:" . $app->website . "/Isikrs/profil/" . $_SESSION['akademik']->ID);
                    exit;
                } else {
                    Flasher::setFlash('gagal', 'diubah', 'danger');
                    header("Location:" . $app->website . "/Isikrs/profil/" . $_SESSION['akademik']->ID);
                    exit;
                }
            } else {
                Flasher::setFlash('gagal', 'diubah', 'danger');
                header("Location:" . $app->website . "/Isikrs/profil/" . $_SESSION['akademik']->ID);
                exit;
            }
        }

        // header("Location:" . $app->website . "/Isikrs/profil/" . $_SESSION['akademik']->ID);
        // // header("Location:" . $app->website . "/Isikrs/login");

        // return $new;
    }
    public function save($id)
    {
        global $app;

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $level = isset($_POST['level']) ? $_POST['level'] : '';
        $departements_id = isset($_POST['departements_id']) ? $_POST['departements_id'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $posisi = isset($_POST['position']) ? $_POST['position'] : '';
        $identity = isset($_POST['identity_number']) ? $_POST['identity_number'] : '';
        $allowed = isset($_POST['allowed']) ? $_POST['allowed'] : '';
        $login_at = isset($_POST['login_at']) ? $_POST['login_at'] : '';

        if ($username == '') {
            Flasher::setFlash('gagal', 'diubah', 'danger');
            header("Location:" . $app->website . "/Isikrs/dashboard/" . $_SESSION['akademik']->ID);
            exit;
        } else {
            if ($id > 0) {
                if ($password != '') {
                    $sql = "UPDATE users
                        SET departements_id=:departements_id, username=:username, password=:password, name=:name, level=:level, allowed=:allowed, identity_number=:identity,  position=:position, login_at=:login_at
                        WHERE id=:id";
                    $params = array(
                        ':id' => $id,
                        ':departements_id' => $departements_id,
                        ':username' => $username,
                        ':password' => md5($password),
                        ':name' => $name,
                        ':level' => $level,
                        ':allowed' => $allowed,
                        ':identity' => $identity,
                        ':position' => $posisi,
                        ':login_at' => $login_at
                    );
                    try {
                        $stmt = $app->connection->prepare($sql);
                        $stmt->setFetchMode(PDO::FETCH_OBJ);
                        $stmt->execute($params);

                        $stmt->closeCursor();
                    } catch (PDOException $ex) {
                        die($ex->getMessage());
                    }
                    Flasher::setFlash('berhasil', 'diubah', 'success');
                    header("Location:" . $app->website . "/Isikrs/pengguna");
                    exit;
                } else {
                    $sql = "UPDATE users
                        SET departements_id=:departements_id, username=:username, name=:name, level=:level, allowed=:allowed, identity_number=:identity,  position=:position, login_at=:login_at
                        WHERE id=:id";
                    $params = array(
                        ':id' => $id,
                        ':departements_id' => $departements_id,
                        ':username' => $username,
                        ':name' => $name,
                        ':level' => $level,
                        ':allowed' => $allowed,
                        ':identity' => $identity,
                        ':position' => $posisi,
                        ':login_at' => $login_at
                    );
                    try {
                        $stmt = $app->connection->prepare($sql);
                        $stmt->setFetchMode(PDO::FETCH_OBJ);
                        $stmt->execute($params);

                        $stmt->closeCursor();
                    } catch (PDOException $ex) {
                        die($ex->getMessage());
                    }
                    $query = "SELECT *
                FROM users
                WHERE username=:username";
                    $params = array(
                        ':username' => $username,
                    );

                    $result = null;

                    try {
                        $stmt = $app->connection->prepare($query);
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
                        $objUser->username = $result->username;
                        $objUser->password = $result->password;
                        $objUser->nama = $result->name;
                        $objUser->level = $result->level;
                        $objUser->loginat = $result->login_at;
                        $objUser->identity_number = $result->identity_number;
                        $objUser->departement = $result->departements_id;
                        $objUser->posisi = $result->position;
                        $objUser->allowed = $result->allowed;

                        if ($_SESSION['akademik']->ID == $objUser->ID) {
                            $_SESSION = array();
                            $_SESSION['akademik'] = $objUser;
                        }
                    }
                    Flasher::setFlash('berhasil', 'diubah', 'success');
                    header("Location:" . $app->website . "/Isikrs/profil/" . $_SESSION['akademik']->ID);
                    exit;
                }
            } else {
                $sql = "INSERT INTO users (
                        id, departements_id, username, password, name, level, allowed, identity_number,  position, login_at
                    ) VALUES (
                       :id, :departements_id, :username, :password, :name, :level, :allowed, :identity,  :position, :login_at
                    )";
                $params = array(
                    ':id' => $id,
                    ':departements_id' => $departements_id,
                    ':username' => $username,
                    ':password' => md5($password),
                    ':name' => $name,
                    ':level' => $level,
                    ':allowed' => $allowed,
                    ':identity' => $identity,
                    ':position' => $posisi,
                    ':login_at' => $login_at
                );
                try {
                    $stmt = $app->connection->prepare($sql);
                    $stmt->setFetchMode(PDO::FETCH_OBJ);
                    $stmt->execute($params);

                    $stmt->closeCursor();
                } catch (PDOException $ex) {
                    die($ex->getMessage());
                }
                Flasher::setFlash('berhasil', 'ditambahkan', 'success');
                header("Location:" . $app->website . "/Isikrs/pengguna");
                exit;
            }
        }
        // header("Location:" . $app->website . "/Admin/dashboard/" . $_SESSION['akademik']->ID);
        // exit;
        // // header("Location:" . $app->website . "/Admin/login");


        return $username;
    }
}

class ViewIsikrs extends View
{
    public function tampil()
    {
        global $app;
        ?>

<?php
}



public function jadwal($pribadi, $semua)
{
    global $app;
    ?>
    <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#pribadi" role="tab">Pribadi</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#semua" role="tab">Semua</a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!--second tab-->
            <div class="tab-pane active" id="pribadi" role="tabpanel">
                <div class="card-body">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <div id="example23_wrapper" class="dataTables_wrapper">
                                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example23_info" style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Mata Kuliah: activate to sort column ascending" style="width: 291px;">Mata Kuliah</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Syarat: activate to sort column ascending" style="width: 426px;">Syarat</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="SKS: activate to sort column ascending" style="width: 224px;">SKS</th>
                                                    <th class="sorting_desc" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Semester: activate to sort column ascending" style="width: 112px;" aria-sort="descending">Semester</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Kurikulum: activate to sort column ascending" style="width: 202px;">Kurikulum</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Nama Dosen: activate to sort column ascending" style="width: 163px;">Nama Dosen</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Ruangan: activate to sort column ascending" style="width: 163px;">Ruangan</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Hari: activate to sort column ascending" style="width: 163px;">Hari</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Masuk: activate to sort column ascending" style="width: 163px;">Masuk</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example23" rowspan="1" colspan="1" aria-label="Keluar: activate to sort column ascending" style="width: 163px;">Selesai</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
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
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                foreach ($pribadi as $obj) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $obj->matkul ?></td>
                                                        <td><?php echo $obj->syarat ?></td>
                                                        <td><?php echo $obj->sks ?></td>
                                                        <td><?php echo $obj->semester ?></td>
                                                        <td><?php echo $obj->kurikulum ?></td>
                                                        <td><?php echo $obj->dosen ?></td>
                                                        <td><?php echo $obj->ruang ?></td>
                                                        <td><?php echo $obj->hari ?></td>
                                                        <td><?php echo $obj->masuk ?></td>
                                                        <td><?php echo $obj->keluar ?></td>
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
            </div>
            <div class="tab-pane" id="semua" role="tabpanel">
                <div class="card-body">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <div id="example22_wrapper" class="dataTables_wrapper">
                                        <table id="example22" class="display nowrap table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example22_info" style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Mata Kuliah: activate to sort column ascending" style="width: 291px;">Mata Kuliah</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Syarat: activate to sort column ascending" style="width: 426px;">Syarat</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="SKS: activate to sort column ascending" style="width: 224px;">SKS</th>
                                                    <th class="sorting_desc" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Semester: activate to sort column ascending" style="width: 112px;" aria-sort="descending">Semester</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Kurikulum: activate to sort column ascending" style="width: 202px;">Kurikulum</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Nama Dosen: activate to sort column ascending" style="width: 163px;">Nama Dosen</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Ruangan: activate to sort column ascending" style="width: 163px;">Ruangan</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Hari: activate to sort column ascending" style="width: 163px;">Hari</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Masuk: activate to sort column ascending" style="width: 163px;">Masuk</th>
                                                    <th class="sorting" tabindex="0" aria-controls="example22" rowspan="1" colspan="1" aria-label="Keluar: activate to sort column ascending" style="width: 163px;">Selesai</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
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
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                foreach ($semua as $obj) {
                                                    ?>
                                                    <tr class="footable-even" style="">
                                                        <td><?php echo $obj->matkul ?></td>
                                                        <td><?php echo $obj->syarat ?></td>
                                                        <td><?php echo $obj->sks ?></td>
                                                        <td><?php echo $obj->semester ?></td>
                                                        <td><?php echo $obj->kurikulum ?></td>
                                                        <td><?php echo $obj->dosen ?></td>
                                                        <td><?php echo $obj->ruang ?></td>
                                                        <td><?php echo $obj->hari ?></td>
                                                        <td><?php echo $obj->masuk ?></td>
                                                        <td><?php echo $obj->keluar ?></td>
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
            </div>
        </div>
    </div>
    <!-- column -->
<?php
}
public function kurikulum($k11, $k15)
{
    ?>

    <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#k2011" role="tab">Kurikulum 2011</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#k2015" role="tab">Kurikulum 2015</a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!--second tab-->

            <div class="tab-pane active" id="k2011" role="tabpanel">
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <div id="example1_wrapper" class="dataTables_wrapper">
                            <table id="example1" class="display nowrap table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example1_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Kode Mata Kuliah</th>
                                        <th>Mata Kuliah</th>
                                        <th></th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Kode Mata Kuliah</th>
                                        <th>Mata Kuliah</th>
                                        <th></th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    foreach ($k11 as $obj) {
                                        ?>
                                        <tr>
                                            <td><?php echo $obj->kodemk ?></td>
                                            <td><?php echo $obj->matkul ?></td>
                                            <td></td>
                                            <td><?php echo $obj->semester ?></td>
                                            <td><?php echo $obj->sks ?></td>
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
            <div class="tab-pane" id="k2015" role="tabpanel">
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <div id="example_wrapper" class="dataTables_wrapper">
                            <table id="example" class="display nowrap table table-hover table-striped table-bordered dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Kode Mata Kuliah</th>
                                        <th>Mata Kuliah</th>
                                        <th></th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Kode Mata Kuliah</th>
                                        <th>Mata Kuliah</th>
                                        <th></th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    foreach ($k15 as $obj) {
                                        ?>
                                        <tr>
                                            <td><?php echo $obj->kodemk ?></td>
                                            <td><?php echo $obj->matkul ?></td>
                                            <td></td>
                                            <td><?php echo $obj->semester ?></td>
                                            <td><?php echo $obj->sks ?></td>
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
<?php
}
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
public function riwayat($result)
{
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <div>
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        Nama Mahasiswa : <?=$_SESSION['akademik']->nama?>
                                    </td>
                                    <td>
                                        NIM :  <?=$_SESSION['akademik']->username?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Penasihat Akademis :
                                    </td>
                                    <td>
                                        Tahun :
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Fakultas : 
                                    </td>
                                    <td>
                                        Prodi :
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Semester :
                                    </td>
                                </tr>
                            </table>
                            <table class="display nowrap table table-bordered " cellspacing="0" width="100%" role="grid" aria-describedby="example23_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th>No</th>
                                        <th rowspan="1" colspan="1">Mata Kuliah</th>
                                        <th rowspan="1" colspan="1">SKS</th>
                                        <th rowspan="1" colspan="1">Kurikulum</th>
                                        <th rowspan="1" colspan="1">Nama Dosen</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $i = 1;
                                    $j = 0;
                                    foreach ($result as $obj) {
                                        ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?php echo $obj->matkul ?></td>
                                            <td><?php echo $obj->sks;
                                                $j += $obj->sks; ?></td>
                                            <td><?php echo $obj->kurikulum ?></td>
                                            <td><?php echo $obj->dosen ?></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <strong>SKS TOTAL:</strong>
                                        </td>
                                        <td colspan="1">
                                            <strong>
                                                <?= $j ?>
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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
