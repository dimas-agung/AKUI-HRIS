<?php
/*
* Languages - View Page
*/
$session = $this->session->userdata('username');
?>

<div class="row m-b-1 animated fadeInRight">
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if(in_array('266',$role_resources_ids)) {?>
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_acc_category');?> </h3>
      </div>
      <div class="box-body">
        <?php $attributes = array('name' => 'add_asset_category', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/assets/add_category', $attributes, $hidden);?>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_name');?></label>
          <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_name');?>">
        </div>
        <div class="form-actions box-footer">
          <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
        </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
  <?php $colmdval = 'col-md-8';?>
  <?php } else {?>
  <?php $colmdval = 'col-md-12';?>
  <?php } ?>
  <div class="<?php echo $colmdval;?>">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_categories');?> </h3>
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th style="width:100px;"><?php echo $this->lang->line('xin_action');?></th>
                <th><?php echo $this->lang->line('xin_name');?></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
