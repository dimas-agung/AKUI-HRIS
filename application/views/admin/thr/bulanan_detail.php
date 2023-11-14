<?php
/* Employee Details view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php //$default_currency = $this->Core_model->read_currency_con_info($system[0]->default_currency_id);?>
<?php
$eid = $this->uri->segment(4);
$eresult = $this->Employees_model->read_employee_information($eid);
?>

<?php $get_animate          = $this->Core_model->get_content_animate();?>

<?php $user_info            = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids   = $this->Core_model->user_role_resource(); ?>
<?php

$sql                        = 'SELECT * FROM xin_employees WHERE user_id = ?';
$binds                      = array($user_id);
$query                      = $this->db->query($sql, $binds);
$rw_password                = $query->result();

$full_name                  = $rw_password[0]->first_name." ".$rw_password[0]->last_name;

?>


<div class="row">
  <div class="col-md-12">

    <div class="nav-tabs-custom mb-4">
      
      <div class="content">                       
          
          <div class="box-header with-border">            
             <i class="fa fa-user"></i> <h3 class="box-title"> <b> <?php echo strtoupper($full_name); ?> </b></h3>
          </div>

          <br/>     
                    <div class="row no-gutters row-bordered row-border-light">
                      <div class="col-md-2 pt-0">
                        <div class="list-group list-group-flush account-settings-links"> 
                            

                            <div class="box">
                              <div class="box-header with-border" style="background-color: #e0f3e5;">
                                <h3 class="box-title"><i class="fa fa-building"></i> Informasi Rekening </h3>
                              </div>
                              <div class="list-group">
                                  <div class="list-group list-group-flush account-settings-links">                                     
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab active"        data-toggle="list" href="javascript:void(0);" data-profile="2" data-profile-block="bank-account"   aria-expanded="true" id="suser_profile_2">Bank Rekening</a> 
                                  </div>
                              </div>
                            </div>

                            <div class="box">
                              <div class="box-header with-border" style="background-color: #e0f3e5;">
                                <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_penambah');?> </h3>
                              </div>
                              <div class="list-group">
                                  <div class="list-group list-group-flush account-settings-links">                                    
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"        data-toggle="list" href="javascript:void(0);" data-profile="3" data-profile-block="gapok"           aria-expanded="true" id="suser_profile_3">Gaji Pokok</a>
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"        data-toggle="list" href="javascript:void(0);" data-profile="4" data-profile-block="set_allowances"  aria-expanded="true" id="suser_profile_4">Tunjangan</a> 
                                 </div>
                              </div>
                            </div>

                          </div>
                      </div>
                      
                      <div class="col-md-10">
                        <div class="tab-content active" style="margin-left: 20px; border: 1px solid #d3d2d2; padding: 10px">                          
                          
                          <!-- Bank -->
                          <div class="tab-pane salary-current-tab active" id="bank-account" >
                              
                              <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> Bank Rekening </h3>
                              </div>
                              <div class="box-body pb-2">
                                <?php $attributes = array('name' => 'bank_account_info', 'id' => 'bank_account_info', 'autocomplete' => 'off');?>
                                <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                                <?php echo form_open('admin/thr/bank_account_info', $attributes, $hidden);?>
                                <?php
                                  $data_usr4 = array(
                                    'type'  => 'hidden',
                                    'name'  => 'user_id',
                                    'id'  => 'user_id',
                                    'value' => $user_id,
                                 );
                                echo form_input($data_usr4);
                                ?>
                                <div class="row">                                  
                                  
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number');?><i class="hris-asterisk">*</i></label>
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number');?>" name="account_number" type="text" value="" id="account_number">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name');?><i class="hris-asterisk">*</i></label>
                                      <select name="bank_name" id="bank_name" class="form-control" data-plugin="select_hrm" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name');?>">
                                        <option value="" > -- Pilih Bank Transfer -- </option>
                                        <option value="Bank BNI"> 009 - Bank BNI </option>
                                        <option value="Bank BRI"> 002 - Bank BRI </option>
                                        <option value="Bank BCA"> 014 - Bank BCA </option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch');?></label>
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch');?>" name="bank_branch" type="text" value="" id="bank_branch">
                                    </div>
                                  </div>

                                </div>

                                <div class="row">  
                                  <div class="col-md-5">
                                    <div class="form-group">                                      
                                      <label>
                                        <input type="checkbox" class="minimal" value="1" id="is_primary" name="is_primary">
                                        <span>&nbsp;Rekening Utama</span> 
                                        </label>
                                    </div>
                                  </div>
                                </div>
                                                              
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                       <label for="amount"></label>
                                      <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                    </div>
                                  </div>
                                </div>
                                <?php echo form_close(); ?> 
                              </div>
                              <div class="box">
                                <div class="box-header with-border">
                                  <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Bank Rekening </h3>
                                </div>
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_bank_account" style="width:100%;">
                                      <thead>
                                        <tr>
                                          <th width="8%"><?php echo $this->lang->line('xin_action');?></th>                                         
                                          <th><?php echo $this->lang->line('xin_e_details_acc_number');?></th>
                                          <th width="20%"><?php echo $this->lang->line('xin_e_details_bank_name');?></th>                                         
                                          <th width="20%"><?php echo $this->lang->line('xin_e_details_bank_branch');?></th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                          </div>

                           <!-- Gaji Pokok -->
                          <div class="tab-pane salary-current-tab" id="gapok" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title">Gaji Pokok </h3>
                            </div>
                            
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_salary_gapok', 'id' => 'employee_update_salary_gapok', 'autocomplete' => 'off');?>
                              <?php $hidden     = array('user_id' => $user_id, 'u_basic_info' => 'UPDATE');?>
                              
                              <?php echo form_open('admin/thr/update_salary_gapok', $attributes, $hidden);?>
                              
                              <div class="bg-white">
                                
                                <div class="row">                                 
                                  <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="basic_salary"><?php echo $this->lang->line('xin_salary_title');?><i class="hris-asterisk">*</i></label>
                                      <input class="form-control basic_salary" placeholder="<?php echo $this->lang->line('xin_salary_title');?>" name="basic_salary" type="number" value="<?php echo $basic_salary;?>">
                                    </div>
                                  </div>
                                </div>
                               
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group" style="float: left;">
                                      <!-- <div class="form-actions box-footer">  -->
                                        <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> 
                                      <!-- </div> -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 

                            </div>
                          </div>

                          <!-- Tunjangan -->
                          <div class="tab-pane salary-current-tab" id="set_allowances"style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> <b>TUNJANGAN</b> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_allowance', 'id' => 'employee_update_allowance', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/thr/employee_allowance_option', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'id'  => 'user_id',
                                'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">

                                <div class="col-md-3"> 
                                
                                  <div class="form-group">
                                    <label for="commission_date" class="control-label"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="allowance_date" type="text" value="" id="allowance_date">
                                  </div>
                                
                                </div>

                                <div class="col-md-9">
                                  
                                    <div class="row">

                                      <div class="col-md-3"> 
                                        <div class="form-group">
                                          <label for="tnj_jabatan">Tnj Jabatan<i class="hris-asterisk">*</i></label>
                                          <input class="form-control " placeholder="Tnj Jabatan" name="tnj_jabatan" type="number" value="" id="tnj_jabatan">
                                        </div>
                                      </div>

                                      
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          <label for="tnj_jabatan">Tnj Produktifitas<i class="hris-asterisk">*</i></label>
                                          <input class="form-control" placeholder="Tnj Produktifitas" name="tnj_produktifitas" type="number" value="" id="tnj_produktifitas">
                                        </div>
                                      </div>

                                      <div class="col-md-3"> 
                                        <div class="form-group">
                                          <label for="tnj_jabatan" >Tnj Komunikasi<i class="hris-asterisk">*</i></label>
                                          <input class="form-control " placeholder="Tnj Komunikasi" name="tnj_komunikasi" type="number" value="" id="tnj_komunikasi">
                                        </div>
                                      </div>

                                      
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          <label for="tnj_jabatan">Tnj Transportasi<i class="hris-asterisk">*</i></label>
                                          <input class="form-control" placeholder="Tnj Transportasi" name="tnj_transportasi" type="number" value="" id="tnj_transportasi">
                                        </div>
                                      </div>

                                     
                                    </div>

                                </div>
                                
                              </div>
                              
                              <div class="row">                                      
                                  <div class="col-md-12">
                                    <div class="form-group simpan_atur">
                                      <div class="box-footer hris-salary-button"> 
                                        <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> 
                                      </div>
                                    </div>
                                  </div>
                              </div>

                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_set_allowances');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_allowances" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="170px"><center><?php echo $this->lang->line('xin_action');?></center></th> 
                                        <th width="120px"><center>Tanggal</center></th>                                       
                                        <th> <center>Tnj Jabatan</center></th>
                                        <th> <center>Tnj Produktifitas</center></th>
                                        <th> <center>Tnj Komunikasi</center></th>
                                        <th> <center>Tnj Transportasi</center></th>                                      
                                      </tr>
                                    </thead>
                                  </table>
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

