<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<?php $start_date         = $this->input->post('start_date'); ?>
<?php $end_date         = $this->input->post('end_date'); ?>


<?php
 if(!isset($month_year)){
    $skrg     = date('Y-m-d');
    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $month_year  = $xin_bulan[0]->month_payroll;
    $bulan       = $xin_bulan[0]->month_payroll;
 }

?>
<form action="<?php echo base_url() ?>admin/reports/export_report_produktifitas_per_periode_list"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">
  <div class="row <?php echo $get_animate;?>">
      <div class="col-md-12">
        <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Menampilkan : Produktifitas Per Periode </h3>
          </div>
          <div class="box-body">
            <div class="row">
                <div class="col-md-12">                   
                  <div class="row">

                    <div class="col-md-3">
                       <div class="form-group">
                        <label for="first_name"><?php echo $this->lang->line('xin_company_select');?></label>
                        <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_company');?>">
                          <option value=""></option>
                          <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                          <?php } ?>
                        </select>
                      </div>         
                    </div>  

                    <div class="col-md-3">  
                      <div class="form-group" id="workstation_ajax">
                        <label for="name"><?php echo $this->lang->line('xin_workstation_select');?></label>
                        <select disabled="disabled" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation');?>" name="workstation_id">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>                   

                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="first_name"> Tanggal Mulai  </label>
                        <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-01');?>">
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="first_name"> Tanggal Sampai  </label>
                        <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d');?>">
                      </div>
                    </div>
                   
                    <div class="col-md-2">
                      <div class="form-group" style="float: left;margin-top: 22px;">
                        <div class="form-actions">
                          <button type="button" class="btn btn-primary" onclick="searchDataProduktifitasPeriode()">
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
    <h3 class="box-title"><span class="info_report_produktifitas_per_periode"></span> </h3>   
    <div class="box-tools pull-right">        
        <div class="box-tools pull-right" id ="myBtn" style="display:none;">                   
         <!--  <button class="btn btn-xs btn-info" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataProduktifitasBoronganXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
              <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
          </button>   -->            
        </div>    
    </div>   
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
       <table class="datatables-demo table table-striped table-bordered" id="xin_table_produktifitas_per_periode" width="100%">
          <thead>
            <tr>
              <th width="60px"  style="text-align: center !important;"><center>No</center></th>             
                            
              <th width="170px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employees_id');?></center></th>
              <th width="250px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_name');?></center></th>
             
               <th style="text-align: center !important;"><center> Keterangan Produktifitas</center></th>

            </tr>
          </thead>
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

    function searchDataProduktifitasPeriode() 
    {
        // var month_year  = jQuery('#month_year').val();
        var company_id      = jQuery('#aj_company').val();
        var workstation_id  = jQuery('#workstation_id').val();
        var start_date      = jQuery('#start_date').val();
        var end_date        = jQuery('#end_date').val();

        // var company_id  = jQuery('#aj_companyx').val();
    
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Dipilih !");
            $("#company").focus();

        } 
        else if (workstation_id == '' )
        {
            alert("Workstation Belum Dipilih !");
             $("#workstation_id").focus();  
            
        } 

        else if (start_date == '' )
        {
            alert("Tanggal Mulai Belum Ditentukan !");
             $("#start_date").focus();  
            
        }

        else if (end_date == '' )
        {
            alert("Tanggal Sampai Ditentukan !");
             $("#end_date").focus();  
            
        } 
        
        
        { 
        
        var s_date = $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
        var e_date = $('#end_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
        
         $periode = s_date + ' ' + e_date;
  
          $('#p_month').html($periode); 

            tampilkan_tabel();

            tampilkan_button();

           $(".info_report_produktifitas_per_periode").html('Berikut ini Produktifitas Per Periode : '+ $periode +' ');

            // $('#p_month').html(month_year);  
            var xin_table_produktifitas_per_periode = $('#xin_table_produktifitas_per_periode').DataTable({

               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/report_produktifitas_per_periode_list/',
                    data: {                 
                        company_id     : company_id, 
                        workstation_id : workstation_id,
                        start_date     : start_date,
                        end_date     : end_date
                    }
                },  
                                    
                "columns": [
                   
                    {"name": "kolom_1",  "className": "text-center"},
                             
                    {"name": "kolom_3",  "className": "text-center"},
                    {"name": "kolom_4",  "className": "text-left"},
                    {"name": "kolom_8",  "className": "text-left"},
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
        }
    }

    function exportDataProduktifitasBoronganXls()  
    {
         // var month_year  = jQuery('#month_year').val();
        var company_id      = jQuery('#aj_company').val();
        var workstation_id  = jQuery('#workstation_id').val();
        var start_date      = jQuery('#start_date').val();
        var end_date      = jQuery('#end_date').val();
        
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Dipilih !");
            $("#company").focus();

        } 
        else if (workstation_id == '' )
        {
            alert("Workstation Belum Dipilih !");
             $("#workstation_id").focus();  
            
        } 

        else if (start_date == '' )
        {
            alert("Tanggal Mulai Belum Ditentukan !");
             $("#start_date").focus();  

        } 

        else if (end_date == '' )
        {
            alert("Tanggal Sampai Belum Ditentukan !");
             $("#end_date").focus();      
                    
        } else {

            $('#formReportExport').submit();
        }
    }  


</script>
