<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<?php $tahun_gaji         = $this->input->post('tahun_gaji'); ?>

<?php
 if(!isset($tahun_gaji)){
    $skrg     = date('Y-m-d');
    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $tahun_gaji  = $xin_bulan[0]->month_payroll;
    $bulan       = $xin_bulan[0]->month_payroll;
 }
?>

<form action="<?php echo base_url() ?>admin/reports/finance_export_resume_tahunan_bulanan"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">                    
                 
<div class="row <?php echo $get_animate;?>">    
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Tampilkan Finance Resume Gaji Tahunan - Bulanan </h3>
        </div>
        <div class="box-body">
          <div class="row">
              <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="department"><?php echo $this->lang->line('module_company_title');?></label>
                        <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>" required>                                 
                          <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                          <?php } ?>
                        </select>
                      </div>                          
                    </div>                          
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="first_name">Tahun Gaji </label>
                        <select class="form-control input-sm" name="tahun_gaji" id="tahun_gaji" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                                
                          <?php foreach($all_tahun_gaji as $info_tahun_gaji) {?>                                    
                            <option value="<?php echo $info_tahun_gaji->tahun;?>" <?php if($info_tahun_gaji->tahun==$tahun_gaji): ?> selected="selected" <?php endif; ?>><?php echo $info_tahun_gaji->tahun?></option>                          
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group" style="float: left;margin-top: 25px;">
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
    <h3 class="box-title"><span class="info_resume"></span> </h3>   
    <div class="box-tools pull-right">            
    </div>   
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="100%">
        <thead>
          <tr>
             <th width="40px" ><center> No.</center></th>
             <th width="120px" ><center> Bulan Gaji </center></th>
             <th width="200px"><center> Jumlah Karyawan </center></th>
             <th width="200px"><center> Total </center></th>
             <th "><center> Keterangan </center></th>                
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

    function searchDataAttendance() 
    {
        var tahun_gaji  = jQuery('#tahun_gaji').val();
        var company_id  = jQuery('#aj_company').val();
    
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();
        } 
        else if (tahun_gaji == '' )
        {
            alert("Bulan Gaji Belum Diisi !");
            $("#tahun_gaji").focus();
        } 
        else 
        {          
           tampilkan_tabel();

           $(".info_resume").html('Berikut ini Daftar Gaji Tahun : <b>'+ tahun_gaji +'</b> ');

            // $('#p_month').html(tahun_gaji);  
            var xin_table_bulanan = $('#xin_table_bulanan').DataTable({

               
              "bDestroy"        : true,
              "bSort"           : false,
              "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],      
              autoWidth         : true, 

              ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/finance_resume_tahunan_bulanan_list/',
                    data: {                 
                        company_id   : company_id, 
                        tahun_gaji   : tahun_gaji                        
                    }
              },  
                                    
              "columns": [
                  {"name": "kolom_1",  "className": "text-center"},
                  {"name": "kolom_2",  "className": "text-center"},
                  {"name": "kolom_3",  "className": "text-center"},
                  {"name": "kolom_4",  "className": "text-right"},                 
                   {"name": "kolom_5",  "className": "text-left"},
                  // {"name": "kolom_9",  "className": "text-right"},
              ],
               dom: 'lBfrtip',
              "buttons": ['excel'], // colvis > if needed

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

    // function exportDataAttendanceXls()  
    // {
                
    //    var tahun_gaji  = jQuery('#tahun_gaji').val();
    //    var company_id  = jQuery('#aj_company').val();
    
    //     if (company_id == '' ){
    //         alert("Nama Perusahaan Belum Diisi !");
    //         $("#company").focus();

    //     } else if (tahun_gaji == '' ){
    //         alert("Bulan Gaji Belum Diisi !");
    //          $("#tahun_gaji").focus();        
       
    //     } else {

    //         $('#formReportExport').submit();
    //     }
    // }  


</script>
