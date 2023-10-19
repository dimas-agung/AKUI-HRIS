<?php
/* Employee Import view
*/
?>
<?php $session            = $this->session->userdata('username'); ?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']); ?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $get_animate        = $this->Core_model->get_content_animate(); ?>
<?php $system             = $this->Core_model->read_setting_info(1); ?>

<?php $start_date         = $this->input->post('start_date'); ?>
<?php $end_date         = $this->input->post('end_date'); ?>


<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>


<section id="basic-listgroup">
  <div class="row match-heights <?php echo $get_animate?>">
    
    <div class="col-md-12 current-tab animated fadeInRight" id="import_gram">
      <div class="box">
        
        <div class="box-header  with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_import_gram').' - '.$this->lang->line('xin_employee_import_csv_file');?></h3>
        </div>

        <div class="box-body">
          <p class="card-text"><?php echo $this->lang->line('xin_gramasi_import_description_line1');?></p>
          <p class="card-text"><?php echo $this->lang->line('xin_gramasi_import_description_line2');?></p>
          
          <h6><a href="<?php echo base_url();?>uploads/csv/sample-csv-gram.csv" class="btn btn-default"> <i class="fa fa-download"></i> <?php echo $this->lang->line('xin_employee_import_download_sample');?> </a></h6>
          
          <?php $attributes = array('name' => 'import_gramasi', 'id' => 'import_gramasi', 'autocomplete' => 'off');?>
          <?php $hidden     = array('user_id' => $session['user_id']);?>
          
          <?php echo form_open_multipart('admin/import/import_gram', $attributes, $hidden);?>
          
          <div class="row">
            <div class="col-md-3">
                  <div class="form-group">
                    <label for="first_name"><?php echo $this->lang->line('xin_company_select'); ?></label>
                    <select class="form-control" name="company_id" id="company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_company'); ?>" required>
                      <option value=""></option>
                      <?php foreach ($all_companies as $company) { ?>
                        <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?></option>
                      <?php } ?>
                    </select>
                  </div>
            </div>
            <!-- <div class="col-md-2">
                            <div class="form-group">
                              <label for="first_name"> Workstation  </label>
                                <select class="form-control" name="workstation_id" id="workstation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation'); ?>">
                                  <option value=""></option>
                                  <?php foreach ($get_all_workstation as $workstation) { ?>
                                    <option value="<?php echo $workstation->workstation_id ?>"><?php echo $workstation->workstation_name ?></option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div> -->
            <div class="col-md-4">
              <div class="form-group">
                <fieldset class="form-group">
                  <label for="logo"><?php echo $this->lang->line('xin_employee_upload_file');?><i class="hris-asterisk">*</i></label>
                  <input type="file" class="form-control-file" id="file" name="file" >
                  <small><?php echo $this->lang->line('xin_employee_imp_allowed_size');?></small>
                </fieldset>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
            <div class="form-actions box-footer"  style="text-align: left !important;">
                 <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class_impor(), 'content' => '<i class="fa fa fa-check-square-o"></i> Proses Impor')); ?> 
             </div>
           </div>
          </div>

          <?php echo form_close(); ?> 
        </div>

        

      </div>
    </div>

    <div class="col-md-12 current-tab animated fadeInRight" id="import_gram">
      <div class="box">
        
        <div class="row <?php echo $get_animate;?>">
            <div class="col-md-12">
              
                <div class="box-header with-border">
                  <h3 class="box-title"> Menampilkan : Hasil Proses Impor Produktifitas Harian dari Aplikasi XAI </h3>
                </div>
                
                <div class="box-body">
                  <div class="row">
                      <div class="col-md-12">                   
                        <div class="row">                          
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="first_name"><?php echo $this->lang->line('xin_company_select'); ?></label>
                              <select class="form-control" name="company_id" id="aj_company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_company'); ?>"onchange="get_workstations()">
                                <option value=""></option>
                                <?php foreach ($all_companies as $company) { ?>
                                  <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <span id="workstation_ajax1">

                              <div class="form-group" id="workstation_ajax">
                                <label for="name"><?php echo $this->lang->line('xin_workstation_select'); ?></label>
                                <select disabled="disabled" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation'); ?>" name="workstation_id">
                                  <option value=""></option>
                                </select>
                              </div>
                            </span>
                          </div>

                          <!-- <div class="col-md-2">
                            <div class="form-group">
                              <label for="first_name"> Perusahaan  </label>
                                <select class="form-control" name="company_id" id="aj_company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_company'); ?>">
                                  <option value=""></option>
                                  <?php foreach ($all_companies as $company) { ?>
                                    <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?></option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                              <label for="first_name"> Workstation  </label>
                                <select class="form-control" name="workstation_id" id="workstation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation'); ?>">
                                  <option value=""></option>
                                  <?php foreach ($get_all_workstation as $workstation) { ?>
                                    <option value="<?php echo $workstation->workstation_id ?>"><?php echo $workstation->workstation_name ?></option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div> -->
                          <div class="col-md-2">
                            <div class="form-group">
                              <label for="first_name"> Tanggal  </label>
                              <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-01');?>">
                            </div>
                          </div>
                         
                          <div class="col-md-6">
                            <div class="form-group" style="float: left;margin-top: 22px;">
                              <div class="form-actions">
                                <button type="button" class="btn btn-primary" onclick="searchDataGramasi()">
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
    </div>

    <div class="col-md-12 current-tab animated fadeInRight" id="import_gram">
      <!-- <div class="box"> -->
        
        <div class="box <?php echo $get_animate;?>"  >
          <div class="box-header with-border" st>
            <h3 class="box-title">
              <span class="info_report_gaji_borongan" ></span>
              <div class="mb-3"></div>
              <span class="info_report_gaji_borongan_desc" ></span> 
            </h3>   
            
           <div class="box-tools pull-right" id ="myBtn" style="display:none;"> 
              <button type="button" class="btn btn-danger" onclick="hapusDataGramasi()" title="Hapus Produktifitas"> 
                <i class="fa fa-times"></i> Hapus
              </button>

              <!-- <button type="button" class="btn btn-warning" onclick="simpanDataGramasi()" title="Simpan Produktifitas"> 
                <i class="fa fa-save"></i> Simpan
              </button> -->
            </div>           

          </div>
          <div class="box-body" id ="myDIV" style="display:none;">
            <div class="box-datatable table-responsive">
               <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="100%">
                  <thead>
                    <tr>
                      <th width="60px"  style="text-align: center !important;"><center>No</center></th>             
                      <th width="120px" style="text-align: center !important;"><center>Tanggal</center></th>
                      <th width="100px" style="text-align: center !important;"><center> No. Job</center></th>   
                      <th width="200px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employees_id');?></center></th>
                      <th width="600px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_employee_name');?></center></th>
                      <th width="250px"><center> Posisi </center></th>
                      <th width="400px" style="text-align: center !important;"><center> Nm Brg </center></th>                                  
                      <th width="120px" style="text-align: center !important;"><center> Gram </center></th>
                      <th width="120px" style="text-align: center !important;"><center> Insentif </center></th>
                      <th width="75px" style="text-align: center !important;"><center> Status<br>HRIS </center></th>
                      <th width="75px" style="text-align: center !important;"><center> Status<br>Simpan  </center></th>
                      <th width="400px" style="text-align: center !important;"><center> Informasi  </center></th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th colspan="7" style="text-align: right !important;"> Total </th>
                      <th width="120px" style="text-align: center !important;"><center> <span class="info_report_gaji_borongan_total" ></span> </center></th>
                      <th width="75px" style="text-align: center !important;"><center>  </center></th>
                      <th width="75px" style="text-align: center !important;"><center>  </center></th>
                      <th width="400px" style="text-align: center !important;"><center>  </center></th>
                    </tr>
                  </tfoot>
                </table>
            </div>
          </div>
        </div>

      <!-- </div> -->
    </div>
      
   
  </div>
</section>

<script type="text/javascript">  
    function get_workstations() {
      var company_id  = jQuery('#aj_company_id').val();
      // alert(company_id)
      $.ajax({
        type : "GET",
        url  : '<?php echo base_url();?>admin/import/get_workstations/'+company_id,
        data : { 
          
          // company_id  : company_id,
        },
        // dataType : "json",
        success:function(data){   
                // console.log(data);                     
                  $("#workstation_ajax1").html(data);
                    
                }
        });
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
      var x = document.getElementById("myBtn");
      if (x.style.display === "none") {
        x.style.display = "block";
      } 
    } 

    

    function searchDataGramasi() 
    {  
        var start_date  = jQuery('#start_date').val();
        var company_id  = jQuery('#aj_company_id').val();
        var workstation_id  = jQuery('#workstation_id').val();
       
        if (start_date == '' )
        {
            alert("Tanggal Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        if (company_id == '' )
        {
            alert("Perusahaan Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        if (workstation_id == '' )
        {
            alert("Workstation Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        
        else 
        { 
         var s_date = $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
        
         $periode = s_date;
  
          $('#p_month').html($periode); 

            tampilkan_tabel();

           tampilkan_button();

          $(".info_report_gaji_borongan").html('<i class="fa fa-info"></i> Silahkan Tunggu ...');
          $(".info_report_gaji_borongan_desc").html('');
          $(".info_report_gaji_borongan_total").html('');

            // $('#p_month').html(month_year);  
            var xin_table_borongan = $('#xin_table_borongan').DataTable({

               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/import/gaji_borongan_gramasi_list/',
                    data: {                 
                        
                        start_date  : start_date,
                        company_id  : company_id,
                        workstation_id:workstation_id
                    }
                },  
                                    
                "columns": [
                   
                    {"name": "kolom_1",   "className": "text-center"},
                    {"name": "kolom_2",   "className": "text-center"},
                    {"name": "kolom_3",   "className": "text-center"},
                    {"name": "kolom_4",   "className": "text-center"},                    
                    {"name": "kolom_5",   "className": "text-left"},
                    {"name": "kolom_6",   "className": "text-left"},
                    {"name": "kolom_7",   "className": "text-center"},                    
                    {"name": "kolom_8",   "className": "text-center"},                    
                    {"name": "kolom_9",   "className": "text-center"},  
                    {"name": "kolom_10",  "className": "text-center"},                                                 
                    {"name": "kolom_11",  "className": "text-left"},                                                
                    {"name": "kolom_12",  "className": "text-left"}                                                  
                   
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
                url  : '<?php echo base_url();?>admin/import/gaji_borongan_gramasi_list_jumlah/',
                data : { 
                  
                    start_date : start_date,                     
                    company_id  : company_id,
                    workstation_id:workstation_id
                },
                dataType : "json",
                success:function(data){                        
                    
                    for(var i=0; i<data.val.length;i++){

                       $(".info_report_gaji_borongan").html('<b>INFORMASI</b> : Berikut ini Produktifitas Harian pada Tanggal : <span class="blink blink_four merah"><b>'+ data.val[i].tanggal +'</b></span>, dengan Jumlah Produktifitas sebanyak : <span class="blink blink_four merah"><b>'+ data.val[i].jumlah +'</b></span> Job, Total : <span class="blink blink_four merah"><b>'+ data.val[i].jumlah_gram +'</b></span> Gram (<span class="blink blink_four merah"><b>'+ data.val[i].jumlah_kg +'</b></span> Kg) .');

                       $(".info_report_gaji_borongan_desc").html('<i class="fa fa-warning"></i> Silahkan diperiksa. Jika sudah benar, silahkan klik tombol <b>Simpan</b> guna melakukan proses selanjutnya. Jika belum benar, silahkan lakukan <b>Proses Impor</b> kembali.');

                       $(".info_report_gaji_borongan_total").html('<b>'+ data.val[i].jumlah_gram +'</b> ');


                    }
                }
            });
        }
    }

    function simpanDataGramasi() 
    {  
        var start_date  = jQuery('#start_date').val();
       
        if (start_date == '' )
        {
            alert("Tanggal Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        if (company_id == '' )
        {
            alert("Perusahaan Belum Diisi !");
            $("#company_id").focus();  
            
        } 
        
        else 
        { 
         var s_date = $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
        
         $periode = s_date;
  
          $('#p_month').html($periode); 

            tampilkan_tabel();

            

           $(".info_report_gaji_borongan_desc").html('<b>INFORMASI SIMPAN</b> <span class="blink blink_two kuning">Proses Simpan Produktifitas Harian pada Tanggal : '+ $periode +' Berhasil Dilakukan.</span>');

            // $('#p_month').html(month_year);  
            var xin_table_borongan = $('#xin_table_borongan').DataTable({

               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/import/gaji_borongan_gramasi_simpan_list/',
                    data: {                 
                        
                        start_date  : start_date
                    }
                },  
                                    
                "columns": [
                   
                    {"name": "kolom_1",   "className": "text-center"},
                    {"name": "kolom_2",   "className": "text-center"},
                    {"name": "kolom_3",   "className": "text-center"},
                    {"name": "kolom_4",   "className": "text-center"},                    
                    {"name": "kolom_5",   "className": "text-left"},
                    {"name": "kolom_6",   "className": "text-left"},
                    {"name": "kolom_7",   "className": "text-center"},                    
                    {"name": "kolom_8",   "className": "text-center"},                    
                    {"name": "kolom_9",   "className": "text-center"},  
                    {"name": "kolom_10",  "className": "text-center"},                                                 
                    {"name": "kolom_11",  "className": "text-left"}                                                  
                   
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

    function hapusDataGramasi() 
    {  
        var start_date  = jQuery('#start_date').val();
        var company_id  = jQuery('#aj_company_id').val();
        var workstation_id  = jQuery('#workstation_id').val();
       
        if (start_date == '' )
        {
            alert("Tanggal Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        if (company_id == '' )
        {
            alert("Perusahaan Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        if (workstation_id == '' )
        {
            alert("Workstation Belum Diisi !");
            $("#start_date").focus();  
            
        } 
        else 
        { 
         var s_date = $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
        
         $periode = s_date;
  
          $('#p_month').html($periode); 

            tampilkan_tabel();

            

           $(".info_report_gaji_borongan_desc").html('<b>INFORMASI HAPUS</b> <span class="blink blink_two kuning">Proses Hapus Produktifitas Harian pada Tanggal : '+ $periode +' Berhasil Dilakukan.</span>');

            // $('#p_month').html(month_year);  
            var xin_table_borongan = $('#xin_table_borongan').DataTable({

               
               "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/import/gaji_borongan_gramasi_hapus_list/',
                    data: {                 
                        
                        start_date  : start_date,
                        company_id:company_id,
                        workstation_id:workstation_id
                    }
                },  
                                    
                "columns": [
                   
                    {"name": "kolom_1",   "className": "text-center"},
                    {"name": "kolom_2",   "className": "text-center"},
                    {"name": "kolom_3",   "className": "text-center"},
                    {"name": "kolom_4",   "className": "text-center"},                    
                    {"name": "kolom_5",   "className": "text-left"},
                    {"name": "kolom_6",   "className": "text-left"},
                    {"name": "kolom_7",   "className": "text-center"},                    
                    {"name": "kolom_8",   "className": "text-center"},                    
                    {"name": "kolom_9",   "className": "text-center"},  
                    {"name": "kolom_10",  "className": "text-center"},                                                 
                    {"name": "kolom_11",  "className": "text-left"}                                                  
                   
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

    


</script>