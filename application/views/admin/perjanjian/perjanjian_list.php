<?php
/* perjanjian view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0442',$role_resources_ids)) {?>

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
            <?php $attributes = array('name' => 'add_perjanjian', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'm-b-1 add');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/perjanjian/add_perjanjian', $attributes, $hidden);?>

            <div class="form-body">
              <div class="row">

                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="first_name"> Jenis Perjanjian </label>
                    <select class=" form-control" name="perjanjian_type_id" id="perjanjian_type_id" data-plugin="select_hrm" data-placeholder="Piih Jenis Perjanjian">
                      <option value=""></option>
                      <?php foreach($get_all_perjanjian_type as $perjanjian_type) {?>
                      <option value="<?php echo $perjanjian_type->perjanjian_type_id?>"><?php echo strtoupper($perjanjian_type->perjanjian_type_name);?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="name">No Perjanjian </label>
                    <input class="form-control" placeholder="No Perjanjian" name="perjanjian_no" type="text">
                  </div>
                  <div class="form-group">
                    <label for="name">Nama Perjanjian </label>
                    <input class="form-control" placeholder="Nama Perjanjian" name="perjanjian_nama" type="text">
                  </div>
                </div>                

                <div class="col-sm-3">                 
                  <div class="form-group">
                    <label for="name">Pihak 1 </label>
                    <input class="form-control" placeholder="Pihak 1" name="perjanjian_pihak_1" type="text">
                  </div>
                  <div class="form-group">
                    <label for="name">Pihak 2 </label>
                    <input class="form-control" placeholder="Pihak 2" name="perjanjian_pihak_2" type="text">
                  </div>                  
                </div>

                <div class="col-sm-3">                 
                  <div class="form-group">
                    <label for="name">Item </label>
                    <input class="form-control" placeholder="Item" name="perjanjian_item" type="text">
                  </div>
                  <div class="form-group">
                    <label for="name">Nilai </label>
                    <input class="form-control" placeholder="Nilai" name="perjanjian_nilai" type="number">
                  </div>                  
                </div>

                 <div class="col-sm-3">                 
                  <div class="form-group">
                    <label for="start_date">Tanggal Dimulai</label>
                    <input class="form-control date" placeholder="Tanggal Dimulai" readonly name="start_date" type="text" value="">
                  </div>
               
                  <div class="form-group">
                    <label for="end_date">Tanggal Berakhir</label>
                    <input class="form-control date" placeholder="Tanggal Berakhir" readonly name="end_date" type="text" value="">
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
    <h3 class="box-title"> Daftar Perjanjian Perusahaan </h3>
  </div>
  <div class="box-body">
    <div class="card-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" >
        <thead>
          <tr>
            <th class="text-center" width="50px">No.</th>
            <th class="text-center" width="200px"> Jenis Perjanjian</th>  
            <th class="text-center" >Daftar Perjanjian</th> 
                     
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
