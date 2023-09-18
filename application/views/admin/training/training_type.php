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
            <?php echo form_open('admin/training_type/add_type', $attributes, $hidden);?>
            
            <div class="form-group">
              <label for="type_kategori"> Kategori  </label>
              <select class="form-control" name="type_kategori" id="type_kategori" data-plugin="select_hrm" data-placeholder="Kategori Pelatihan">
                <option value=""></option>
                <option value="1"> Pelatihan Wajib </option>
                <option value="2"> Pelatihan Support </option>
              </select>
            </div>   

            <div class="form-group">
              <label for="type_name"> Jenis Pelatihan </label>
              <input type="text" class="form-control" name="type_name" placeholder="<?php echo $this->lang->line('left_training_type');?>">
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
    <h3 class="box-title"> Daftar <?php echo $this->lang->line('xin_training_types');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="40">No.</th>
            <th width="200"><i class="fa fa-user"></i> Katgori </th>
            <th ><i class="fa fa-user"></i> Jenis Pelatihan</th>
          
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

