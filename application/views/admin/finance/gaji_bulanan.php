<?php
/* Payment History view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>


<?php $salary_month         = $this->input->post('salary_month'); ?>

<?php
 if(!isset($salary_month)){
    $skrg     = date('Y-m-d');
    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $salary_month  = $xin_bulan[0]->month_payroll;
    $bulan       = $xin_bulan[0]->month_payroll;
 }

?>

<div class="row <?php echo $get_animate;?>">    
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Tampilkan Gaji Karyawan Bulanan </h3>
        </div>
        <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                  <?php $attributes = array('name'    => 'payroll_report', 'id' => 'ihr_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
                  <?php $hidden     = array('user_id' => $session['user_id']);?>
                  <?php echo form_open('admin/finance/gaji_bulanan_list', $attributes, $hidden);?>
                  <?php
                			$data = array(
                			  'type'        => 'hidden',
                			  'name'        => 'date_format',
                			  'id'          => 'date_format',
                			  'value'       => $this->Core_model->set_date_format(date('Y-m-d')),
                			  'class'       => 'form-control',
                			);
          			     echo form_input($data);
          			  ?>
                  <div class="box-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="company"><?php echo $this->lang->line('left_company');?></label>
                            <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Pilih Perusahaan">
                             
                              <?php foreach($get_all_companies as $company) {?>
                              <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        
                        <!--<div class="col-md-3" id="designation_ajax">-->
                        <div class="col-md-2">
                           <label for="first_name"> Bulan Gaji </label>
                            <select class="form-control input-sm" name="salary_month" id="salary_month" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_month');?>" required>                    
                              <?php foreach($all_bulan_gaji as $bulan_gaji) {?>                        
                                <option value="<?php echo $bulan_gaji->month_payroll;?>" <?php if($bulan_gaji->month_payroll==$salary_month): ?> selected="selected" <?php endif; ?>>
                                   <?php echo $bulan_gaji->desc?>                             
                                </option>              
                              <?php } ?>
                            </select>              
                        </div>
                        <div class="col-md-2">
                          <div class="form-group" style="margin-top: 25px;" >
                           
                            <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-filter"></i> '.$this->lang->line('xin_show'))); ?> 
                          </div>
                        </div>
                      </div> 
                  </div>               
                  <?php echo form_close(); ?> 
              </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="box <?php echo $get_animate;?>" >
  <div class="box-header with-border">
    <h3 class="box-title"><span class="finance_gaji_bulanan"></span> </h3>   
    <div class="box-tools pull-right">      
       <!--  <button class="btn btn-xs btn-primary" id="btn-export-xls" data-toggle="tooltip" onclick="exportDataTHRXls()" title="Export ke Excel Xls" data-original-title="Export Xls">                                        
              <i class="fa fa-cloud-upload"></i> &nbsp;Export to Excel (*.csv)
          </button>   -->    
    </div>   
  </div>
  <div class="box-body" id ="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th width="50px"><center>No.</center></th>
            <th width="120px"><center> Bulan Gaji </center></th>
            <th width="300px"><center> Nama Karyawan </center></th>
            <th width="250px"><center> Perusahaan </center></th>
            <th width="300px"><center> Departemen </center></th>
            <th width="350px"><center> Posisi </center></th>
            <th width="120px"><center> Total Gaji</center></th>
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
