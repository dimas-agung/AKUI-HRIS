<?php
/* Location view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0221',$role_resources_ids)) {?>
    <div class="box mb-4 <?php echo $get_animate;?>">
      <div id="accordion">
        
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
          <div class="box-tools pull-right"> 
            <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
              <button type="button" class="btn btn-xs btn-primary"> 
                <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?>
              </button>
            </a> 
          </div>
        </div>

        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
          <div class="box-body">
            <?php $attributes = array('name' => 'add_location', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'm-b-1 add');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/location/add_location', $attributes, $hidden);?>
            <div class="form-body">
              <div class="row">
                <div class="col-sm-6">
                 
                  <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                    <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                 
                  <div class="form-group">
                    <label for="name"><?php echo $this->lang->line('xin_location_name');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_location_name');?>" name="name" type="text">
                  </div>
                  <div class="form-group">
                    <label for="email"><?php echo $this->lang->line('xin_email');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_email');?>" name="email" type="email">
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label for="phone"><?php echo $this->lang->line('xin_phone');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_phone');?>" name="phone" type="text">
                      </div>
                      <div class="col-md-6">
                        <label for="xin_faxn"><?php echo $this->lang->line('xin_faxn');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_faxn');?>" name="fax" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group" id="employee_ajax">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="email"><?php echo $this->lang->line('xin_view_locationh');?></label>
                        <select class="form-control" name="location_head" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_view_locationh');?>">
                          <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="address"><?php echo $this->lang->line('xin_address');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text">
                    <br>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text">
                    <br>
                    <div class="row">
                      <div class="col-xs-4">
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text">
                      </div>
                      <div class="col-xs-4">
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="state" type="text">
                      </div>
                      <div class="col-xs-4">
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text">
                      </div>
                    </div>
                    <br>
                    <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_countries as $country) {?>
                      <option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>
<?php } ?>

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_locations');?> </h3>
  </div>
  <div class="box-body">
    <div class="card-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th class="text-center" width="80px"><?php echo $this->lang->line('xin_action');?></th>
            <th class="text-center" ><?php echo $this->lang->line('xin_location_name');?></th>
            <th class="text-center" width="250px"> Kepala Kantor</th>
            <th class="text-center" width="250px"> Perusahaan </th>
            <th class="text-center" width="100px"><i class="fa fa-user"></i> Karyawan</th>           
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
