<!-- FLOT CHARTS -->
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/flot/jquery.flot.min.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/flot/jquery.flot.resize.min.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/flot/jquery.flot.pie.min.js"></script>

<!-- ChartJS 1.0.1 -->
<script src="<?=$_ASSETS_PATH?>plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="<?=$_ASSETS_PATH?>plugins/fastclick/fastclick.min.js"></script>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Beranda
    <small>Informasi Umum</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Beranda</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">  

  <div class="row">
    <div class="col-md-12">

      <!-- DONUT CHART -->
      <div class="box box-danger">
        
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                  <h4>Panduan Operasional Sistem</h4>
                  <ol>
                    <li>Sistem ini terdiri dari 6 menu utama seperti yang dapat dilihat pada sisi kiri layar.</li>
                    <li>Menu pertama adalah <b>Home</b> untuk mengakses halaman di mana anda sedang berada saat ini.</li>                    
                    <li>Untuk melakukan Penilaian Properti silahkan akses menu <b>Penilaian Properti</b>. Di sana anda akan melihat beberapa Sub Menu.
                      <ol type="a">
                        <li>Penginputan awal dilakukan pada sub menu <b>(1) Formulir Dasar</b>. Di sana anda diminta mengisi beberapa isian. 
                        Setelah disimpan, akan tampil halaman Hasil Penginputan. Pada halaman tersebut anda akan menemukan <b>No. Penilaian</b> 
                        yang bisa digunakan untuk melakukan pencarian untuk keperluan penginputan selanjutnya.</li>

                        <li>
                          Lanjutkan penginputan sesuai dengan urutan sub menu. Di setiap halaman penginputan selain submenu <b>(1) Formulir Dasar</b> anda akan diminta 
                          memasukkan Kunci Pencarian, gunakan salah satu dari No. Penilaian (yang didapatkan setelah penginputan Formulir Dasar), No. Penugasan, atau
                          Nama Calon Debitur untuk melakukan pencarian. Klik <b>Tampil</b> untuk memunculkan hasil, yaitu berupa Daftar Inputan Formulir Dasar/Data Dasar</li>                      
                        </li>
                        <li>
                          Pada Daftar Data Dasar, di kolom paling kanan setiap baris terdapat sebuah tombol bergambar pensil, klik tombol tersebut kemudian akan muncul 
                          kotak PopUp. Pada kotak tersebut silahkan melakukan manajemen data.
                        </li>
                        <li>
                          Apabila pada Data Dasar terdapat kesalahan, data bisa diperbaiki pada sub menu <b>(2) Manajemen Data Dasar</b>.
                        </li>
                      </ol>
                    </li>
                    
                    <li>
                      Untuk melakukan perhitungan Biaya Konstruksi Bangunan silahkan akses menu <b>Biaya Konstruksi Bangunan</b>. Di sana anda akan melihat Sub Menu.
                      <ol type="a">
                        <li>
                          Penginputan dilakukan pada sub menu Input Biaya Konstruksi. Isikan semua data yang diminta. Setelah data tersimpan, akan tampil halaman Hasil Penginputan.
                          Pada Halaman tersebut anda akan melihat <b>No. BCT</b> yang bisa digunakan untuk melakukan pencarian untuk keperluan manajemen data.
                        </li>
                        <li>
                          Apabila terdapat kesalahan pada Data Perhitungan, data bisa diperbaiki pada sub menu <b>Manajemen Biaya Konstruksi</b>
                        </li>
                      </ol>
                    </li>
                    <li>
                      Untuk melihat laporan silahkan akses menu <b>Laporan & Keluaran</b>.
                    </li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <h4>Daftar Penilaian Properti yang belum diselesaikan</h4>
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th width="4%">No.</th>
                        <th>No. Penilaian</th><th>No./Tgl. Penugasan</th><th>Keperluan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        include_once "helpers/date_helper.php";

                        $sql = "SELECT no_penilaian,no_penugasan,tgl_penugasan,keperluan_penugasan FROM penugasan WHERE status='0'";
                        $result = $db->Execute($sql);
                        if(!$result)
                          die($db->ErrorMsg());

                        $no = 0;
                        while($row = $result->FetchRow())
                        {
                          $no++;
                          echo "<tr>
                          <td align='center'>".$no."</td>
                          <td>".$row['no_penilaian']."</td>
                          <td>".$row['no_penugasan']."<br /><b>".indo_date_format($row['tgl_penugasan'],'longDate')."</b></td>
                          <td>".$row['keperluan_penugasan']."</td>
                          </tr>";
                        }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr><td colspan="4">Total : <?=$no?> record(s)</td></tr>
                    </tfoot>
                  </table>
                </div>
            </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    </div><!-- /.col (LEFT) -->
  </div><!-- /.row -->
  
</section><!-- /.content -->