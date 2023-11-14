<?php
/* Attendance view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>


<?php

$company_id    = $this->input->post('company_id');
$month_year    = $this->input->post('month_year');
$bulan = null;

if(!isset($month_year)){

    $skrg     = date('Y-m');
    $tanggal = null;

    $xin_bulan     = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    if ($xin_bulan) {
      $bulan         = $xin_bulan[0]->month_payroll;
  
      $company_id    = 1;
        
      
      $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($bulan);
      $tanggal       = $this->Timesheet_model->read_tanggal_information($bulan);
    }

    if(!is_null($tanggal)){
      $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
      $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

      $start_date    = new DateTime($tanggal[0]->start_date);
      $end_date      = new DateTime($tanggal[0]->end_date);
      $interval_date = $end_date->diff($start_date);

    } else {
      $start_att     = '';  
      $end_att       = '';  

      $start_date    = '';
      $end_date      = '';
      $interval_date = '';
    }   
    
  } else {

    $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($month_year);

     $bulan         = $month_year;

    $tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
    if(!is_null($tanggal)){
      $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
      $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

      $start_date    = new DateTime($tanggal[0]->start_date);
      $end_date      = new DateTime($tanggal[0]->end_date);
      $interval_date = $end_date->diff($start_date);


    } else {
      $start_att = '';  
      $end_att = '';  

      $start_date    = '';
      $end_date      = '';
      $interval_date = '';
    }   

    

  }

?>

<?php $attributes = array('name' => 'attendance_rekap_harian_proses', 'id' => 'attendance_rekap_harian_proses', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
<?php $hidden     = array('user_id' => $session['user_id']);?>
<?php echo form_open('admin/timesheet/attendance_rekap_harian_proses', $attributes, $hidden);?>
<?php
    $data = array(
      'type'     => 'hidden',
      'name'     => 'date_format',
      'id'       => 'date_format',
      'value'    => $this->Core_model->set_date_format(date('Y-m-d')),
      'class'    => 'form-control',
    );
    echo form_input($data);
?>

<div class="row <?php echo $get_animate;?>">
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Proses Rekap Absensi Harian </h3>
        </div>     
        <div class="box-body">
            <div class="row">
              
              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                  <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                    <?php foreach($get_all_companies as $company) {?>
                    <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <label for="name">Pola Kerja</label>
                      <select name="pola_kerja" id="pola_kerja" class="form-control input-sm" data-plugin="select_hrm" data-placeholder="Pola Kerja">
                          <?php foreach($all_office_pola as $pola_kerja) {?>
                          <option value="<?php echo $pola_kerja->pola_name?>"><?php echo strtoupper($pola_kerja->pola_keterangan);?></option>
                          <?php } ?>
                      </select>
                  </div>
              </div>            

              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name">Bulan Kehadiran</label>
                  <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                    
                    <?php foreach($all_bulan_gaji as $bulan_gaji) {?>                       
                      <option value="<?php echo $bulan_gaji->month_payroll;?>" <?php if($bulan_gaji->month_payroll==$bulan): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($bulan_gaji->desc); ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <!-- <div class="col-md-2">
               <div class="form-group" id="periode_ajax">
                  <label for="first_name">Periode Kehadiran Harian</label>
                  <select disabled="disabled"  class="form-control input-sm" name="periode_id" id="periode_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                    
                       <option value="0"> Pilih Periode Kehadiran Harian </option>
                  </select>
                </div>
              </div> -->

              <div class="col-md-3">
                <div class="form-group"> &nbsp;
                  <label for="first_name">&nbsp;</label><br />
                  <button type="button" class="btn  btn-success"  onclick="searchDataAttendance()" title="Tampilkan"> 
                    <i class="fa fa-filter"></i> 
                    Tampilkan
                  </button>  
                </div>
              </div>

            </div>
        </div>
    </div>
  </div>
</div>

<div class="box <?php echo $get_animate;?>">
  
   <div class="box-header ">
              <span class="info_rekap_harian"></span>
              <div class="box-tools pull-right" id ="myBtn" style="display:none;">
                  <?php if(in_array('0952',$role_resources_ids)) { ?>
                    <button type="submit" class="btn  btn-warning save"  title="Rekap Absensi Harian"  > 
                      <i class="fa fa-gears"></i> Rekap Absensi Harian
                    </button>
                  <?php } ?>
              </div>
            </div>   
            <div class="box-body" id ="myDIV" style="display:none;">
              <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table_recap" style="width:100%;">
                  <thead>
                    <tr>
                      <th width="20px" rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor'); ?></th>
                      <th width="300px" rowspan="2" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_employee'); ?></center>
                      </th>
                      <th colspan="31"> Kehadiran Bulan <span id="p_month"></span></th>
                      <th colspan="8" style="vertical-align: middle !important;">Rekap</th>
                      <th colspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_absent_late'); ?></th>
                      <th width="20px" rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_timesheet_workdays_total'); ?></th>
                    </tr>
                    <tr>

                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 1 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 2 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 3 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 4 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 5 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 6 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 7 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 8 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 9 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 10 </center>
                      </th>

                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 11 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 12 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 13 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 14 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 15 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 16 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 17 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 18 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 19 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 20 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 21 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 22 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 23 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 24 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 25 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 26 </center>
                      </th>

                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 27 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 28 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 29 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 30 </center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center> 31 </center>
                      </th>

                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_holiday_judul'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center>LK</center>
                      </th>

                      <th width="20px" style="vertical-align: middle !important;">
                        <center>H</center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_on_sick_simbol'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_on_izin_simbol'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_on_leave_simbol'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_absent_simbol'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_travels_simbol'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_absent_menit'); ?></center>
                      </th>
                      <th width="20px" style="vertical-align: middle !important;">
                        <center><?php echo $this->lang->line('xin_absent_jam'); ?></center>
                      </th>
                    </tr>
                  </thead>               
                </table>
              </div>
            </div>
  
</div>

 <?php echo form_close(); ?>

<style type="text/css">
    .box-tools {
        margin-right: -5px !important;
    }
    .col-md-8 {
      padding-left:0px !important;
      padding-right: 0px !important;
    }
    .dataTables_length {
      float:left;
    }
    .dt-buttons {
        position: relative;
        float: right;
        margin-left: 10px;
    }
    .hide-calendar .ui-datepicker-calendar { display:none !important; }
    .hide-calendar .ui-priority-secondary { display:none !important; }
</style>


<script type="text/javascript"> 


  function tampilkan_tabel() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
      x.style.display = "block";
    } 
  } 

  function sembunyikan_tabel() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "block") {
      x.style.display = "none";
    } 
  } 

  function tampilkan_tombol() {
    var x = document.getElementById("myBtn");
    if (x.style.display === "none") {
      x.style.display = "block";
    } 
  } 

  function searchDataAttendance() 
  {
      $(".info_rekap_harian").html('Loading ...');

      var month_year  = jQuery('#month_year').val();
      var company_id  = jQuery('#company_id').val();
      // var jenis_gaji  = jQuery('#jenis_gaji').val();
      var pola_kerja  = jQuery('#pola_kerja').val();
      var period = jQuery('#periode_id').val();
  
      if (company_id == '' )
      {
          alert("Nama Perusahaan Belum Diisi !");
          $("#company").focus();

      } 
      else if (month_year == '' )
      {
          alert("Bulan Kehadiran Belum Diisi !");
           $("#month_year").focus();  
          
      } 
      // else if (jenis_gaji == '' )
      // {
      //     alert("Jenis Karyawan Belum Diisi !");
      //      $("#jenis_gaji").focus();  
          
      // }
      else if (pola_kerja == '' )
      {
          alert("Pola Kerja Belum Diisi !");
           $("#pola_kerja").focus();  
          
      }
      // else if (period == '')
      // {
      //   alert("Periode Belum Diisi !");
      //   $("#periode_id").focus();  
      // }
      else 
      {
        $period_text = $('#periode_id option:selected').text();

        $(".info_rekap_harian").html(`<h3 class="box-title text-uppercase text-bold"> Rekap Absensi Harian, Periode : ${$period_text}</h3><h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two kuning">Rekap Absensi Harian </span>" Jika Data Ditabel ini tidak ada.</h5>`);

        toastr.success('Tampilkan Rekap Absensi Harian');
        
         // $this->Timesheet_model->get_xin_employees_tanggal(jQuery('#month_year').val());

          var xin_table3   = $('#xin_table_recap').dataTable({
                  
            "bDestroy"        : true,
            "bSort"           : false,
            "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
            autoWidth         : true,  
            "fixedColumns"    : true,
            "fixedColumns"    : {
              leftColumns   : 2
            },    
            "ajax": {
              url : site_url+"timesheet/attendance_rekap_harian_list/?company_id="+company_id+"&month_year="+month_year+"&pola_kerja="+pola_kerja,
              type : 'GET'
            },
           
              "columns": [{
                  "name": "kolom_1",
                  "className": "text-center"
                },
                {
                  "name": "kolom_2",
                  "className": "text-left"
                },
                {
                  "name": "kolom_1",
                  "className": "text-center"
                },
                {
                  "name": "kolom_2",
                  "className": "text-center"
                },
                {
                  "name": "kolom_3",
                  "className": "text-center"
                },
                {
                  "name": "kolom_4",
                  "className": "text-center"
                },

                {
                  "name": "kolom_5",
                  "className": "text-center"
                },
                {
                  "name": "kolom_6",
                  "className": "text-center"
                },
                {
                  "name": "kolom_7",
                  "className": "text-center"
                },
                {
                  "name": "kolom_8",
                  "className": "text-center"
                },
                {
                  "name": "kolom_9",
                  "className": "text-center"
                },
                {
                  "name": "kolom_10",
                  "className": "text-center"
                },
                {
                  "name": "kolom_11",
                  "className": "text-center"
                },

                {
                  "name": "kolom_12",
                  "className": "text-center"
                },
                {
                  "name": "kolom_13",
                  "className": "text-center"
                },
                {
                  "name": "kolom_14",
                  "className": "text-center"
                },
                {
                  "name": "kolom_15",
                  "className": "text-center"
                },
                {
                  "name": "kolom_16",
                  "className": "text-center"
                },
                {
                  "name": "kolom_17",
                  "className": "text-center"
                },
                {
                  "name": "kolom_18",
                  "className": "text-center"
                },

                {
                  "name": "kolom_19",
                  "className": "text-center"
                },
                {
                  "name": "kolom_20",
                  "className": "text-center"
                },
                {
                  "name": "kolom_21",
                  "className": "text-center"
                },
                {
                  "name": "kolom_22",
                  "className": "text-center"
                },
                {
                  "name": "kolom_23",
                  "className": "text-center"
                },
                {
                  "name": "kolom_24",
                  "className": "text-center"
                },
                {
                  "name": "kolom_25",
                  "className": "text-center"
                },
                {
                  "name": "kolom_26",
                  "className": "text-center"
                },
                {
                  "name": "kolom_27",
                  "className": "text-center"
                },
                {
                  "name": "kolom_28",
                  "className": "text-center"
                },
                {
                  "name": "kolom_29",
                  "className": "text-center"
                },
                {
                  "name": "kolom_30",
                  "className": "text-center"
                },
                {
                  "name": "kolom_31",
                  "className": "text-center"
                },

                {
                  "name": "kolom_libur",
                  "className": "text-center"
                },
                {
                  "name": "kolom_libur",
                  "className": "text-center"
                },
                {
                  "name": "kolom_aktif",
                  "className": "text-center"
                },
                {
                  "name": "kolom_sakit",
                  "className": "text-center"
                },
                {
                  "name": "kolom_izin",
                  "className": "text-center"
                },
                {
                  "name": "kolom_cuti",
                  "className": "text-center"
                },
                {
                  "name": "kolom_alpa",
                  "className": "text-center"
                },
                {
                  "name": "kolom_dinas",
                  "className": "text-center"
                },

                {
                  "name": "kolom_menit",
                  "className": "text-center"
                },
                {
                  "name": "kolom_jam",
                  "className": "text-center"
                },
                {
                  "name": "kolom_total",
                  "className": "text-center"
                },

              ],
             "language": {
                    "aria": {
                        "sortAscending" : ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Silahkan Tunggu...",
                "processing": "Sedang memproses...",
                 "search"      : "Pencarian : ", "searchPlaceholder": "Masukan Kata Pencarian ...",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "thousands": "'",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
              },
             
             dom: 'lBfrtip',
              buttons: [
                    'print', {
                      extend: 'pdf',
                      orientation: 'landscape'
                    },
                    'excel'
                  ],
            
            "rowCallback": function(row, data, index) { 

              if (data[10] == '' || data[11] == '' || data[12] == '' ){

                $(row).find('td:eq(12)').css('background-color', '#ddd');
                $(row).find('td:eq(12)').css('color', 'black');

              }    

               
                $(row).find('td:eq(33)').css('background-color', '#ddc3c3');
                $(row).find('td:eq(33)').css('color', 'black');

                $(row).find('td:eq(34)').css('background-color', '#ecd5c2');
                $(row).find('td:eq(34)').css('color', 'black');

                $(row).find('td:eq(35)').css('background-color', '#c6edd1');
                $(row).find('td:eq(35)').css('color', 'black');

                $(row).find('td:eq(36)').css('background-color', '#c6dbed');
                $(row).find('td:eq(36)').css('color', 'black');  

                $(row).find('td:eq(37)').css('background-color', '#e7debd');
                $(row).find('td:eq(37)').css('color', 'black');

                $(row).find('td:eq(38)').css('background-color', '#bde7e7');
                $(row).find('td:eq(38)').css('color', 'black');

                $(row).find('td:eq(39)').css('background-color', '#edeac6');
                $(row).find('td:eq(39)').css('color', 'black'); 

                $(row).find('td:eq(40)').css('background-color', '#c6e9ed');
                $(row).find('td:eq(40)').css('color', 'black');

                $(row).find('td:eq(41)').css('background-color', '#edc6eb');
                $(row).find('td:eq(41)').css('color', 'black');

                $(row).find('td:eq(42)').css('background-color', '#edc6eb');
                $(row).find('td:eq(42)').css('color', 'black');

                $(row).find('td:eq(43)').css('background-color', '#cfc6ed');
                $(row).find('td:eq(43)').css('color', 'black');
                                
              
            }
            
            
          });
        
         
          tampilkan_tabel();
          tampilkan_tombol();
         
      }
  }


</script>