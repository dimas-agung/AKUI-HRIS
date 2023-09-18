<?php
/* Assets view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if(in_array('262',$role_resources_ids)) {?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?></h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'add_asset_pinjam', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'form');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open_multipart('admin/assets/add_asset_pinjam', $attributes, $hidden);?>
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
               <div class="row">
                <div class="col-md-6">
                  
                  <div class="form-group">
                    <label for="company_id"><?php echo $this->lang->line('left_company');?></label>
                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Pilih Perusahaan">
                      <option value=""></option>
                      <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                      <?php } ?>
                    </select>
                  </div>                
                  
                </div>
                <div class="col-md-6">
                  <div class="form-group" id="employee_ajax">
                    <label for="first_name"> Dipinjam Oleh </label>
                    <select class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="Pilih Karyawan">
                      <option value=""></option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="first_name"> Kategori Aset </label>
                    <select class="form-control" name="category_id" id="category_id" data-plugin="select_hrm" data-placeholder="Pilih Kategori Aset">
                      <option value=""></option>
                      <?php foreach($all_assets_categories as $assets_category) {?>
                      <option value="<?php echo $assets_category->assets_category_id?>"><?php echo $assets_category->category_name?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group" id="assets_ajax">
                    <label for="asset_name" class="control-label"> Nama Aset </label>
                    <select class="form-control" name="assets_id" id="assets_id" data-plugin="select_hrm" data-placeholder="Pilih Nama Aset">
                      <option value=""></option>
                    </select>
                  </div>
                </div>
              </div>
             
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pinjam_date">Tanggal Pinjam </label>
                    <input class="form-control asset_date" placeholder="Tanggal Pinjam" name="pinjam_date" type="text" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="form-group">
                    <label for="pinjam_date">Tanggal Kembali </label>
                    <input class="form-control asset_date" placeholder="Tanggal Kembali" name="kembali_date" type="text" value="">
                  </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-md-6">
              
                <div class="form-group">
                  <label for="award_information"> Catatan Pinjam Aset </label>
                  <textarea class="form-control" placeholder="Catatan Pinjam Aset" name="asset_note" cols="30" rows="6" id="asset_note"></textarea>
                </div>     

                <div class="row">
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="is_pinjam" class="control-label"> Status Aset</label>
                    
                    <select class="form-control" name="is_pinjam" data-plugin="select_hrm" data-placeholder="Pilih Status Aset">
                      <option value=""> Pilih Status </option>
                      <option value="1"> Dipinjam </option>
                      <option value="2"> Dikembalikan </option>
                    </select>
                  </div>
                </div>
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
        <h3 class="box-title"> Daftar Semua Aset Status : Dipinjam </h3>
      </div>
  <div class="box-body">
  <div class="box-datatable table-responsive">
    <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
      <thead>
        <tr>
          <th width="8%" class="text-center"><?php echo $this->lang->line('xin_action');?></th>
          <th width="10%" class="text-center"> <i class="fa fa-clock-o"></i> Tanggal Pinjam</th>
           <th width="10%" class="text-center"> <i class="fa fa-clock-o"></i> Tanggal Kembali</th>
          <th width="10%" class="text-center"> NIP </th>
          <th width="20%" class="text-center"> Nama Karyawan </th>
          <th width="10%" class="text-center"> Kode Aset </th>
          <th class="text-center"> Nama Aset Dipinjam </th>
          <th width="10%" class="text-center"> Status Aset </th>          
        </tr>
      </thead>
    </table>
  </div>
</div>
</div>