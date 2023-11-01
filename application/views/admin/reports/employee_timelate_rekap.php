<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

<?php echo form_open_multipart('admin/reports/export_employees_timelate_rekap');?>
<div class="row <?php echo $get_animate;?>">
  <div class="col-md-12">
      <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Laporan Rekap Keterlambatan Karyawan </h3>
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
                    <label for="first_name">Tanggal Awal</label>
                    <input class="form-control attendance_date"  placeholder="<?php echo $this->lang->line('xin_select_date');?>"  id="start_date" name="start_date" type="date" value="<?php echo date('Y-m-d');?>">
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                    <label for="first_name">Tanggal Akhir</label>
                    <input class="form-control attendance_date"  placeholder="<?php echo $this->lang->line('xin_select_date');?>"  id="end_date" name="end_date" type="date" value="<?php echo date('Y-m-d');?>">
                  </div>
              </div>
              
                
              <div class="col-md-2">
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
              <h3 class="box-title text-uppercase text-bold"> Data Rekap Karyawan Terlambat  </h3>
              <span class="info_tarik_reguler"></span>
              
              <div class="box-tools pull-right" id ="myBtn" style="display:">                   
                <!-- <button class="btn btn-xs btn-info" id="btn-export-xls" data-toggle="tooltip" type="submit" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
                    <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
                  </button>               -->
                </div>    
              </div>  
              
              <div class="box-body" id ="myDIV" style="display:none;">
              <a href="#" class="btn btn-xs btn-info" onclick="htmlTableToExcel()">

                <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
              </a>

              <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table_recap" style="width:160%;">
                  <thead>                           
                      <!-- <tr id="tr1">
                        <th rowspan="2" style="width:50px; vertical-align: middle !important;"><center>No.</center></th>          
                        <th rowspan="2" style="width:400px;vertical-align: middle !important;"><center>Nama Karyawan</center></th>
                        <th rowspan="2" style="width:120px;vertical-align: middle !important;"><center>Mulai Kerja</center></th>
                        <th rowspan="2" style="width:450px;vertical-align: middle !important;"><center>Posisi</center></th>
                        <th rowspan="2" style="width:450px;vertical-align: middle !important;"><center>Workstation</center></th>
                        
                        
                        
                      </tr>
                     

                        <tr id="tr2">
                        
                        </tr> -->
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
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script type="text/javascript">
function htmlTableToExcel(type ='xlsx'){
 var data = document.getElementById('xin_table_recap');
 var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
 XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
 XLSX.writeFile(excelFile, 'ExportedFile:HTMLTableToExcel.' + type);
} 
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
        var start_date  = document.getElementById("start_date").value;
        var end_date  = document.getElementById("end_date").value;
        var date1 = new Date(start_date);
        var date2 = new Date(end_date);
          
        // To calculate the time difference of two dates
        var Difference_In_Time = date2.getTime() - date1.getTime();
          
        // To calculate the no. of days between two dates
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24)+1;
        console.log(Difference_In_Days);
        // $('#span_tr1').html('');
        // $('#span_tr1').html('<tr id="tr2"></tr>');
        $('#xin_table_recap thead').html('');
 
        $('#xin_table_recap').find('thead #tr2').empty();
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
          //  $('#xin_table_recap').find('thead #tr1').append(`
          //       <th colspan="${Difference_In_Days}" style="width:500px;vertical-align: middle !important;"><center>Total Terlambat (Menit)</center></th>
          //       <th rowspan="2" style="width:100px;vertical-align: middle !important;"><center>Total Menit Keterlambatan</center></th> 
          //       <th rowspan="2" style="width:100px;vertical-align: middle !important;"><center>Jumlah Keterlambatan</center></th> 
          //  `);
           $('#xin_table_recap thead').append(`
                <tr>
                  <th rowspan="2" style="width:50px; vertical-align: middle !important;"><center>No.</center></th>          
                  <th rowspan="2" style="width:400px;vertical-align: middle !important;"><center>Nama Karyawan</center></th>
                  <th rowspan="2" style="width:120px;vertical-align: middle !important;"><center>Mulai Kerja</center></th>
                  <th rowspan="2" style="width:450px;vertical-align: middle !important;"><center>Posisi</center></th>
                  <th rowspan="2" style="width:450px;vertical-align: middle !important;"><center>Workstation</center></th>
                  <th colspan="${Difference_In_Days}" style="width:500px;vertical-align: middle !important;"><center>Total Terlambat (Menit)</center></th>
                  <th rowspan="2" style="width:100px;vertical-align: middle !important;"><center>Total Menit Keterlambatan</center></th> 
                  <th rowspan="2" style="width:100px;vertical-align: middle !important;"><center>Jumlah Keterlambatan</center></th> 
                </tr>
           `);
          //  return;
          //  $('#xin_table_recap').find('thead #tr1').append(``);
          let dataColums = [];
            dataColums = [
             {"name": "kolom_1",  "className": "text-center","width": "2%"},
                    {"name": "kolom_2",  "className": "text-center"},
                    {"name": "kolom_3",  "className": "text-center"},
                    {"name": "kolom_4",  "className": "text-left"},
                    {"name": "kolom_5",  "className": "text-left"},
           ];
           day_start = date1;
           let dataRow  = '';
           dataRow += '<tr>';
          //  $('#xin_table_recap').find('thead #tr2 th').remove();
           for (let index = 0; index < Difference_In_Days; index++) {
             let obj = {"name": "kolom_tgl",  "className": "text-left"};
             dataColums.push(obj);
             day = day_start.getDate()
            //  console.log(day);
             dataRow += `<th style="vertical-align: middle !important;"><center>${day}</center></th>`; 
            //  $('#xin_table_recap').find('thead #tr2').append(`<th style="vertical-align: middle !important;"><center>${day}</center></th> `);
             day_start.setDate(day_start.getDate() + 1);
          }
          dataRow += '</tr>';
          $('#xin_table_recap thead').append(dataRow);
          let obj2 = {"name": "kolom_total",  "className": "text-left"};
          let obj3 = {"name": "kolom_jumlah",  "className": "text-left"};
          dataColums.push(obj2);
          dataColums.push(obj3);
          console.log(dataColums);
         
          // $('#p_month').html(month_year);  
          var xin_table_borongan = $('#xin_table_recap').DataTable({               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],   
              
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/employees_timelate_rekap_list/',
                    data: {                 
                        company_id  : company_id, 
                        department_id   : department_id,
                        start_date   : start_date,
                        end_date   : end_date,                                       
                    }
                },                                      
                 "columns":dataColums,
                 buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
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