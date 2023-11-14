<?php
/* perizinan view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0432',$role_resources_ids)) {?>
    
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
            <?php $attributes = array('name' => 'add_perizinan', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'm-b-1 add');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/perizinan/add_perizinan', $attributes, $hidden);?>

            <div class="form-body">
              <div class="row">

                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="first_name"> Jenis Perizinan </label>
                    <select class=" form-control" name="perizinan_type_id" id="perizinan_type_id" data-plugin="select_hrm" data-placeholder="Piih Jenis Perizinan">
                      <option value=""></option>
                      <?php foreach($get_all_perizinan_type as $perizinan_type) {?>
                      <option value="<?php echo $perizinan_type->perizinan_type_id?>"><?php echo strtoupper($perizinan_type->perizinan_type_name);?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="first_name"> Nama Instansi </label>
                    <select class=" form-control" name="instansi_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Piih Instansi">
                      <option value=""></option>
                      <?php foreach($get_all_instansi as $instansi) {?>
                      <option value="<?php echo $instansi->instansi_id?>"><?php echo strtoupper($instansi->instansi_name);?></option>
                      <?php } ?>
                    </select>
                  </div>

                  
                  
                </div>                

                <div class="col-sm-4"> 

                  <div class="form-group">
                    <label for="name">No Perizinan </label>
                    <input class="form-control" placeholder="No Perizinan" name="perizinan_no" type="text">
                  </div>
                  
                  <div class="form-group">
                    <label for="name">Nama Perizinan </label>
                    <input class="form-control" placeholder="Nama Perizinan" name="perizinan_nama" type="text">
                  </div>
                                    
                </div> 

                 <div class="col-sm-4"> 


                  <div class="form-group">
                    <label for="start_date">Tanggal Diperoleh</label>
                    <input class="form-control date" placeholder="Tanggal Diperoleh" readonly name="start_date" type="text" value="">
                  </div>
               
                  <div class="form-group">
                    <label for="end_date">Berlaku Sampai</label>
                    <input class="form-control date" placeholder="Berlaku Sampai" readonly name="end_date" type="text" value="">
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
    <h3 class="box-title"> Daftar Perizinan Perusahaan </h3>
  </div>
  <div class="box-body">
    <div class="card-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" >
        <thead>
          <tr>
            <th class="text-center" width="50px">No.</th>
            <th class="text-center" width="200px"> Jenis Perizinan</th>  
            <th class="text-center" >Daftar Perizinan</th> 
                     
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
