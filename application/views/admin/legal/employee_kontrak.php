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
<?php
$ar_sc = explode('- ',$system[0]->default_currency_symbol);
$sc_show = $ar_sc[1];
$leave_user = $this->Core_model->read_user_info($eid);
?>
<?php $get_animate          = $this->Core_model->get_content_animate();?>
<!-- <?php $leave_categories_ids = explode(',',$leave_categories);?>
<?php $view_companies_ids   = explode(',',$view_companies_id);?> -->
<?php $user_info            = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids   = $this->Core_model->user_role_resource(); ?>
<?php

$sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
$binds = array($user_id);
$query = $this->db->query($sql, $binds);
$rw_password = $query->result();
$password = $rw_password[0]->password;

$full_name = $rw_password[0]->first_name." ".$rw_password[0]->last_name;
// password_verify($old_password,$rw_password[0]->password);
// $options = array('cost' => 12);
?>


<div class="row">
  <div class="col-md-12">

    <div class="nav-tabs-custom mb-4">
      
      <div class="content">                       
          
          <div class="box-header with-border">            
          <i class="fa fa-user"></i> <h3 class="box-title"> <b> <?php echo strtoupper($full_name); ?> </b></h3>
          </div>
          <br/>       
          <div class="tab-content " style="border: 1px solid #d3d2d2; padding: 10px">                  
            
            <div class="tab-pane active current-tab <?php echo $get_animate;?>" id="contract">
              <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_contract');?> </h3>
              </div>

              <div class="box-body pb-2">
                <?php $attributes = array('name' => 'contract_info', 'id' => 'contract_info', 'autocomplete' => 'off');?>
                <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                <?php echo form_open('admin/employees/contract_info', $attributes, $hidden);?>
                <?php
                    $data_usr4 = array(
                    'type'  => 'hidden',
                    'name'  => 'user_id',
                     'id'    => 'user_id',
                    'value' => $user_id,
                   );
                  echo form_input($data_usr4);
                  ?>
                  
                <div class="col-md-6">
                                
                  <div class="form-group">
                    <label for="company_id" class="">Nama Perusahaan </label>
                    <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="Nama Perusahaan ">
                      <option value=""> -- Pilih Perusahaan -- </option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>" <?php if($company_id==$company->company_id):?> selected <?php endif;?>> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>

                   <div class="form-group">
                    <label for="title" class="">No Kontrak</label>
                    <input class="form-control" placeholder="No Kontrak" name="title" type="text" value="" id="title">
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="" for="from_date"> Tanggal Mulai </label>
                    <input type="text" class="form-control cont_date" name="from_date" placeholder=" Tanggal Mulai " readonly value="">
                  </div>                
                  
                   <div class="form-group">
                    <label for="contract_durasi_id" class=""> Lama kontrak</label>
                    <select class="form-control" name="contract_durasi_id" data-plugin="select_hrm" data-placeholder=" Lama kontrak">
                      <option value=""> Lama kontrak</option>
                      <?php foreach($all_contract_durasi as $contract_durasi) {?>
                      <option value="<?php echo $contract_durasi->contract_durasi_id;?>"> <?php echo $contract_durasi->name;?></option>
                      <?php } ?>
                    </select>
                  </div>

                </div>
  
                <div class="col-md-6">

                  <div class="form-group">
                    <label for="designation_id" class="">Posisi </label>
                    <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="Posisi Karyawan">
                      <option value="">Posisi Karyawan</option>
                      <?php foreach($all_designations as $designation) {?>
                      <?php if($designation_id==$designation->designation_id):?>
                      <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name?></option>
                      <?php endif;?>
                      <?php } ?>
                    </select>
                  </div>  

                  <div class="form-group">
                    <label for="contract_type_id" class="">Tipe</label>
                    <select class="form-control" name="contract_type_id" data-plugin="select_hrm" data-placeholder="Tipe">
                      <option value="">Tipe</option>
                      <?php foreach($all_contract_types as $contract_type) {?>
                      <option value="<?php echo $contract_type->contract_type_id;?>"> <?php echo $contract_type->name;?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="to_date">Tanggal Sampai</label>
                    <input type="text" class="form-control cont_date" name="to_date" placeholder="Tanggal Sampai" readonly value="">
                  </div>
                  <div class="form-group">
                    <label for="description">Keterangan Kontrak</label>
                    <textarea class="form-control" placeholder="Keterangan Kontrak" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"></textarea>
                    <span class="countdown"></span> </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                    </div>
                  </div>
                </div>
                <?php echo form_close(); ?> 
              </div>
              
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"> 
                    <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_contracts');?> :
                    <b> <?php echo strtoupper($full_name); ?> </b>
                  </h3>
                </div>
                <div class="box-body">
                  <div class="box-datatable table-responsive">
                    <table class="table table-striped table-bordered dataTable" id="xin_table_contract" style="width:100%;">
                      <thead>
                        <tr>
                          <th width="120px"><?php echo $this->lang->line('xin_action');?></center></th>
                          <th width="280px"><center>No Kontrak</center></th>
                          <th width="100px"><center> Start </center></th>
                          <th width="100px"><center> Reminder </center></th>
                          <th width="100px"><center> End </center></th>
                          <th width="90px"><center> Lama  </center></th>
                          <th ><center> Lokasi & Posisi </center></th>
                         
                          <th width="60px"><center> Tipe </center></th>
                           <th width="100px"><center> Status</center></th>
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

