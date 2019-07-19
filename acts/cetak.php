<?php
require_once('../libraries/tcpdf/tcpdf.php');

// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new TCPDF ('L', 'mm', array('250','130'), true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(5, 5, 5);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 1);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', '', 12);


function fetch_data() {
    $output = '';	
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "akademikuas";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection

    $sql = "SELECT ks.courses_code as kodemk, ks.courses_name as matkul, ks.courses_credit as sks, ks.educators_name as dosen, tc.name as kelas, ks.courses_year as kurikulum
        FROM teachingcredits tc, educators d, courses c, classstudents cs, krs ks
        Where tc.educators_id = d.id AND tc.courses_id = c.id and tc.educators_id = d.id and cs.teachingcredits_id = tc.id and ks.status = 1
       group by matkul order by matkul";
    $result = mysqli_query($conn, $sql);

        // output data of each row
        while($row = mysqli_fetch_array($result)) {   

        $output .= '<tr>
                    <td align="center">'.$row['kodemk'].'</td>
                    <td align="center">'.$row['matkul'].'</td>
                    <td align="center">'.$row['sks'].'</td>
                    <td align="center">'.$row['dosen'].'</td>
                    <td align="center">'.$row['kelas'].'</td>
                    <td align="center">'.$row['kurikulum'].'</td>
                    </tr>';
        
        }
        return $output;
}


$content  = '';  
$content .= '<br><br>
<img src="../images/logo.png" height="42" width="42"><br>
<table border="1">  
  <tr>
  <th align="center"><b>Kode MK</b></th>
  <th align="center"><b>Mata Kuliah</b></th>
  <th align="center"><b>SKS</b></th>
  <th align="center"><b>Nama Dosen</b></th>
  <th align="center"><b>Kelas</b></th>
  <th align="center"><b>Kurikulum</b></th>
  </tr>';     
   
$content .= fetch_data(); 
$content .= '
</table>';


// output the HTML content
$pdf->writeHTML($content, true, true, true, true, '');

// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('KRS.pdf', 'I');

?>