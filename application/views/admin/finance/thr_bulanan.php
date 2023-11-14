<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<?php $thr_year         = $this->input->post('thr_year'); ?>

<?php
 if(!isset($thr_year)){
    $skrg     = date('Y-m-d');
    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $thr_year  = $xin_bulan[0]->tahun;
    $bulan       = $xin_bulan[0]->tahun;
 }
?>

<div class="row <?php echo $get_animate;?>">    
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Tampilkan THR Karyawan Bulanan</h3>
        </div>
        <div class="box-body">
          <div class="row">
              <div class="col-md-12">                  
                  <form action="<?php echo base_url() ?>admin/finance/export_thr_bulanan"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">                    
                      <div class="box-body">
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
                                <label for="first_name">Tahun THR </label>
                                <select class="form-control input-sm" name="thr_year" id="thr_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                                
                                  <?php foreach($all_tahun_thr as $tahun_thr) {?>                                    
                                    <option value="<?php echo $tahun_thr->tahun;?>" <?php if($tahun_thr->tahun==$thr_year): ?> selected="selected" <?php endif; ?>><?php echo $tahun_thr->tahun?></option>                          
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label for="first_name"> Tanggal Batas THR </label>
                                <input class="form-control thr_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="tanggal_thr" name="tanggal_thr" type="text" value="<?php echo date('Y-m-d');?>">
                              </div>
                            </div> 
                            <div class="col-md-3">
                              <div class="form-group" style="float: left;margin-top: 25px;">
                                <div class="form-actions">
                                  <button type="button" class="btn btn-success" onclick="searchDataAttendance()">
                                     <i class="fa fa-filter"></i> Tampilkan
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                  </form>            
              </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="box <?php echo $get_animate;?>" >
  <div class="box-header with-border">

    <h3 class="box-title"><span class="info_thr"></span> </h3>   
    <div id ="myBtn" style="display:none;">
        <div class="box-tools pull-right">                   
          <!-- <button class="btn btn-xs btn-primary" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataTHRXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
              <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.csv)
          </button> -->                 
        </div>
     </div>  
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_thr" width="100%">
        <thead>
          <tr>
             <th width="50px"><center>No.</center></th>
            <th width="120px"><center> Tahun THR </center></th>
             <th width="120px"><center> Batas THR </center></th>
            <th width="300px"><center> Nama Karyawan </center></th>
            <th width="250px"><center> Perusahaan </center></th>
            <th width="300px"><center> Departemen </center></th>
            <th width="350px"><center> Posisi </center></th>
            <th width="120px"><center> Total THR</center></th>
            <th width="120px"><center> #No.Rekening</center></th>
            <th width="120px"><center> #Bank</center></th>         
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

    function searchDataAttendance() 
    {
       
        var company_id  = jQuery('#aj_company').val();
         var thr_year  = jQuery('#thr_year').val();
          var tanggal_thr  = jQuery('#tanggal_thr').val();
    
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();
        } 
        else if (thr_year == '' )
        {
            alert("Tahun THR Belum Diisi !");
            $("#thr_year").focus();
        } 
         else if (tanggal_thr == '' )
        {
            alert("Tanggal Batas THR Belum Diisi !");
            $("#tanggal_thr").focus();
        } 
        else 
        {          
           tampilkan_tabel();

           tampilkan_button();

           $(".info_thr").html('Berikut ini THR Karyawan Bulanan, Tahun : <b>'+ thr_year +'</b>, Tanggal Batas THR : <b>'+ tanggal_thr +'</b> ');

            // $('#p_month').html(thr_year);  
            var xin_table_thr = $('#xin_table_thr').DataTable({

               
              "bDestroy"        : true,
              "bSort"           : false,
              "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],       
              autoWidth         : true, 

              ajax: {
                    url: '<?php echo base_url(); ?>admin/finance/slip_thr_bulanan_list/',
                    data: {                 
                        company_id   : company_id, 
                        thr_year   : thr_year,
                         tanggal_thr   : tanggal_thr                        
                    }
              },  
                                    
              "columns": [
                  {"name": "kolom_1", "className": "text-center"},
                   {"name": "kolom_1", "className": "text-center"},
                  {"name": "kolom_2", "className": "text-center"},
                  {"name": "kolom_3", "className": "text-left"},
                  {"name": "kolom_4", "className": "text-center"},
                  {"name": "kolom_5", "className": "text-left"},
                  {"name": "kolom_6", "className": "text-left"},
                  {"name": "kolom_7", "className": "text-right"},
                  {"name": "kolom_8", "className": "text-center"},
                  {"name": "kolom_8", "className": "text-center"},
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

    function exportDataTHRXls()  
    {
                
       
       var company_id  = jQuery('#aj_company').val();
      var thr_year  = jQuery('#thr_year').val();
      var tanggal_thr  = jQuery('#tanggal_thr').val();

        if (company_id == '' ){
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } else if (thr_year == '' ){
            alert("Tahun THR Belum Diisi !");
             $("#thr_year").focus();


        } else if (tanggal_thr == '' ){
            alert("Tanggal Batasa THR Belum Diisi !");
             $("#tanggal_thr").focus();        
       
        } else {

            $('#formReportExport').submit();
        }
    }  


</script>
