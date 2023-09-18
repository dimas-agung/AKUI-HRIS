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
                                <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Gaji </h3>
                              </div>
                              <div class="list-group">
                                  <div class="list-group list-group-flush account-settings-links"> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab active" data-toggle="list" href="javascript:void(0);" data-profile="1" data-profile-block="jenis"          aria-expanded="true" id="suser_profile_1">Setting Gaji </a>
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"        data-toggle="list" href="javascript:void(0);" data-profile="2" data-profile-block="bank-account"   aria-expanded="true" id="suser_profile_2">Bank Rekening</a> 
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
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"        data-toggle="list" href="javascript:void(0);" data-profile="5" data-profile-block="commissions"     aria-expanded="true" id="suser_profile_5">Insentif</a> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"        data-toggle="list" href="javascript:void(0);" data-profile="6" data-profile-block="overtime"        aria-expanded="true" id="suser_profile_6">Lembur</a> 
                                  </div>
                              </div>
                            </div>

                            <div class="box">
                              <div class="box-header with-border" style="background-color: #e0f3e5;">
                                <h3 class="box-title"><i class="fa fa-minus-circle"></i> <?php echo $this->lang->line('xin_pengurang');?> </h3>
                              </div>
                              <div class="list-group">
                                  <div class="list-group list-group-flush account-settings-links"> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"      data-toggle="list" href="javascript:void(0);" data-profile="7" data-profile-block="other_payment"         aria-expanded="true" id="suser_profile_7">Pajak (PPh 21)</a>
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"      data-toggle="list" href="javascript:void(0);" data-profile="8" data-profile-block="statutory_deductions"  aria-expanded="true" id="suser_profile_8">BPJS KES & TK </a> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"      data-toggle="list" href="javascript:void(0);" data-profile="9" data-profile-block="loan_deductions"       aria-expanded="true" id="suser_profile_9">Pinjaman Karyawan</a>                     
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab"      data-toggle="list" href="javascript:void(0);" data-profile="10" data-profile-block="minus"                aria-expanded="true" id="suser_profile_10">Potongan Lain</a> 
                                  
                                  </div>
                              </div>
                            </div> 
                          
                          </div>
                      </div>
                      <div class="col-md-10">
                        <div class="tab-content active" style="margin-left: 20px; border: 1px solid #d3d2d2; padding: 10px">                          

                           <!-- Jenis Gaji -->
                          <div class="tab-pane salary-current-tab active" id="jenis">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title">Setting Gaji </h3>
                            </div>
                            
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_salary', 'id' => 'employee_update_salary', 'autocomplete' => 'off');?>
                              <?php $hidden     = array('user_id' => $user_id, 'u_basic_info' => 'UPDATE');?>
                              
                              <?php echo form_open('admin/payroll/update_salary_option', $attributes, $hidden);?>
                              
                              <div class="bg-white">

                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="wages_type"> Apa jenis gaji karyawan ini ? <i class="hris-asterisk">*</i></label>
                                      <select name="wages_type" id="wages_type" class="form-control" data-plugin="select_hrm">
                                        <option value="" > -- Pilih Jenis Gaji -- </option>
                                        <option value="1" <?php if($wages_type==1):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_full_tTime');?></option>
                                        <option value="2" <?php if($wages_type==2):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_part_tTime');?></option>
                                        <option value="3" <?php if($wages_type==3):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_free_lance');?></option>
                                      </select>
                                    </div>
                                  </div>                                                           
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="grade_type"> Apa jenis grade karyawan ini ? <i class="hris-asterisk">*</i></label>
                                      <select name="grade_type" id="grade_type" class="form-control" data-plugin="select_hrm">
                                         <option value="" > -- Pilih Grade Gaji -- </option>
                                         <option value="Grade 1"  <?php if($grade_type=='Grade 1'):?>  selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_1');?></option>
                                        <option value="Grade 2"  <?php if($grade_type=='Grade 2'):?>  selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_2');?></option>
                                        <option value="Grade 3"  <?php if($grade_type=='Grade 3'):?>  selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_3');?></option>
                                        <option value="Grade 4"  <?php if($grade_type=='Grade 4'):?>  selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_4');?></option>
                                        <option value="Grade 4A" <?php if($grade_type=='Grade 4A'):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_4A');?></option>
                                        <option value="Grade 4B" <?php if($grade_type=='Grade 4B'):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_4B');?></option>
                                        <option value="Grade 5"  <?php if($grade_type=='Grade 5'):?>  selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_5');?></option>
                                        <option value="Grade 5A" <?php if($grade_type=='Grade 5A'):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_5A');?></option>
                                        <option value="Grade 5B" <?php if($grade_type=='Grade 5B'):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_5B');?></option>
                                        <option value="Grade 6"  <?php if($grade_type=='Grade 6'):?>  selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_6');?></option>
                                        <option value="Grade 6A" <?php if($grade_type=='Grade 6A'):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_employee_type_grade_6A');?></option>
                                      </select>
                                    </div>
                                  </div>                                                           
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="payment_type"> Penggajian dibayarkan dengan metode apa ? <i class="hris-asterisk">*</i></label>
                                      <select name="payment_type" id="payment_type" class="form-control" data-plugin="select_hrm">
                                        <option value="" > -- Pilih Jenis Pembayaran Gaji -- </option>
                                        <option value="1" <?php if($payment_type==1):?> selected="selected"<?php endif;?>> Transfer Bank </option>
                                        <option value="2" <?php if($payment_type==2):?> selected="selected"<?php endif;?>> Tunai </option>
                                      </select>
                                    </div>
                                  </div>                                                           
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="flag"> Apakah faktor kehadiran ini diperhitungkan ? <i class="hris-asterisk">*</i></label>
                                      <select name="flag" id="flag" class="form-control" data-plugin="select_hrm">
                                        <option value="" > -- Pilih Faktor Absensi -- </option>
                                        <option value="0" <?php if($flag==0):?> selected="selected"<?php endif;?>> Iya, diperhitungkan </option>
                                        <option value="1" <?php if($flag==1):?> selected="selected"<?php endif;?>> Tidak, abaikan faktor absensi ini</option>
                                      </select>
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
                          
                          <!-- Bank -->
                          <div class="tab-pane salary-current-tab" id="bank-account" style="display:none;">
                              
                              <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> Bank Rekening </h3>
                              </div>
                              <div class="box-body pb-2">
                                <?php $attributes = array('name' => 'bank_account_info', 'id' => 'bank_account_info', 'autocomplete' => 'off');?>
                                <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                                <?php echo form_open('admin/payroll/bank_account_info', $attributes, $hidden);?>
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
                              
                              <?php echo form_open('admin/payroll/update_salary_gapok', $attributes, $hidden);?>
                              
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
                              <?php echo form_open('admin/payroll/employee_allowance_option', $attributes, $hidden);?>
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
                                    <input class="form-control date" placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="allowance_date" type="text" value="" id="allowance_date">
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

                          <!-- Insentif -->
                          <div class="tab-pane salary-current-tab" id="commissions" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> <b>INSENTIF</b> </h3>
                             
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_commissions', 'id' => 'employee_update_commissions', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/payroll/employee_commissions_option', $attributes, $hidden);?>
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
                                    <input class="form-control date" placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="date" type="text" value="" id="date">
                                  </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="" id="title">
                                  </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="" id="amount">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group simpan_atur">
                                    <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>
                              
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Insentif </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_commissions" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="170px"><center><?php echo $this->lang->line('xin_action');?></center></th>
                                        <th width="120px"><center><?php echo $this->lang->line('dashboard_xin_date');?></center></th>
                                        <th> <center><?php echo $this->lang->line('dashboard_xin_title');?></center></th>
                                        <th width="100px"> <center><?php echo $this->lang->line('xin_amount');?></center></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <!-- Pinjaman -->
                          <div class="tab-pane salary-current-tab" id="loan_deductions" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> <b>PINJAMAN KARYAWAN</b> </h3>
                              <p style="margin-top: 5px;"><b>PERHATIAN</b> : <span class="blink blink-one merah">Tanggal Mulai s/d Sampai merupakan awal dan akhir bulan gaji</span></p>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'add_loan_info', 'id' => 'add_loan_info', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/payroll/employee_loan_info', $attributes, $hidden);?>
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
                                 <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="month_year">Tanggal Pinjam <i class="hris-asterisk">*</i></label>
                                    <input class="form-control cont_date" placeholder="Tanggal Pinjam" name="loan_date" type="text">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="loan_options">Jenis Pinjaman <i class="hris-asterisk">*</i></label>
                                    <select name="loan_options" id="loan_options" class="form-control" data-plugin="select_hrm">
                                      <option value="1"><?php echo $this->lang->line('xin_loan_ssc_title');?></option>
                                      <option value="2"><?php echo $this->lang->line('xin_loan_hdmf_title');?></option>
                                      <option value="0"><?php echo $this->lang->line('xin_loan_other_sd_title');?></option>
                                    </select>
                                  </div>
                                </div>                                 
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="month_year">Pinjaman Untuk <i class="hris-asterisk">*</i></label>
                                      <input class="form-control" placeholder="Pinjaman Untuk" name="loan_deduction_title" type="text">
                                    </div>
                                  </div>
                                  <div class="col-md-2">
                                    <div class="form-group">
                                      <label for="edu_role">Besar Pinjaman<i class="hris-asterisk">*</i></label>
                                      <input class="form-control" placeholder="Besar Pinjaman" name="monthly_installment" type="number" id="m_monthly_installment">
                                    </div>
                                  </div>
                                <div class="col-md-2">
                                  <div class="form-group">
                                    <label for="month_year">Angsuran Mulai<i class="hris-asterisk">*</i></label>
                                    <input class="form-control cont_date" placeholder="Tanggal Mulai"name="start_date" type="text">
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="form-group">
                                    <label for="end_date">Sampai<i class="hris-asterisk">*</i></label>
                                    <input class="form-control cont_date" placeholder="Tanggal Sampai" name="end_date" type="text">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="description">Keterangan Pinjaman</label>
                                    <textarea class="form-control textarea" placeholder="Keterangan Pinjaman" name="reason" cols="30" rows="2" id="reason2"></textarea>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group box-footer">
                                    <div class="form-actions  pull-left"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Pinjaman Karyawan </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_deductions" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="150px"><center><?php echo $this->lang->line('xin_action');?></center></th>
                                         <th width="120px"><center>Tanggal Pinjaman</center></th>
                                          <th width="210px"><center>Durasi Pinjaman</center></th>
                                        <th><center>Keterangan Pinjaman</center></th>
                                        <th width="120px"><center>Pinjaman</center></th>
                                        <th width="120px"><center>Angusuran</center></th>
                                         <th width="150px"><center>Selama </center></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- BPJS -->
                          <div class="tab-pane salary-current-tab" id="statutory_deductions" style="display:none;">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> <b> BPJS KES & TK  </b> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'statutory_deductions_info', 'id' => 'statutory_deductions_info', 'autocomplete' => 'off');?>
                              <?php $hidden     = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/payroll/set_statutory_deductions', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'id'    => 'user_id',
                                'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="commission_date" class="control-label"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control cont_date"  placeholder="Tanggal" name="deduction_date" type="text" value="" id="deduction_date">
                                  </div>                                
                                </div>                               
                               
                                <div class="col-md-3">
                                    <div class="form-group">
                                      <label for="statutory_options"><?php echo $this->lang->line('xin_salary_sd_options');?><i class="hris-asterisk">*</i></label>
                                      <select name="statutory_options" id="statutory_options" class="form-control" data-plugin="select_hrm">
                                        <option value=""> -- Pilih BPJS -- </option>
                                        <option value="1"><?php echo $this->lang->line('xin_sd_ssc_title');?></option>
                                        <option value="2"><?php echo $this->lang->line('xin_sd_phic_title');?></option>
                                      </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title_no');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title_no');?>" name="title" type="text" value="" id="title">
                                  </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?>
                                    <?php if($system[0]->statutory_fixed!='yes'):?> (%) <?php endif;?><i class="hris-asterisk">*</i>
                                    </label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="" id="amount">
                                  </div>
                                </div>

                              </div>

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group box-footer">
                                    <div class="form-actions pull-left"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>                              
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> BPJS KES & TK  </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_statutory_deductions" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="170px"><center><?php echo $this->lang->line('xin_action');?></center></th> 
                                        <th width="120px"><center>Tanggal</center></th>    
                                        <th><center><?php echo $this->lang->line('xin_salary_sd_options');?></center></th>
                                        <th><center><?php echo $this->lang->line('dashboard_xin_title_no');?></center></th>
                                        <th><center><?php echo $this->lang->line('xin_amount');?> <?php if($system[0]->statutory_fixed!='yes'):?> (%) <?php endif;?></center></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>                            
                          </div>

                          <!-- Pajak -->
                          <div class="tab-pane salary-current-tab" id="other_payment" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"> <i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> <b>PAJAK (PPh 21)</b> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'other_payments_info', 'id' => 'other_payments_info', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/payroll/set_other_payments', $attributes, $hidden);?>
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
                                    <input class="form-control date" placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="payments_date" type="text" value="" id="payments_date">
                                  </div>                                
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="" id="title">
                                  </div>
                                </div>
                                
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="" id="amount">
                                  </div>
                                </div>

                                <div class="col-md-3">                                  
                                  <div class="form-group simpan_atur">                                      
                                      <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>                                  
                                </div>
                                
                              </div>
                             
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_set_other_payment');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_other_payments" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="170px"><center><?php echo $this->lang->line('xin_action');?></center></th> 
                                        <th width="120px"><center>Tanggal</center></th> 
                                        <th ><center><?php echo $this->lang->line('dashboard_xin_title');?></center></th>
                                        <th width="120px"><center><?php echo $this->lang->line('xin_amount');?></center></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Lembur -->
                          <div class="tab-pane salary-current-tab" id="overtime" style="display:none;">
                           
                            <div class="box-header with-border">
                              <h3 class="box-title"> <i class="fa fa-eye"></i> Lihat <b>LEMBUR</b> </h3>
                            </div>
                            
                             <div class="box" style="margin-top: 10px;">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('dashboard_overtime');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_overtime" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="20px"><center> Aksi.</center></th>
                                        <th width="110px"><center> Tanggal </center></th>
                                        <th width="200px"><center> Jam </center></th>
                                        <th width="250px"><center> Pemberi Lembur </center></th>                                      
                                        <th ><center> Keterangan Lembur </center></th>
                                        <th width="100px"><center> Lama </center></th>
                                        <th width="120px"><center> Biaya</center></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Potong -->
                          <div class="tab-pane salary-current-tab" id="minus" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_new');?> <b>PEMOTONGAN</b> </h3>
                             
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_minus', 'id' => 'employee_update_minus', 'autocomplete' => 'off');?>
                              <?php $hidden     = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/payroll/employee_minus_option_bulanan', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                 'type'  => 'hidden',
                                'name'  => 'user_id',
                                 'id'   => 'user_id',
                                'value' => $user_id,
                               );
                               echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                
                                <div class="col-md-3"> 
                                  <div class="form-group">
                                    <label for="commission_date" class="control-label"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control date" placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="minus_date" type="text" value="" id="minus_date">
                                  </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="minus_title" type="text" value="" id="minus_title">
                                  </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="minus_amount" type="number" value="" id="minus_amount">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group simpan_atur">
                                    <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>
                              
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Pemotongan </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_minus_bulanan" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th width="170px"><center><?php echo $this->lang->line('xin_action');?></center></th>
                                        <th width="120px"><center><?php echo $this->lang->line('dashboard_xin_date');?></center></th>
                                        <th> <center><?php echo $this->lang->line('dashboard_xin_title');?></center></th>
                                        <th width="100px"> <center><?php echo $this->lang->line('xin_amount');?></center></th>
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

