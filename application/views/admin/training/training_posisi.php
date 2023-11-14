<?php
/* Training Type view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>

<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('551',$role_resources_ids)) {?>

    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

    <div class="box mb-4 <?php echo $get_animate;?>">
      <div id="accordion">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
          <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
              <button type="button" class="btn btn-xs btn-primary"> 
                  <span class="fa fa-plus-circle"> </span> <?php echo $this->lang->line('xin_add_new');?>
              </button>
            </a> 
          </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
          <div class="box-body">
            <?php $attributes = array('name' => 'add_type', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'm-b-1 add');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/training_posisi/add_type', $attributes, $hidden);?>
            
          <div class="form-group">
            <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
            <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Pilih Perusahaan">
              <option value=""></option>
              <?php foreach($get_all_companies as $company) {?>
              <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
              <?php } ?>
            </select>
          </div>         
        
          <div class="form-group" id="department_ajax">
            <label for="name"><?php echo $this->lang->line('xin_hr_main_department');?></label>
            <select disabled="disabled" class="select2" data-plugin="select_hrm" data-placeholder="Pilih Department" name="department_id">
              <option value=""></option>
            </select>
          </div>

          <div class="form-group" id="designation_ajax">
            <label for="name"><?php echo $this->lang->line('xin_designations');?></label>
            <select disabled="disabled" class="select2" data-plugin="select_hrm" data-placeholder="Pilih Posisi" name="designation_id">
              <option value=""></option>
            </select>
          </div>      

          <div class="form-group">
            <label for="training_type"> Jenis Pelatihan </label>
            <select class="form-control" class="select2" data-plugin="select_hrm" data-placeholder="Pilih Jenis Pelatihan"  name="type_id">
              <option value=""></option>
              <?php foreach($all_training_types as $training_type) {?>
              <option value="<?php echo $training_type->training_type_id?>"><?php echo $training_type->type?></option>
              <?php } ?>
            </select>
          </div>   

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
            </div>

            <?php echo form_close(); ?> 
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> Daftar <?php echo $this->lang->line('xin_training_posisi');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="40">No.</th>
            <th width="400"><i class="fa fa-user"></i> Jenis Pelatihan </th>
            <th ><i class="fa fa-user"></i> Daftar Posisi </th>
          
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

