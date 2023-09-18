<?php
/* Trainers view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Core_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if (in_array('561', $role_resources_ids)) { ?>
  <?php $user_info = $this->Core_model->read_user_info($session['user_id']); ?>
  <div class="box mb-4 <?php echo $get_animate; ?>">
    <div id="accordion">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new'); ?> </h3>
        <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new'); ?></button>
          </a> </div>
      </div>
      <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
        <div class="box-body">
          <?php $attributes = array('name' => 'add_trainer', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
          <?php $hidden = array('user_id' => $session['user_id']); ?>
          <?php echo form_open('admin/trainers/add_trainer', $attributes, $hidden); ?>
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-12">

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name'); ?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name'); ?>" name="first_name" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_last_name'); ?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name'); ?>" name="last_name" type="text" value="">
                      </div>
                    </div>
                  </div>

                  <!-- <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="company_name">Nama Vendor</label>
                        <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="Nama Vendor">
                          <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                          <?php foreach ($all_vendors as $company) { ?>
                            <option value="<?php echo $company->vendor_id; ?>"> <?php echo $company->name; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div> -->

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="expertise"><?php echo $this->lang->line('xin_expertise'); ?></label>
                        <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_expertise'); ?>" name="expertise" cols="30" rows="5" id="expertise"></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="address"><?php echo $this->lang->line('xin_address'); ?></label>
                        <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_address'); ?>" name="address" cols="30" rows="3" id="address"></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="contact_number"><?php echo $this->lang->line('xin_contact_number'); ?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number'); ?>" name="contact_number" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email'); ?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email'); ?>" name="email" type="text" value="">
                      </div>
                    </div>
                  </div>

                </div>

              </div>


              <div class="form-actions box-footer">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
  <div class="box-header with-border">
    <h3 class="box-title"> Daftar Pelatih </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="80"><?php echo $this->lang->line('xin_action'); ?></th>
            <th width="350"><i class="fa fa-user"></i> Nama Pelatih</th>
            <th width="200"><i class="fa fa-phone"></i> <?php echo $this->lang->line('xin_contact_number'); ?></th>
            <th width="250"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('dashboard_email'); ?> </th>
            <th width="250"> <i class="fa fa-graduation-cap"></i> Bidang Keahlian </th>
            <!-- <th> <i class="fa fa-building"></i> Nama Vendor </th> -->
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>