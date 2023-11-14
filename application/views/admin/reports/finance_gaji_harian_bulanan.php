<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<form action="<?php echo base_url() ?>admin/reports/finance_export_gaji_harian_bulanan"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">
  <div class="row <?php echo $get_animate;?>">
      <div class="col-md-12">
        <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Tampilkan Finance : Rekap Gaji Harian Bulanan </h3>
          </div>
          <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                   
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="department">Perusahaan </label>
                            <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>" required>
                              <?php foreach($all_companies as $company) {?>
                              <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                              <?php } ?>
                            </select>
                          </div>                          
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="first_name"> Tanggal Mulai </label>
                            <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-01');?>">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="first_name"> Tanggal Sampai </label>
                            <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d');?>">
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
    <h3 class="box-title"><span class="info_harian"></span> </h3>   
    <div class="box-tools pull-right">        
        <div class="box-tools pull-right" id ="myBtn" style="display:none;">                     
           <button class="btn btn-xs btn-info" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataGajiHarianXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
              <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
          </button>                 
        </div>    
    </div>   
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_harian" width="100%">
        <thead>

          <tr>
            <th width="50px"><center> No.</center></th>
            <th width="120px"><center> NIP </center></th>
            <th width="300px"><center> Nama Karyawan </center></th>
            <th width="120px"><center> Total Gaji</center></th>
            <th width="150px"><center> #No.Rekening</center></th>
            <th width="110px"><center> #Bank</center></th>  
            <th width="600px"><center> Keterangan Periode Kerja </center></th>          
          </tr>
        </thead>
          <tfoot style="font-size: 14px !important;">
          <tr>
            <th colspan="3">   <span class="info_resume_info" style="float: right;"></span>  &nbsp;&nbsp; </th>            
            <th width="120px"><right> <span class=" blink blink-one info_resume_gaji" style="font-size: 14px !important;"></span> </right></center></th>
            <th width="150px"></th>
            <th width="120px"></th>
             <th width="120px"></th>            
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
        var start_date  = jQuery('#start_date').val();
        var end_date    = jQuery('#end_date').val();
        var company_id  = jQuery('#aj_company').val();
    
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
        else if (start_date == '' )
        {
            alert("Tanggal Start Belum Diisi !");
             $("#start_date").focus();  
            
        } 
        else if (end_date == '' )
        {
            alert("Tanggal Finish Belum Diisi !");
             $("#end_date").focus();  
            
        } 
        else
        {

          var s_date = $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
          var e_date = $('#end_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();

           $periode = s_date+' s/d '+e_date;
    
           $(".info_harian").html('Loading ...');

           $(".info_resume_info").html('');
           
           $(".info_resume_gaji").html('');

           tampilkan_tabel();

           tampilkan_button();
           
            var xin_table_harian = $('#xin_table_harian').DataTable({               
              "bDestroy"        : true,
              "bSort"           : false,
              "aLengthMenu"     : [[8, 10, 30, 50, 100, -1], [8, 10, 30, 50, 100, "All"]],   
              autoWidth         : true, 

              ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/finance_gaji_harian_bulanan_list/',
                    data: {                 
                        company_id : company_id, 
                        start_date : start_date,
                        end_date   : end_date                       
                    }
              },  
                                    
              "columns": [
                  {"name": "kolom_1", "className": "text-center"},
                  {"name": "kolom_2", "className": "text-center"},
                  {"name": "kolom_3", "className": "text-left"},
                
                 
                  {"name": "kolom_7", "className": "text-right"},
                  {"name": "kolom_8", "className": "text-center"},
                  {"name": "kolom_9", "className": "text-center"},
                  {"name": "kolom_10", "className": "text-left"},
              ],
               // dom: 'lBfrtip',
              // "buttons": ['excel'], // colvis > if needed

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
                url  : '<?php echo base_url();?>admin/reports/finance_gaji_harian_bulanan_jumlah/',
                data : { 
                   company_id : company_id, 
                    start_date : start_date,
                    end_date   : end_date  
                    
                },
                dataType : "json",
                success:function(data){                        
                    
                    for(var i=0; i<data.val.length;i++){
                        
                      $(".info_harian").html('Berikut ini Daftar Gaji Harian - Periode : <b>'+ start_date +' s/d '+ end_date +' </b>, Jumlah Karyawan : <b>'+ data.val[i].jumlah_karyawan +'</b> Orang, Grand Total : <b> '+ data.val[i].jumlah_gaji +'</b>, Perusahaan : <b>'+ data.val[i].company_name +'  </b>. ');
                      
                      $(".info_resume_info").html(' Grand Total ');

                       $(".info_resume_karyawan").html(''+ data.val[i].jumlah_karyawan +'');

                      $(".info_resume_gaji").html(''+ data.val[i].jumlah_gaji +'');

                    }
                }
            });

        }
    }

    function exportDataGajiHarianXls()  
    {
         // var month_year  = jQuery('#month_year').val();
        var company_id  = jQuery('#aj_company').val();
        var start_date  = jQuery('#start_date').val();
        var end_date    = jQuery('#end_date').val();

        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
        else if (start_date == '' )
        {
            alert("Tanggal Start Belum Diisi !");
             $("#start_date").focus();  
            
        } 
        else if (end_date == '' )
        {
            alert("Tanggal Finish Belum Diisi !");
             $("#end_date").focus();  
            
        } else {

            $('#formReportExport').submit();
        }
    }

</script>