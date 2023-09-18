<?php
/* Department view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0231',$role_resources_ids)) {?>
    <div class="box mb-4 <?php echo $get_animate;?>">
        <div id="accordion">   
           
          <div class="box-header  with-border">
            <h3 class="box-title"> <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
            <div class="box-tools pull-right"> 
              <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
              <button type="button" class="btn btn-xs btn-primary"> 
                <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?>
              </button>
              </a> 
            </div>
          </div>

          <div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
            <div class="box-body">
          <?php $attributes = array('name' => 'add_department', 'id' => 'xin-form', 'autocomplete' => 'off');?>
          <?php $hidden = array('user_id' => $session['user_id']);?>
          <?php echo form_open('admin/department/add_department', $attributes, $hidden);?>
          
          <div class="form-group">
            <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
            <select class=" form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
              <option value=""></option>
              <?php foreach($get_all_companies as $company) {?>
              <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group" id="location_ajax">
            <label for="name"><?php echo $this->lang->line('left_location');?></label>
            <select disabled="disabled" name="location_id" id="location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
              <option value=""></option>
            </select>
          </div>
          
          <div class="form-group" id="employee_ajax">
            <label for="name"><?php echo $this->lang->line('xin_department_head');?></label>
            <select disabled="disabled" name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_department_head');?>">
              <option value=""></option>
            </select>
          </div>

          <div class="form-group">
            <label for="name"><?php echo $this->lang->line('xin_name');?> <?php echo $this->lang->line('xin_departments');?></label>
            <?php
            $data = array(
              'name'        => 'department_name',
              'id'          => 'department_name',
              'value'       => '',
              'placeholder'   => $this->lang->line('xin_name'),
              'class'       => 'form-control',
            );
          
          echo form_input($data);
          ?>
          </div>       

          <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
          <?php echo form_close(); ?> </div>
          </div>
        </div>  
    </div>
<?php } ?>

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_departments');?> </h3>
  </div>
  <div class="box-body">
    <div class="card-datatable table-responsive">
        <table class="datatables-demo table table-striped table-bordered" id="xin_table">
          <thead>
            <tr>
              <th class="text-center" width="50px">No.</th>
              <th class="text-center" width="250px"> Perusahaan </th>
              <th class="text-center" > Daftar Departemen </th>              
            </tr>
          </thead>
        </table>
    </div>
  </div>
</div>