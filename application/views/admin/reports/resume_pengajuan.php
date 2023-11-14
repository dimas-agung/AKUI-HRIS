<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<?php $month_year         = $this->input->post('month_year'); ?>

<?php
 if(!isset($month_year)){
    $skrg     = date('Y-m-d');
    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $month_year  = $xin_bulan[0]->month_payroll;
    $bulan       = $xin_bulan[0]->month_payroll;
 }

?>

<div class="row <?php echo $get_animate;?>">    
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Tampilkan Resume Gaji Bulanan </h3>
        </div>
        <div class="box-body">
          <div class="row">
              <div class="col-md-12">                  
                  <form action="<?php echo base_url() ?>admin/reports/export_resume_pengajuan_bulanan"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">                    
                      <!-- <div class="box-body"> -->
                          <div class="row">
                                                      
                            <div class="col-md-2">
                              <div class="form-group">
                                <label for="first_name">Bulan Gaji </label>
                                <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>
                                  <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                                    <option value="<?php echo $bulan_gaji->month_payroll;?>" <?php if($bulan_gaji->month_payroll==$month_year): ?> selected="selected" <?php endif; ?>><?php echo $bulan_gaji->desc?></option>
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
                      <!-- </div> -->
                  </form>            
              </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="box <?php echo $get_animate;?>" >
  <div class="box-header with-border">
    <h3 class="box-title"><span class="info_resume"></span> </h3>   
    <div class="box-tools pull-right" id ="myBtn" style="display:none;">        
        <button class="btn btn-xs btn-info" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataResumeXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
              <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
          </button>     
    </div>   
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="100%">
        <thead>
          <tr>
             <th width="40px" ><center> No.</center></th>
             <th width="300px"><center> Perusahaan </center></th>
             <th width="100px"><center> Bulan Gaji </center></th>
             <th width="100px"><center> Total<br>Karyawan </center></th>
             <th width="100px"><center> Total<br>Gaji </center></th>
             <th><center> Detail </center></th>                
          </tr>
        </thead>
        <tfoot style="font-size: 14px !important;">
          <tr>
            <th colspan="3">   <span class="info_resume_info" style="float: right;"></span>  &nbsp;&nbsp; </th>
             <th width="100px" ><center> <span class="info_resume_karyawan"></span> </center></th>
             <th width="100px" ><right>  <span class=" blink blink-one info_resume_gaji" style="font-size: 14px !important;"></span> </right></th>             
             <th ><center>  </center></th>                
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
        var month_year  = jQuery('#month_year').val();
            
        if (month_year == '' )
        {
            alert("Bulan Gaji Belum Diisi !");
            $("#month_year").focus();
        } 
        else 
        {          
           tampilkan_tabel();

           tampilkan_button();

           $(".info_resume").html('Loading ... ');

           $(".info_resume_info").html('');

           $(".info_resume_karyawan").html('');

           $(".info_resume_gaji").html('');

            // $('#p_month').html(month_year);  
            var xin_table_bulanan = $('#xin_table_bulanan').DataTable({

               
              "bDestroy"        : true,
              "bSort"           : false,
              "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
              autoWidth         : true, 

              ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/resume_pengajuan_gaji_bulanan_list/',
                    data: {                 
                        month_year   : month_year                        
                    }
              },  
                                    
              "columns": [
                  {"name": "kolom_1",  "className": "text-center"},
                  {"name": "kolom_2",  "className": "text-left"},
                  {"name": "kolom_2",  "className": "text-center"},
                  {"name": "kolom_3",  "className": "text-center"},
                  {"name": "kolom_4",  "className": "text-right"},                 
                  {"name": "kolom_5",  "className": "text-left"},
                  // {"name": "kolom_9",  "className": "text-right"},
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
                url  : '<?php echo base_url();?>admin/reports/resume_pengajuan_bulanan_jumlah/',
                data : { 
                    month_year : month_year
                    
                },
                dataType : "json",
                success:function(data){                        
                    
                    for(var i=0; i<data.val.length;i++){
                        
                       $(".info_resume").html('Berikut ini Daftar Resume Pengajuan - Gaji Bulan : <b>'+ data.val[i].bulan_gaji +'</b>, Jumlah Karyawan : <b> '+ data.val[i].jumlah_karyawan +' Orang </b>, Grand Total : <b> '+ data.val[i].jumlah_gaji +'</b> ');
                      
                       $(".info_resume_info").html(' Grand Total ');

                      $(".info_resume_karyawan").html(''+ data.val[i].jumlah_karyawan +'');

                      $(".info_resume_gaji").html(''+ data.val[i].jumlah_gaji +'');

                    }
                }
            });

           
        }
    }

    function exportDataResumeXls()  
    {
                
       var month_year  = jQuery('#month_year').val();
       
       if (month_year == '' ){
            alert("Bulan Gaji Belum Diisi !");
             $("#month_year").focus();        
       
        } else {

            $('#formReportExport').submit();
        }
    }  


</script>
