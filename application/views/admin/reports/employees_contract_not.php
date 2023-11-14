<?php

/* ===========================================================================
   LAPORAN : KARYAWAN AKTIF
   ===========================================================================
*/

?>
<?php $session     = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info   = $this->Core_model->read_user_info($session['user_id']);?>

<div class="row m-b-1 <?php echo $get_animate;?>">  
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_hr_report_filters');?> </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <?php $attributes = array('name'     => 'employees_contract_not', 'id' => 'employees_contract_not', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
            <?php $hidden     = array('euser_id' => $session['user_id']);?>
            <?php echo form_open('admin/reports/employees_contract_not', $attributes, $hidden);?>
            <?php
                $data = array(
                  'name'        => 'user_id',
                  'id'          => 'user_id',
                  'type'        => 'hidden',
                  'value'   	  => $session['user_id'],
                  'class'       => 'form-control input-sm',
                );
            
            echo form_input($data);
            ?>
           
            <div class="row">

              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                  <select class="form-control input-sm input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                    <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                    <?php foreach($all_companies as $company) {?>
                    <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            
              <div class="col-md-3">
                <div class="form-group" id="department_ajax">
                  <label for="department"><?php echo $this->lang->line('xin_employee_department');?></label>
                  <select disabled="disabled" class="form-control input-sm" name="department_id" id="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
                    <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                  </select>
                </div>
              </div>

              <div class="col-md-3" id="designation_ajax">
                <div class="form-group">
                  <label for="designation"><?php echo $this->lang->line('xin_designation');?></label>
                  <select disabled="disabled" class="form-control input-sm" id="designation_id" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
                    <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                  </select>
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <label for="submit">&nbsp;</label><br />
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_get');?> </button>
                </div>
              </div>
              
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12 <?php echo $get_animate;?>">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-info-circle"></i> Daftar Kontrak Belum Dibuat </h3>
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered " id="xin_emp_contract_not" width="150%">
            <thead>
              <tr>
                <th width="10px" text-align="center"><center>No.</center></th>
                <th width="120px"><center> Tanggal<br>Masuk</center></th>
                <th width="120px"><center> Kontrak<br>Mulai</center></th>
                <th width="120px"><center> Kontrak<br>Berakhir</center></th>

                <th width="120px"><center> NIP </center></th>                
                <th width="320px"><center> Nama Lengkap </center></th>               
                <th width="150px"><center> Departemen </center></th>
                <th width="150px"><center> Posisi </center></th>
                
                
                <th width="80px"><center> Status </center></th>
                <th width="200px"><center> No Kontrak Terakhir </center></th>  
              
              </tr>
            </thead>
          </table>
        </div>
      </div>
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
