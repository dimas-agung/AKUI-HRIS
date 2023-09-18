<?php
/* vendors view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('571',$role_resources_ids)) {?>

    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
    <div class="box mb-4 <?php echo $get_animate;?>">
      <div id="accordion">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
          <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
            </a> 
          </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
          <div class="box-body">
            <?php $attributes = array('name' => 'add_vendor', 'id' => 'xin-form', 'autocomplete' => 'off');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/vendors/add_vendor', $attributes, $hidden);?>
            <div class="bg-white">
              <div class="box-block">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?></label>
                          <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="">
                        </div>
                      </div>                  
                    </div>                
                  </div>             
                </div>
                
                <div class="form-actions box-footer">
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
                </div>

              </div>
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>

<?php } ?>

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> Daftar Vendor </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="80"><?php echo $this->lang->line('xin_action');?></th>
            <th ><i class="fa fa-user"></i> Nama Vendor</th>
           
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
