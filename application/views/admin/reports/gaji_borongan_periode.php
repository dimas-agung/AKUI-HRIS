<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<!-- <?php $start_date         = $this->input->post('start_date'); ?>
<?php $end_date         = $this->input->post('end_date'); ?>
 -->
<?php

$company_id    = $this->input->post('company_id');
$month_year    = $this->input->post('month_year');


?>
<form action="<?php echo base_url() ?>admin/reports/export_borongan_periode"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">
  <div class="row <?php echo $get_animate;?>">
      <div class="col-md-12">
        <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Menampilkan : Detail Gaji Borongan Periode </h3>
          </div>
          <div class="box-body">
            <div class="row">
                <div class="col-md-12">                   
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="department">Perusahaan </label>
                        <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="Pilih Nama Perusahaan" required>
                          <option value=""> Pilih Nama Perusahaan </option>
                          <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                          <?php } ?>
                        </select>
                      </div>                          
                    </div>

                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="first_name">Bulan Kerja Borongan</label>
                        <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="Pilih Bulan Kerja Borongan" required>                    
                          <option value=""> Pilih Bulan Kerja Borongan </option>
                          <?php foreach($all_bulan_gaji as $bulan_gaji) {?>                                                   
                            <option value="<?php echo $bulan_gaji->month_payroll;?>" > <?php echo strtoupper($bulan_gaji->desc); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-2">
                     <div class="form-group" id="periode_ajax">
                        <label for="first_name">Periode Kerja Borongan</label>
                        <select disabled="disabled"  class="form-control input-sm" name="periode_id" id="periode_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                    
                             <option value="0"> Pilih Periode Kerja Borongan </option>
                        </select>
                      </div>
                    </div>
                  
                    <div class="col-md-2">
                      <div class="form-group" style="float: left;margin-top: 22px;">
                        <div class="form-actions">
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
        </div>
      </div>
  </div>
</form>

<div class="box <?php echo $get_animate;?>" >
  <div class="box-header with-border">
    <h3 class="box-title"><span class="info_report_gaji_borongan"></span> </h3>   
    <div class="box-tools pull-right">        
        <div class="box-tools pull-right" id ="myBtn" style="display:none;">                   
          <button class="btn btn-xs btn-info" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataGajiboronganXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
              <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
          </button>              
        </div>    
    </div>   
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="190%">
        <thead>
          <tr>                       
            <th width="40px"  style="text-align: center !important;"><center>No</center></th>             
            <th width="350px" style="text-align: center !important;"><center>Periode Gaji</center></th>
            <th width="170px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employees_id');?></center></th>
            <th width="550px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_name');?></center></th>
            <th width="350px" style="text-align: center !important;"><center> Workstation </center></th> 
            <th width="320px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_designation_txt');?></center></th>            
            <th width="120px" style="text-align: center !important;"><center>Tanggal<br>Mulai Kerja </center></th>            
            <th width="120px" style="text-align: center !important;"><center>Masa<br>Kerja</center></th>
            <th width="100px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_status');?></center></th>
            <th width="100px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_contrack');?></center></th>
            <th width="100px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_grade');?></center></th>             
           
            <th width="170px" style="text-align: center !important;"><center> Jumlah<br>Hari Kerja</center></th>
            <th width="170px" style="text-align: center !important;"><center> Total<br>Gram</center></th>
            <th width="170px" style="text-align: center !important;"><center> Total<br>Upah</center></th>
            <th width="170px" style="text-align: center !important;"><center> Total<br>Insentif</center></th>
            <th width="170px" style="text-align: center !important;"><center> Total<br>Tambahan </center></th>
            <th width="170px" style="text-align: center !important;"><center> Total<br>Diperbantukan </center></th>
            <th width="170px" style="text-align: center !important;"><center> BPJS Kes </center></th>
            <th width="170px" style="text-align: center !important;"><center> BPJS TK</center></th>
            <th width="170px" style="text-align: center !important;"><center> Potongan Lain </center></th>

            <th width="120px" style="text-align: center !important;"><center> <?php echo $this->lang->line('xin_payroll_total_salary');?></center></th>            
            <th width="120px" style="text-align: center !important;"><center> No. Rekening </center></th>
            <th width="120px" style="text-align: center !important;"><center> Bank Transfer </center></th>
            <th width="120px" style="text-align: center !important;"><center> Email </center></th>
          </tr> 
           
                   
        </thead>
        <tfoot>
            <tr>                       
            <th colspan ="20" style="text-align: right !important;"> <span class="info_resume_info"></span></th>             
            <th width="120px" style="text-align: right !important;"> <span class="info_resume_gaji"></span> </th>       
            <th width="120px" ><center> </center></th>
            <th width="120px" ><center> </center></th>
            <th width="120px" ><center> </center></th>
          </tr> 
        </tfoot>
      </table>

    </div>
  </div>
</div>





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


    function tampilkan_tabel() 
    {
      var x = document.getElementById("myDIV");
      if (x.style.display === "none") {
        x.style.display = "block";
      } 
    } 

    function tampilkan_button() 
    {
      var x = document.getElementById("myBtn");
      if (x.style.display === "none") {
        x.style.display = "block";
      } 
    } 

    function searchDataAttendance() 
    {
        var company_id  = document.getElementById("aj_company").value; 
        var periode_id  = document.getElementById("periode_id").value;
        
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();
        } 
        else if (periode_id == '' )
        {
            alert("Periode Kerja Borongan Belum Diisi !");
             $("#periode_id").focus();              
        } 
        
        else 
        {        

            tampilkan_tabel();

            tampilkan_button();

           $(".info_report_gaji_borongan").html('Loading ..');

            // $('#p_month').html(month_year);  
            var xin_table_borongan = $('#xin_table_borongan').DataTable({               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/gaji_borongan_periode_list/',
                    data: {                 
                        company_id  : company_id, 
                        periode_id   : periode_id
                                              
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
                    {"name": "kolom_11", "className": "text-center"},                
                    {"name": "kolom_12", "className": "text-right"},
                    {"name": "kolom_13", "className": "text-right"},
                    {"name": "kolom_14", "className": "text-right"},
                    {"name": "kolom_15", "className": "text-right"},
                    {"name": "kolom_24", "className": "text-right"},
                    {"name": "kolom_16", "className": "text-right"},
                    {"name": "kolom_17", "className": "text-right"},
                    {"name": "kolom_18", "className": "text-right"},
                    {"name": "kolom_19", "className": "text-right"},
                    {"name": "kolom_20", "className": "text-right"},                          
                    {"name": "kolom_21", "className": "text-center"},
                    {"name": "kolom_22", "className": "text-center"},
                    {"name": "kolom_23", "className": "text-left"}    

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

             $.ajax({
                type : "GET",
                url  : '<?php echo base_url();?>admin/reports/gaji_borongan_periode_jumlah/',
                data : { 
                   company_id : company_id, 
                  periode_id : periode_id
                      
                    
                },
                dataType : "json",
                success:function(data){                        
                    
                    for(var i=0; i<data.val.length;i++){
                        
                      $(".info_report_gaji_borongan").html('<i class="fa fa-info-circle"></i> Berikut ini Daftar Gaji Borongan - Periode : <b>'+ data.val[i].start_date +' s/d '+ data.val[i].end_date +' </b>, Jumlah Karyawan : <b>'+ data.val[i].jumlah_karyawan +'</b> Orang, Grand Total : <b> '+ data.val[i].jumlah_gaji +'</b>, Perusahaan : <b>'+ data.val[i].company_name +'  </b>. ');
                      
                      
                      $(".info_resume_info").html(' Grand Total &nbsp;');

                       $(".info_resume_karyawan").html(''+ data.val[i].jumlah_karyawan +'');

                      $(".info_resume_gaji").html('<span class="blink blink-one">'+ data.val[i].jumlah_gaji +'</span>');
                    }
                }
            });
        }
    }

    function exportDataGajiboronganXls()  
    {
         // var month_year  = jQuery('#month_year').val();
        var company_id  = document.getElementById("aj_company").value; 
        var periode_id  = document.getElementById("periode_id").value;
       
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
        else if (periode_id == '' )
        {
            alert("Periode Kerja Borongan Belum Diisi !");
             $("#periode_id").focus();  
             
            
        } else {

            $('#formReportExport').submit();
        }
    }  


</script>
