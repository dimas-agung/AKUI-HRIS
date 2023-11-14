<?php
/* Generate Payslip view
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
?>

<form action="<?php echo base_url() ?>admin/reports/export_gaji_harian_periode_rekap"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">
  <div class="row <?php echo $get_animate;?>">
      <div class="col-md-12">
        <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Menampilkan : Rekap Gaji Harian Periode </h3>
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
                            <label for="first_name">Bulan Kerja Harian</label>
                            <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="Pilih Bulan Kerja Harian" required>                    
                              <option value=""> Pilih Bulan Kerja Harian </option>
                              <?php foreach($all_bulan_gaji as $bulan_gaji) {?>                                                   
                                <option value="<?php echo $bulan_gaji->month_payroll;?>" > <?php echo strtoupper($bulan_gaji->desc); ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-2">
                          <div class="form-group" id="periode_ajax">
                            <label for="first_name">Periode Kerja Harian</label>
                            <select disabled="disabled"  class="form-control input-sm" name="periode_id" id="periode_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                    
                                 <option value="0"> Pilih Periode Kerja Harian </option>
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
            <th width="240px"><center> Periode Gaji </center></th>
            <th width="350px"><center> Nama Karyawan </center></th>
            <th width="250px"><center> Departemen </center></th>
            <th width="250px"><center> Workstation </center></th>            
            <th width="250px"><center> Posisi </center></th>
            <th width="120px"><center> Total Gaji</center></th>
            <th width="150px"><center> #No.Rekening</center></th>
            <th width="120px"><center> #Bank</center></th>            
          </tr>
        </thead>
          <tfoot style="font-size: 14px !important;">
          <tr>
            <th colspan="6">   <span class="info_resume_info" style="float: right;"></span>  &nbsp;&nbsp; </th>            
            <th width="120px"><right> <span class=" blink blink-one info_resume_gaji" style="font-size: 14px !important;"></span> </right></center></th>
            <th width="150px"></th>
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
        var company_id  = document.getElementById("aj_company").value; 
        var periode_id  = document.getElementById("periode_id").value;
    
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
         else if (periode_id == '' )
        {
            alert("Periode Kerja Harian Belum Diisi !");
             $("#periode_id").focus();              
        }  
        else
        {
    
           $(".info_harian").html('Loading ...');

           $(".info_resume_info").html('');
           
           $(".info_resume_gaji").html('');

           tampilkan_tabel();

           tampilkan_button();
           
            var xin_table_harian = $('#xin_table_harian').DataTable({               
              "bDestroy"        : true,
              "bSort"           : false,
              "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],   
              autoWidth         : true, 

              ajax: {
                    url: '<?php echo base_url(); ?>admin/reports/gaji_harian_periode_rekap_list/',
                    data: {                 
                        company_id : company_id, 
                        periode_id   : periode_id                  
                    }
              },  
                                    
              "columns": [
                  {"name": "kolom_1", "className": "text-center"},
                  {"name": "kolom_2", "className": "text-center"},
                  {"name": "kolom_3", "className": "text-left"},
                  {"name": "kolom_4", "className": "text-left"},
                  {"name": "kolom_5", "className": "text-left"},
                  {"name": "kolom_6", "className": "text-left"},
                  {"name": "kolom_7", "className": "text-right"},
                  {"name": "kolom_8", "className": "text-center"},
                  {"name": "kolom_8", "className": "text-center"},
                  
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
                url  : '<?php echo base_url();?>admin/reports/gaji_harian_rekap_jumlah/',
                data : { 
                   company_id : company_id, 
                   periode_id : periode_id
                    
                },
                dataType : "json",
                success:function(data){                        
                    
                    for(var i=0; i<data.val.length;i++){
                        
                      $(".info_harian").html('Berikut ini Daftar Gaji Harian - Periode : <b>'+ data.val[i].start_date +' s/d '+ data.val[i].end_date +' </b>, Jumlah Karyawan : <b>'+ data.val[i].jumlah_karyawan +'</b> Orang, Grand Total : <b> '+ data.val[i].jumlah_gaji +'</b>, Jumlah : <b>'+ data.val[i].jumlah_periode +'  </b> Periode. ');
                      
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
         var company_id  = document.getElementById("aj_company").value; 
        var periode_id  = document.getElementById("periode_id").value;

        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
        else if (periode_id == '' )
        {
            alert("Periode Kerja Harian Belum Diisi !");
             $("#periode_id").focus();   
            
        } else {

            $('#formReportExport').submit();
        }
    }

</script>