<?php
/* Constants view
*/
?>
<?php $session     = $this->session->userdata('username');?>
<?php $moduleInfo  = $this->Core_model->read_setting_info(1);?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0832',$role_resources_ids)) {?>
  <div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
          <?php $attributes = array('name' => 'shift_jam_info', 'id' => 'shift_jam_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add');?>
          
          <?php $hidden = array('set_shift_jam' => 'UPDATE');?>
          
          <?php echo form_open('admin/pengaturan/shift_jam_info/', $attributes, $hidden);?>
          
          <div class="row">
        
              <div class="col-md-6">

                <div class="form-group">
                  <label for="name">Kode</label>
                  <input type="text" class="form-control" name="kode" placeholder="Kode">
                </div>

                 <div class="form-group">
                  <label for="clock_in">Jam Shift Mulai</label>
                  <input class="form-control timepicker" placeholder="Jam Shift Mulai" name="start_date" type="text" >
                </div>

                <div class="form-group">
                  <label for="clock_out">Jam Shift Sampai</label>
                  <input class="form-control timepicker" placeholder="Jam Shift ampai" name="end_date" type="text" >
                </div>

              </div>

               <div class="col-md-6">

                <div class="form-group">
                  <label for="name">Keterangan</label>
                  <input type="text" class="form-control" name="keterangan" placeholder="Keterangan">
                </div> 

              </div>

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
   <h3 class="box-title"> <b>JAM KERJA SHIFT </b> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_shift_jam">
        <thead>
          <tr>
            <th width="5%"><center> <?php echo $this->lang->line('xin_action');?></center></th>
            <th width="80px"><center> Kode</center></th> 
            <th width="100px"><center> Jam Shift Mulai</center></th> 
            <th width="100px"><center> Jam Shift Selesai</center></th> 
            <th><center> Keterangan</center></th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>



<div class="modal fade edit_setting_datail" id="edit_setting_datail" tabindex="-1" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_setting_info"></div>
  </div>
</div>

<style type="text/css">
    .table-striped { width:100% !important; }
</style>