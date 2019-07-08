<?php
global $app;

if (!$app) {
   header("Location:../index.php");
}

class ControllerMatkul2011 extends Controller {
    public function __construct() {
        
    }

    public function index()
    {
        $model = new ModelMatkul2011();
        $view = new ViewMatkul2011();
        $view->index($model->findAll());
    }

    public function entry($id = 0) {
        $model = new ModelMatkul2011();
        $view = new ViewMatkul2011();

        $view->entry($model->find($id));
    }
    public function save($id) {
        $model = new ModelMatkul2011();
        $model->save($id);
    }

    public function delete($id = 0) {
        $model = new ModelMatkul2011();
        $model->delete($id);
    }
}

class ModelMatkul2011 extends Model {
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
            $sql = "DELETE FROM courses
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

        header("Location:".$app->website."/Matkul2011/index");
    }

    public function find($id) {
        global $app;

        $id = $app->id;

        if ($id > 0) {
            $sql = "SELECT *
                    FROM courses
                    WHERE id=:id AND year='2011'";
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

        $sql = "SELECT code, name, case semester when '0' then 'Pilihan' else semester end as semester, prerequisite, year
                FROM courses
                WHERE year='2011'
                ORDER BY semester";
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

class ViewMatkul2011 extends View {

    public function index($result) {
        global $app;
?>
<div style="margin-bottom:20px;">
    <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Matkul/index">Semua</a>
    <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Matkul2011/index">2011</a>
    <a class="btn pmd-ripple-effect btn-success" href="<?php echo $app->website; ?>/Matkul2015/index">2015</a>
    </div>
<div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Kode Mata Kuliah</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Semester</th>
                    <th>Syarat</th>
                    <th>Kurikulum</th>
                </tr>
            </thead>
            <tbody>
<?php
    foreach ($result as $obj) {
?>
                <tr>
                    <td data-title="kodematkul"><?php echo $obj->code; ?></td>
                    <td data-title="namamatkul"><?php echo $obj->name; ?></td>
                    <td data-title="semester"><?php echo $obj->semester; ?></td>
                    <td data-title="syarat"><?php echo $obj->prerequisite; ?></td>
                    <td data-title="kurikulum"><?php echo $obj->year; ?></td>
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