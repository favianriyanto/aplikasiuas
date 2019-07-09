<?php
global $app;

if (!$app) {
    header("Location:../beranda.php");
}

class ControllerData extends Controller
{
    public function ambil()
    {
        $model = new ModelData();
        $model->ambil();
    }
}

class ModelData extends Model
{

    public function ambil()
    {
        global $app;

        $sql = "SELECT COUNT(a.nim) as jumlahmhs
				FROM advisorstudents a
				WHERE a.username=:username
                AND a.password=MD5(:password)";
                
            $objUser = new stdClass();
            $objUser->katak = $result->jumlahmhs;

            $_SESSION['user'] = $objUser;

            header('Location:' . $app->website . '/Beranda/dashboard');
    }
}

class ViewData extends View
{

}
?>