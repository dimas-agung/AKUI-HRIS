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
<?php $bulk_form_url      = 'admin/thr/add_pay_to_all_bulanan'; ?>
<?php $is_half_col        = '12'; ?>

<div class="row <?php echo $get_animate;?>">
    
    <div class="col-md-<?php echo $is_half_col;?>">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> <?php echo $this->lang->line('xin_lihat_employee_payroll');?> </h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form action="<?php echo base_url() ?>admin/thr/export"  id="formReportExport" class="m-b-1 add form-hrm" method="post" target="_blank">                    
           
          <!--    <?php $attributes = array('name' => 'set_salary_details', 'id' => 'set_salary_details', 'class' => 'm-b-1 add form-hrm');?>
              <?php $hidden = array('user_id' => $session['user_id']);?>
              <?php echo form_open('admin/thr/set_salary_details', $attributes, $hidden);?>  -->
              
              <div class="row">

                <!-- <?php if($user_info[0]->user_role_id==1 || in_array('314',$role_resources_ids)){ ?> -->
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
               <!--  <div class="col-md-3">
                  <div class="form-group" id="employee_ajax">
                    <label for="department"><?php echo $this->lang->line('dashboard_single_employee');?></label>
                    <select id="employee_id" name="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                      <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                    </select>
                  </div>
                </div> -->
                <!-- <?php } else {?>
                <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $session['user_id'];?>" />
                <?php } ?> -->
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="month_year"><?php echo $this->lang->line('xin_select_month_payroll');?></label>
                    <input class="form-control month_year" placeholder="<?php echo $this->lang->line('xin_select_month_payroll');?>" readonly id="month_year" name="month_year" type="text" value="<?php echo date('Y-m');?>">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group" style="float: left;margin-top: 25px;">
                    <div class="form-actions">
                      <button type="button" class="btn btn-sm btn-flat btn-primary" onclick="searchDataAttendance()">
                        <i class="fa fa-check-square-o"></i> Cari Gaji
                      </button>
                    </div>
                  </div>
                </div>

              </div>
              </form>
              <!-- <?php echo form_close(); ?>  -->
               </div>
          </div>
        </div>
      </div>
    </div> 

   

</div>

<div class="box <?php echo $get_animate;?>" id ="myButton" style="display:none;">
  <div class="box-header with-border">
    <h3 class="box-title"> 
      Lihat Gaji Bulanan, Bulan : 
      <span class="text-danger" id="p_month"><?php echo date('F Y', strtotime($month_year));?></span> 
    </h3>    
    <?php if(in_array('37',$role_resources_ids)) { ?>
      <div class="box-tools pull-right"> 
      
         <button class="btn btn-xs btn-primary" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataAttendanceXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
            <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.xls)
        </button>
     
      </div>
    <?php } ?>   
  </div>
  <div class="box-body" >
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="280%">
        <thead>
          <tr>
            <th width="30px" rowspan="2" style="text-align: center !important;"><center>No.</center></th>
            <th width="100px" rowspan="2" style=""><center>Status Payroll</center></th>
            <th width="170px" rowspan="2" ><center> NIK Karyawan </center></th>
            <th width="450px" rowspan="2" ><center> Nama Karyawan </center></th>
            <th width="400px" rowspan="2" ><center> Departemen </center></th> 
            <th width="520px" rowspan="2" ><center> Posisi </center></th>   

            <th width="120px" rowspan="2" ><center> Tanggal<br>Mulai Kerja </center></th>            
            <th width="120px" rowspan="2" ><center> Masa<br>Kerja</center></th>
            <th width="100px" rowspan="2" ><center> Status<br>Karyawan</center></th>
            <th width="100px" rowspan="2" ><center> Status<br>Kontrak</center></th>
            <th width="100px" rowspan="2" ><center> Status<br>Grade</center></0h>

            <th width="120px" colspan="7" style="background-color: #4e7ccf;color: #fff;"><?php echo $this->lang->line('xin_penambah');?></th>
            <th width="120px" colspan="6" style="background-color: #cd4ecf;color: #fff;"><?php echo $this->lang->line('xin_pengurang');?></th>
            <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_total_salary');?></center></th>
            
            <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;"><center><?php echo $this->lang->line('dashboard_xin_status_rekening');?></center></th>          
          </tr>
          <tr>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_salary');?></center></th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_overtime');?></center></th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_salary_allowance_jabatan');?></center></th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_salary_allowance_produktifitas');?></center></th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_salary_allowance_transport');?></center></th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_salary_allowance_komunikasi');?></center></th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_comission');?></center></th>

            <th width="170px" style="background-color: #cd4ecf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_tax');?></center></th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_loan');?></center></th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_bpjs_kes');?></center></th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_bpjs_tk');?></center></th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_absen_jumlah');?></center></th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;"><center><?php echo $this->lang->line('xin_payroll_absen');?></center></th>
            
            
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

     function tampilkan_button() {
              var x = document.getElementById("myButton");
              if (x.style.display === "none") {
                  x.style.display = "block";
              } 
            }

    function searchDataAttendance() {

                
        var month_year  = jQuery('#month_year').val();
        var company_id  = jQuery('#aj_company').val();
    
        if (company_id == '' )
        {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } 
        else if (month_year == '' )
        {
            alert("Bulan Gaji Belum Diisi !");
             $("#month_year").focus();  
            
        } 
        else 
        {          
           tampilkan_button();

            $('#p_month').html(month_year);  
            var xin_table_bulanan = $('#xin_table_bulanan').DataTable({

               
                "bDestroy"        : true,
               "bSort"           : false,
               "aLengthMenu"     : [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],      
               autoWidth         : true, 

                ajax: {
                    url: '<?php echo base_url(); ?>admin/thr/lihat_payslip_list_bulanan/',
                    data: {                 
                        company      : company_id, 
                        month_year   : month_year
                        
                    }
                },  
                                    
                "columns": [
                    {"name": "no", "className": "text-left"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no"},
                    {"name": "no", "className": "text-left"},
                    {"name": "no", "className": "text-left"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no", "className": "text-center"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    {"name": "no", "className": "text-right"},
                    
                    {"name": "no", "className": "text-center"},
                ],  
                    
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records found",
                    "infoFiltered": "(filtered1 from _MAX_ total records)",
                    "lengthMenu": "Show : _MENU_",
                    "search": "Search : ",
                    "sProcessing":"Pencarian Gaji ...",
                    "zeroRecords": "No matching records found",
                    "paginate": {
                        "previous":"Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"                        
                    }
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

    function exportDataAttendanceXls()  
    {
                
       var month_year  = jQuery('#month_year').val();
       var company_id  = jQuery('#aj_company').val();
    
        if (company_id == '' ){
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } else if (month_year == '' ){
            alert("Bulan Gaji Belum Diisi !");
             $("#month_year").focus();        
       
        } else {

            $('#formReportExport').submit();
        }
    }

</script>
