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
        <?php $attributes = array('name' => 'add_assets', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'form');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open_multipart('admin/assets/add_asset', $attributes, $hidden);?>
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="first_name"><?php echo $this->lang->line('xin_acc_category');?></label>
                    <select class="form-control" name="category_id" id="category_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_acc_category');?>">
                      <option value=""></option>
                      <?php foreach($all_assets_categories as $assets_category) {?>
                      <option value="<?php echo $assets_category->assets_category_id?>"><?php echo $assets_category->category_name?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="asset_name" class="control-label"><?php echo $this->lang->line('xin_asset_name');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_asset_name');?>" name="asset_name" type="text" value="">
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="manufacturer"><?php echo $this->lang->line('xin_manufacturer');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_manufacturer');?>" name="manufacturer" type="text" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="xin_serial_number" class="control-label"><?php echo $this->lang->line('xin_serial_number');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_serial_number');?>" name="serial_number" type="text" value="">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="award_information"><?php echo $this->lang->line('xin_asset_note');?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_asset_note');?>" name="asset_note" cols="30" rows="3" id="asset_note"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="company_asset_code"><?php echo $this->lang->line('xin_company_asset_code');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_asset_code');?>" name="company_asset_code" type="text" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="is_working" class="control-label"> Kondisi Aset </label>
                    <select class="form-control" name="is_working" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_is_working');?>">
                      <option value=""> -- Pilih Kondisi Aset -- </option>
                      <option value="1"> Bagus </option>
                      <option value="2"> Rusak </option>
                      <option value="3"> Dalam Perbaikan </option>
                      <option value="4"> Hilang </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="purchase_date"><?php echo $this->lang->line('xin_purchase_date');?></label>
                    <input class="form-control asset_date" placeholder="<?php echo $this->lang->line('xin_purchase_date');?>" name="purchase_date" type="text" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="role"><?php echo $this->lang->line('xin_invoice_number');?></label>
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_invoice_number');?>" name="invoice_number" type="text" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="warranty_end_date" class="control-label"><?php echo $this->lang->line('xin_warranty_end_date');?></label>
                    <input class="form-control asset_date" placeholder="<?php echo $this->lang->line('xin_warranty_end_date');?>" name="warranty_end_date" type="text" value="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <fieldset class="form-group">
                      <label for="asset_image"><?php echo $this->lang->line('xin_asset_image');?></label>
                      <input type="file" class="form-control-file" id="asset_image" name="asset_image">
                      <small><?php echo $this->lang->line('xin_asset_allowed_image_formats');?></small>
                    </fieldset>
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
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_assets');?> </h3>
      </div>
  <div class="box-body">
  <div class="box-datatable table-responsive">
    <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
      <thead>
        <tr>
          <th width="8%" class="text-center"><?php echo $this->lang->line('xin_action');?></th>
          <th width="5%" class="text-center"> Foto</th>
          <th width="10%" class="text-center"> Kode Aset</th>
          <th class="text-center"><i class="fa fa-cubes"></i> Nama Aset</th>
          <th width="20%" class="text-center"> <i class="fa fa-cube"></i> Kategori Aset</th>
          <th width="8%" class="text-center">Tanggal Beli</th>
          <th width="8%" class="text-center"> Kondisi Aset </th>
          <th width="8%" class="text-center"> Status Aset </th>
        </tr>
      </thead>
    </table>
  </div>
</div>
</div>