<?php
global $app;

if (!$app) {
   header("Location:../index.php");
}

class ControllerFaq extends Controller {
    public function __construct() {
        
    }

    public function index() {
        $view = new ViewFaq();
        $view->index();
    }
}

class ModelFaq extends Model {

}

class ViewFaq extends View {
    public function index() {
        global $app;
        
        if (!isset($_SESSION['user'])) {
            header("Location:".$app->website);
        }
        global $app;
    
        $levelakses = $_SESSION['user']->level;

        ?>
        <div for="sekjur" class="card shadow mb-4" <?php if ($levelakses == 'Mahasiswa'){?>style="display:none"<?php } ?>>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">FAQ - Tampilan Sekjur</h6>
                </div>
                <div class="card-body">
                  <p><b>*Bagaimana cara Logout</b></p>
                  <p class="mb-0">Untuk logout, tekan area di ujung kanan atas bertuliskan nama pengguna, lalu klik tombol logout.</p><br>
                  <p><b>*Bagaimana cara mengecilkan Navigasi Bar</b></p>
                  <p class="mb-0">Tekan tombol "<" untuk mengecilkan navigasi bar dan takan ">" untuk membesarkan kembali navigasi bar</p><br>
                  <p><b>*Bagaimana cara menambahkan data</b></p>
                  <p class="mb-0">Tekan tombol "Tambah" bewarna hijau dikiri atas pada tabel, setelah isi tabel tekan "Simpan" untuk menyimpan,<br>dan "Batal" untuk membatalkan penambahan data.</p><br>
                  <p><b>*Bagaimana cara mengedit data</b></p>
                  <p class="mb-0">Tekan tombol ikon pensil bewarna biru disamping data yang dipilih, setelah isi tabel tekan "Simpan" untuk menyimpan,<br>dan "Batal" untuk membatalkan pengeditan data.</p><br>
                  <p><b>*Bagaimana cara menghapus data</b></p>
                  <p class="mb-0">Tekan tombol ikon tong sampah bewarna merah disamping data yang dipilih, akan tampil sebuah pop-up konfirmasi.<br>Tekan "OK" untuk menghapus, dan "Cancel" untuk membatalkan penghapusan data.</p><br>
                </div>
              </div>

        <div for="mahasiswa" class="card shadow mb-4" <?php if ($levelakses == 'Sekjur'){?>style="display:none"<?php } ?>>
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">FAQ - Tampilan Sekjur</h6>
                </div>
                <div class="card-body">
                  <p><b>*Bagaimana cara Logout</b></p>
                  <p class="mb-0">Untuk logout, tekan area di ujung kanan atas bertuliskan nama pengguna, lalu klik tombol logout.</p><br>
                  <p><b>*Bagaimana cara mengecilkan Navigasi Bar</b></p>
                  <p class="mb-0">Tekan tombol "<" untuk mengecilkan navigasi bar dan takan ">" untuk membesarkan kembali navigasi bar</p><br>
                  <p><b>*Bagaimana cara isi KRS</b></p>
                  <p class="mb-0">Pilih centang pilihan tersedia, lalu klik simpan</p><br>
                </div>
              </div>
        <?php
    }

    public function login() {
        global $app;
?>
    

<?php
    }
}
?>