<?php
/* instansi view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0251',$role_resources_ids)) {?>
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
            <?php $attributes = array('name' => 'add_instansi', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'm-b-1 add');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/instansi/add_instansi', $attributes, $hidden);?>
            <div class="form-body">
              <div class="row">

                <div class="col-sm-6">
                  
                  <div class="form-group">
                    <label for="name">Nama Instansi </label>
                    <input class="form-control" placeholder="Nama Instansi" name="instansi_name" type="text">
                  </div>
                  <div class="form-group">
                    <label for="name">Nama Kontak Instansi </label>
                    <input class="form-control" placeholder="Nama Kontak Instansi" name="instansi_contact" type="text">
                  </div>
                  <div class="form-group">
                    <label for="name">No Telp Instansi </label>
                    <input class="form-control" placeholder="No Telp Instansi" name="instansi_phone" type="text">
                  </div>

                </div>                

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">Alamat Instansi </label>
                    <textarea class="form-control" placeholder="Alamat Instansi" name="instansi_address" cols="30" rows="10" id="instansi_address"></textarea>
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
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_instansis');?> </h3>
  </div>
  <div class="box-body">
    <div class="card-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" >
        <thead>
          <tr>
            <th class="text-center" width="80px"><?php echo $this->lang->line('xin_action');?></th>
           
            <th class="text-center"  width="250px" >Nama Instansi</th>  
            <th class="text-center">Alamat Instansi</th>
            <th class="text-center" width="150px">No. Telp Instansi</th>   
            <th class="text-center" width="200px">Kontak Instansi</th>  
            
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
