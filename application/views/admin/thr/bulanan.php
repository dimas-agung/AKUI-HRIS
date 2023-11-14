<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $system             = $this->Core_model->read_setting_info(1);?>

<?php $tahun_thr          = $this->input->post('tahun_thr'); ?>
<?php $tanggal_thr        = $this->input->post('tanggal_thr'); ?>

<?php
 if(!isset($tahun_thr)){   

    $skrg     = date('Y');

    $xin_tahun   = $this->Timesheet_model->get_xin_employees_tahun($skrg);
    $tahun_thr    = $xin_tahun[0]->tahun;    
    $tahun       = $xin_tahun[0]->tahun;

} 
?>

<?php $attributes = array('name' => 'bulk_payment', 'id' => 'bulk_payment', 'class' => 'm-b-1 add form-hrm');?>
<?php $hidden     = array('user_id' => $session['user_id']);?>
<?php echo form_open('admin/thr/add_pay_to_all_bulanan', $attributes, $hidden);?>

<div class="row <?php echo $get_animate;?>">
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Proses THR Bulanan </h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                    <select class="form-control" name="company_id" id="aj_companyx" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                      <?php foreach($all_companies as $company) {?>
                        <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                        <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="tahun$"> Tahun THR </label>
                    <select class="form-control input-sm" name="tahun_thr" id="tahun_thr" data-plugin="select_hrm" data-placeholder="Pilih Tahun" required>                    
                      <?php foreach($all_tahun_thr as $tahun_thr) {?>                        
                        <option value="<?php echo $tahun_thr->tahun;?>" <?php if($tahun_thr->tahun==$tahun_thr): ?> selected="selected" <?php endif; ?>>
                           <?php echo $tahun_thr->tahun?>                             
                        </option>              
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

                <div class="col-md-5">
                  <div class="form-group" style="float: left; margin-top: 22px;">
                    <div class="form-actions">
                      <button type="button" class="btn btn-warning"  onclick="searchDataTHR()" title="Proses THR"> 
                        <i class="fa fa-money"></i> 
                        Proses THR Bulanan
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

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    
    <h3 class="box-title text-uppercase text-bold"> 
      PERINCIAN THR BULANAN - <span class="text-danger" >TAHUN : </span>
       <span class="text-danger" id="p_month"><?php echo  $tahun  ;?></span>
    </h3> 

    <h5>
       <i class="fa fa-warning"></i>  Silahkan klik tombol "<span class="blink blink_two kuning">Proses THR Bulanan</span>" 
       Terlebih dahulu sebagai Draft THR Bulanan, Jika sudah Benar, silahkan Klik Tombol "<span class="blink blink_two hijau">Simpan THR Bulanan</span>" 
       guna Proses Kirim ke Bagian Finance.     
    </h5>
    
    <div class="box-tools pull-right" id ="myBtn" style="display:none;"> 
        <?php if(in_array('10112',$role_resources_ids)) { ?>
            <button type="submit" class="btn  btn-primary save"  title="Simpan THR"  > 
              <i class="fa fa-save"></i> Simpan THR Bulanan
            </button>
        <?php } ?>
    </div>

  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
     
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_thr" width="180%">
        <thead>
          <tr>
            <th width="200px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_action');?></center></th>
            <th width="80px"  style="text-align: center !important;"><center>No</center></th>
            <th width="100px" style="text-align: center !important;"><center>Status<br>THR</center></th>
            <th width="150px" style="text-align: center !important;"><center>Tahun<br>THR</center></th>
            <th width="150px" style="text-align: center !important;"><center>Batas<br>THR</center></th>
            <th width="250px" ><center><?php echo $this->lang->line('xin_employees_id');?></center></th>
            <th width="450px" ><center><?php echo $this->lang->line('xin_employee_name');?></center></th>
            <th width="350px" ><center><?php echo $this->lang->line('left_department');?></center></th> 
            <th width="520px" ><center><?php echo $this->lang->line('xin_employee_designation_txt');?></center></th>            
            <th width="220px" ><center>Tanggal<br>Mulai Kerja </center></th>            
            <th width="120px" ><center>Masa<br>Kerja</center></th>
            <th width="100px" ><center><?php echo $this->lang->line('xin_employee_status');?></center></th>
            <th width="100px" ><center><?php echo $this->lang->line('xin_employee_contrack');?></center></th>
            <th width="100px" ><center><?php echo $this->lang->line('xin_employee_grade');?></center></th>
           
            <th width="100px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_salary');?></center></th>
            <th width="100px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_salary_allowance_jabatan');?></center></th>
            <th width="100px" style="background-color: #4e7ccf;color: #fff;"><center>Total T1&T2</center></th>
            <th width="120px" style="background-color: #2b8a38;color: #fff;"><center> Total THR </center></th>  

            <th width="120px" style="background-color: #cfbe4e;color: #fff;"><center> No. Rekening </center></th>
            <th width="120px" style="background-color: #cfbe4e;color: #fff;"><center> Bank Transfer </center></th>
            <th width="120px" style="background-color: #cfbe4e;color: #fff;"><center> Email </center></th>       
          </tr>         
        </thead>
      </table>
    </div>
  </div>
</div>

<?php echo form_close(); ?> 

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

  function searchDataTHR() 
  {
      
      var company_id  = jQuery('#aj_companyx').val();
      var tahun_thr  = jQuery('#tahun_thr').val();
      var tanggal_thr  = jQuery('#tanggal_thr').val();
  
      if (company_id == '' )
      {
          alert("Nama Perusahaan Belum Diisi !");
          $("#company").focus();

      } 
      else if (tahun_thr == '' )
      {
          alert("Tahun THR Belum Diisi !");
           $("#tahun_thr").focus();  
          
      } 
       else if (tanggal_thr == '' )
      {
          alert("Tanggal Batas THR Belum Diisi !");
           $("#tanggal_thr").focus();  
          
      } 
      else 
      {   
          toastr.success('Proses THR Bulanan Berlangsung');
          
          $('#p_month').html(tahun_thr+', Tanggal Batas THR : '+tanggal_thr);     
        
           var xin_table3   = $('#xin_table_thr').dataTable({
                  
            "bDestroy"        : true,
            "bSort"           : false,
            "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
            autoWidth         : true,  
            "fixedColumns"    : true,
            "fixedColumns"    : {
                leftColumns   : 7
            },    
            "ajax": {
              url : site_url+"thr/thr_list_bulanan/?company_id="+company_id+"&tahun_thr="+tahun_thr+"&tanggal_thr="+tanggal_thr,
              type : 'GET'
            },
            "columns": [
                  {"name": "kolom_1",  "className": "text-center","width": "5%"},
                  {"name": "kolom_2",  "className": "text-center"},
                  {"name": "kolom_3",  "className": "text-center"},
                  {"name": "kolom_4",  "className": "text-center"},
                  {"name": "kolom_5",  "className": "text-center"},
                  {"name": "kolom_6",  "className": "text-center"},
                  {"name": "kolom_7",  "className": "text-left"},
                  {"name": "kolom_8",  "className": "text-left"},
                  {"name": "kolom_9",  "className": "text-left"},
                  {"name": "kolom_10",  "className": "text-center"},
                  {"name": "kolom_11", "className": "text-center"},
                  {"name": "kolom_12", "className": "text-center"},
                  {"name": "kolom_13", "className": "text-center"},
                  {"name": "kolom_14", "className": "text-center"},
                  {"name": "kolom_15", "className": "text-right"},    
                  {"name": "kolom_16", "className": "text-right"},
                  {"name": "kolom_17", "className": "text-right"},
                  {"name": "kolom_18", "className": "text-right"},        
                  {"name": "kolom_19", "className": "text-center"},
                  {"name": "kolom_20", "className": "text-center"},
                  {"name": "kolom_21", "className": "text-left"}     
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
              dom: 'lBfrtip',
            "buttons": ['excel'], // colvis > if needed
            
            "fnDrawCallback": function(settings){
              $('[data-toggle="tooltip"]').tooltip();          
            },
            
            "rowCallback": function(row, data, index) { 
                      
              $(row).find('td:eq(14)').css('background-color', '#eef7fa');
              $(row).find('td:eq(14)').css('color', 'black');
              $(row).find('td:eq(15)').css('background-color', '#eef7fa');
              $(row).find('td:eq(15)').css('color', 'black');       
              $(row).find('td:eq(16)').css('background-color', '#eef7fa');
              $(row).find('td:eq(16)').css('color', 'black');
              $(row).find('td:eq(17)').css('background-color', '#eef7fa');
              $(row).find('td:eq(17)').css('color', 'black');              
              $(row).find('td:eq(18)').css('background-color', '#faf9ee');
              $(row).find('td:eq(18)').css('color', 'black'); 
              $(row).find('td:eq(19)').css('background-color', '#faf9ee');
              $(row).find('td:eq(19)').css('color', 'black'); 
              $(row).find('td:eq(20)').css('background-color', '#faf9ee');
              $(row).find('td:eq(20)').css('color', 'black');
           
            }
          });
        
         
          tampilkan_tabel();
          tampilkan_tombol();
         
      }
  }

</script>