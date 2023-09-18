<?php
/* Database Backup Log view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<div class="row  <?php echo $get_animate;?>" style="margin-bottom:10px;">
  
  

</div>
<div class="box  <?php echo $get_animate;?>">
  <div class="box-header with-border">
   
                 
    <div class="row" >                            
        <div class="col-md-8">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_backup_log');?></h3> 
        </div>

        <div class="col-md-2 text-right">
            <?php $attributes = array('name' => 'del_backup', 'id' => 'del_backup', 'autocomplete' => 'off');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/settings/delete_db_backup', $attributes, $hidden);?>
            <button type="submit" class="btn btn-danger save"><i class="fa fa-times"></i> <?php echo $this->lang->line('xin_delete_old_backup');?></button>
            <?php echo form_close(); ?> 
        </div>
        
        <div class="col-md-2 text-left">
          <?php $attributes = array('name' => 'db_backup', 'id' => 'db_backup', 'autocomplete' => 'off');?>
          <?php $hidden = array('user_id' => $session['user_id']);?>
          <?php echo form_open('admin/settings/create_database_backup', $attributes, $hidden);?>
          <button type="submit" class="btn btn-primary save"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('xin_create_backup');?></button>
          <?php echo form_close(); ?> 
        </div>
    </div>
  </div>

  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="80px"><?php echo $this->lang->line('xin_action');?></th>
            <th width="120px"><?php echo $this->lang->line('xin_e_details_date');?></th>
            <th ><?php echo $this->lang->line('xin_database_file');?></th>
            
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
