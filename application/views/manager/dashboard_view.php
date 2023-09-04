<!-- Content Header (Page header) -->
<style>
    
    
    body{
      
    }
  </style>
<section class="content-header" style="font-family:Helvetica !important;">
	<h1>
		<?php if(!empty($site_name)){ echo $site_name; } ?>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>
<?php
// Create database connection using config file
include_once("config.php");
 
// Fetch all users data from database
$result = mysqli_query($mysqli, "SELECT Count(topik_id) as topik from cbt_topik");
$result2 = mysqli_query($mysqli, "SELECT Count(grup_id) as Kelas FROM cbt_user_grup");
$result3 = mysqli_query($mysqli, "SELECT Count(tes_id) as Tes FROM cbt_tes");
$result4 = mysqli_query($mysqli, "SELECT Count(user_id) as Siswa FROM cbt_user");
$result5 = mysqli_query($mysqli, "SELECT token_isi FROM cbt_tes_token order by token_ts desc limit 1");
$result6 = mysqli_query($mysqli, "SELECT count(tes_id) as active FROM `cbt_tes` WHERE `tes_end_time`>= CURRENT_TIMESTAMP()+1 and `tes_begin_time`>= CURRENT_TIMESTAMP()");

# All users online

$user_online = mysqli_query($mysqli, "SELECT user_firstname as users from cbt_user where is_logged_in = 1");

?>
<!-- Main content -->
<section class="content">
   <!-- /.box-header -->
        
   
    
	<div class="callout callout-info" >
    	<h4>Informasi</h4>
        <p>Admin SMP  N 2 Tuntang mengelola dan melaksanakan ujian online.</p>
    </div>
    <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php  
                  while($user_data = mysqli_fetch_array($result)) {         
                      
                      echo $user_data['topik'];
                       
                  }
                  ?></h3>

                <p>Bank Soal</p>
              </div>
              <div class="icon">
                <i class="ion-android-document"></i>
              </div>
              <a href="http://localhost/smp/index.php/manager/modul_topik" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-blue">
              <div class="inner">
                <h3><?php  
                  while($user_data = mysqli_fetch_array($result2)) {         
                      
                      echo $user_data['Kelas'];
                       
                  }
                  ?></h3>

                <p>Kelas</p>
              </div>
              <div class="icon">
                <i class="ion-android-people"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php  
                  while($user_data = mysqli_fetch_array($result3)) {         
                      
                      echo $user_data['Tes'];
                       
                  }
                  ?></h3>

                <p>Total Ujian</p>
              </div>
              <div class="icon">
                <i class="ion-ios-albums"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php  
                  while($user_data = mysqli_fetch_array($result4)) {         
                      
                      echo $user_data['Siswa'];
                       
                  }
                  ?></h3>

                <p>Siswa</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php  
                  while($user_data = mysqli_fetch_array($result5)) {         
                      
                      echo $user_data['token_isi'];
                       
                  }
                  ?></h3>

                <p>Token</p>
              </div>
              <div class="icon">
                <i class="ion-android-checkmark-circle"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
      
          <!-- ./col -->
        
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <script>
                                    var n = localStorage.getItem('on_load_counter');

                        if (n === null) {
                            n = 0;
                        }

                        n++;

                        localStorage.setItem("on_load_counter", n);

                        document.getElementById('CounterVisitor').innerHTML = n;
                </script>
                  <h3><?php echo mysqli_num_rows($user_online)?></h3>
                
                <p>User Online</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php  
                  while($user_data = mysqli_fetch_array($result6)) {         
                      
                      echo $user_data['active'];
                       
                  }
                  ?></h3>

                <p>Ujian Active</p>
              </div>
              <div class="icon">
                <i class="ion-android-checkmark-circle"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="glyphicon glyphicon-chevron-right"></i></a>
            </div>
          </div>
          </div>
          
          <!-- ./col -->
        </div>
        
        <div class="box">
          <div class="box-body">
              <table id="table-users" class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>No.</th>
                          <th>Nama</th>
                      </tr>
                  </thead>
                  <?php
                    $nomor = 1; // Inisialisasi nomor urut
                    while ($item = mysqli_fetch_assoc($user_online)) {
                        echo '<tr>';
                        echo '<td>' . $nomor . '</td>'; // Menampilkan nomor urut
                        echo '<td>' . $item['users'] . '</td>'; // Menampilkan data nama
                        echo '</tr>';
                        
                        $nomor++; // Meningkatkan nomor urut setelah setiap data
                      }
                    ?>
              </table>                        
          </div>
        </div>
          
    <div class="box">
        <div class="box-header with-border">
            <div class="box-title">Konfigurasi System</div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <b><u>Waktu Server</u></b>
                    <br />
                    <b><?php if(!empty($waktu_server)){ echo $waktu_server; } ?></b>
                    <br />
                    Pastikan waktu server sesuai dengan waktu saat ini. Jika ada perbedaan, cek timezone server dan timezone di konfigurasi PHP.
                </div>
                <div class="col-md-4">
                    <b><u>Informasi Konfigurasi Upload PHP</u></b>
                    <br />
                    POST_MAX_SIZE = <?php if(!empty($post_max_size)){ echo $post_max_size; } ?>
                    <br />
                    UPLOAD_MAX_FILESIZE = <?php if(!empty($upload_max_filesize)){ echo $upload_max_filesize; } ?>
                </div>
                <div class="col-md-4">
                    <b><u>Folder Upload</u></b>
                    <br />
                    Folder "uploads" = <?php if(!empty($dir_public_uploads)){ echo $dir_public_uploads; } ?>
                    <br />
                    Folder "public/uploads" = <?php if(!empty($dir_uploads)){ echo $dir_uploads; } ?>
                    <br />
                    Pastikan kedua folder diatas memiliki nilai Writeable.
                </div>
            </div>
            <p>
            </p>
        </div>
    </div>
  
</section><!-- /.content -->
<style>
 .callout.callout-info{
    background-color:#DB005B  !important;
 }

</style>
<script>
  $( document ).ready(function() {
      $('#table-users').DataTable({
              "paging": true,
              "iDisplayLength":10,
              "searching": true,
              "aoColumns": [
                    {"bSearchable": false, "bSortable": false, "sWidth":"20px"},
                    {"bSearchable": false, "bSortable": false, "sWidth":"100px"}],
              "autoWidth": false,
        });
      });
</script>