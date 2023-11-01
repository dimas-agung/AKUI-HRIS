<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

<?php echo form_open_multipart('admin/reports/export_employees_timelate_list');?>
<div class="row <?php echo $get_animate;?>">
  <div class="col-md-12">
      <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Laporan Keterlambatan Karyawan </h3>
          </div>
          <div class="box-body">
            <div class="row">
              
              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                  <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="Perusahaan" onchange="get_department()">
                    <option value=""></option>
                    <?php foreach($get_all_companies as $company) {?>
                    <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <span id="span_department_id">
                  <div class="form-group">
                      <label for="name">Departemen</label>
                      <select name="department_id" id="department_id" class="form-control input-sm" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>" disabled>
                          
                      </select>
                  </div>
                </span>
              </div>            

              <div class="col-md-2">
                  <div class="form-group">
                    <label for="first_name">Tanggal</label>
                    <input class="form-control attendance_date"  placeholder="<?php echo $this->lang->line('xin_select_date');?>"  id="attendance_date" name="attendance_date" type="date" value="<?php echo date('Y-m-d');?>">
                  </div>
              </div>
              
                
              <div class="col-md-4">
                <div class="form-group"> &nbsp;
                  <label for="first_name">&nbsp;</label><br />
                  <button type="button" class="btn btn-primary" onclick="searchDataAttendance()">
                            <i class="fa fa-filter"></i> Tampilkan
                          </button>
                </div>
              </div>

            </div>
          </div>
    
       </div>
  </div>
</div>

<div class="box <?php echo $get_animate;?>">
  
  <div class="box-body">
    <div class="row">
        <div class="col-md-12"> 
        <h3 class="box-title"><span class="info_report_gaji_borongan"></span> </h3>  
          <div class="box" style="margin-bottom: 0px;">   
            <div class="box-header with-border">
              <h3 class="box-title text-uppercase text-bold"> Data Karyawan Terlambat  </h3>
              <span class="info_tarik_reguler"></span>
              
              <div class="box-tools pull-right" id ="myBtn" style="display:">                   
                <button class="btn btn-xs btn-info" id="btn-export-xls" data-toggle="tooltip" type="submit" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
                    <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
                </button>              
              </div>    
            </div>  
           
             <div class="box-body" id ="myDIV" style="display:none;">
              <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table_recap" style="width:160%;">
                  <thead>                           
                      <tr>
                        <th style="width:50px; vertical-align: middle !important;"><center>No.</center></th>          
                        <th style="width:400px;vertical-align: middle !important;"><center>Nama Karyawan</center></th>
                        <th style="width:120px;vertical-align: middle !important;"><center>Mulai Kerja</center></th>
                        <th style="width:450px;vertical-align: middle !important;"><center>Posisi</center></th>
                        <th style="width:320px;vertical-align: middle !important;"><center>Workstation</center></th>       
                        <th style="width:180px;vertical-align: middle !important;"><center>Jam Kerja</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center>Tanggal</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center>Status</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_clock_in');?></center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_clock_out');?></center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_late');?><br>(Menit)</center></th> 
                        <!-- <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_early_leaving');?><br>(Menit)</center></th>  
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_overtime');?><br>(Menit)</center></th>            
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_total_work');?><br>(Menit)</center></th> -->
                        <th style="width:500px;vertical-align: middle !important;"><center>Keterangan</center></th>                          
                      </tr>
                    </thead>                  
                  </table>
              </div>
            </div>
          </div>


          
        </div>
        <!-- /.col --> 
    </div>

    
  </div>
</div>
<?php echo form_close(); ?> 

<script type="text/javascript"> 
 function tampilkan_tabel() 
    {
      var x = document.getElementById("myDIV");
      if (x.style.display === "none") {
        x.style.display = "block";
      } 
    } 

    function tampilkan_button() 
    {
      // var x = document.getElementById("myBtn");
      // if (x.style.display === "none") {
      //   x.style.display = "block";
      // } 
    } 

function searchDataAttendance() 
    {
        var company_id  = document.getElementById("company_id").value; 
        var department_id  = document.getElementById("department_id").value;
        var attendance_date  = document.getElementById("attendance_date").value;
        
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company_id").focus();
        } 
        else if (department_id == '' )
        {
            alert("Periode Kerja Borongan Belum Diisi !");
             $("#department_id").focus();              
        } 
        
        else 
        {        

            tampilkan_tabel();

            tampilkan_button();

           $(".info_report_gaji_borongan").html('Loading ..');

            // $('#p_month').html(month_year);  
            var xin_table_borongan = $('#xin_table_recap').DataTable({               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/employees_timelate_list/',
                    data: {                 
                        company_id  : company_id, 
                        department_id   : department_id,
                        attendance_date   : attendance_date,
                                              
                    }
                },                                      
                 "columns": [
                    {"name": "kolom_1",  "className": "text-center","width": "2%"},
                    {"name": "kolom_2",  "className": "text-center"},
                    {"name": "kolom_3",  "className": "text-center"},
                    {"name": "kolom_4",  "className": "text-left"},
                    {"name": "kolom_5",  "className": "text-left"},
                    {"name": "kolom_6",  "className": "text-left"},
                    {"name": "kolom_7",  "className": "text-center"},
                    {"name": "kolom_8", "className": "text-center"},
                    {"name": "kolom_9", "className": "text-center"},
                    {"name": "kolom_10", "className": "text-center"},             
                    // {"name": "kolom_11", "className": "text-center"},                
                    // {"name": "kolom_12", "className": "text-right"},
                    // {"name": "kolom_13", "className": "text-right"},
                    {"name": "kolom_14", "className": "text-right"},
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
              
                "bStateSave": true,                     
                
                // set the initial value
                "pageLength": 10,
                "columnDefs": [{  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
                "order": [
                    [1, "asc"]
                ],
                "iDisplayLength": 10,
               
            });
            $(".info_report_gaji_borongan").html('');
        }
    }
  function tampilkan_tabel() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
      x.style.display = "block";
    } 
  } 

  function tampilkan_tombol() {
    var x = document.getElementById("myBtn");
    if (x.style.display === "none") {
      x.style.display = "block";
    } 
  } 
  function exportDataXls()  
    {
         // var month_year  = jQuery('#month_year').val();
         var company_id  = document.getElementById("company_id").value; 
        var department_id  = document.getElementById("department_id").value;
        var attendance_date  = document.getElementById("attendance_date").value;
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
        else if (department_id == '' )
        {
            alert("Departemen Belum Diisi !");
             $("#department_id").focus();  
             
            
        } else {

          $.ajax({
            type : "GET",
            url  : '<?php echo base_url();?>admin/reports/export_employees_timelate_list',
            data : { 
              
              company_id  : company_id,
              department_id  : department_id,
              attendance_date  : attendance_date,
            },
            // dataType : "json",
            success:function(data){ 
                    if(data == 1){
                      alert('Data Berhasil di export!')
                    }else{
                      alert('Data gagal di export!')

                    }
                    // console.log(data);                     
            }     
          });

        
        }
    }  

    function get_department() {
      var company_id  = jQuery('#company_id').val();
      // alert(company_id)
      $.ajax({
        type : "GET",
        url  : '<?php echo base_url();?>admin/reports/get_department/'+company_id,
        data : { 
          
          // company_id  : company_id,
        },
        // dataType : "json",
        success:function(data){   
                // console.log(data);                     
                  $("#span_department_id").html(data);
                    
                }
        });
    }
  </script>