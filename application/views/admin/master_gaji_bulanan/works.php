<?php
/* works view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $xuser_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>

 <div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">      

      <div class="box-header with-border">
        <h3 class="box-title"> <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
        <div class="box-tools pull-right"> 
            <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> 
              <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?>
            </button>
            </a> 
          </div>
      </div>

      <div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
        
        <div class="box-body">
        
          <?php $attributes = array('name' => 'add_work', 'id' => 'xin-form', 'autocomplete' => 'off');?>
          <?php $hidden = array('user_id' => $session['user_id']);?>
          <?php echo form_open('admin/pengaturan/add_work', $attributes, $hidden);?>
        
          <div class="row">

            <div class="col-md-6">

              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                    <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date');?>"  name="start_date" type="text">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                    <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date');?>"  name="end_date" type="text">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="bulan">Bulan  </label>
                    <select class="form-control input-sm" name="bulan" id="bulan" data-plugin="select_hrm" data-placeholder="Bulan" required>
                      <?php foreach($all_bulan as $bulan_gaji) {?>
                        <option value="<?php echo $bulan_gaji->id;?>" ><?php echo $bulan_gaji->bulan_nama?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tahun">Tahun  </label>
                    <select class="form-control input-sm" name="tahun" id="tahun" data-plugin="select_hrm" data-placeholder="Tahun" required>
                      <?php foreach($all_tahun as $tahun_gaji) {?>
                        <option value="<?php echo $tahun_gaji->tahun_nama;?>" ><?php echo $tahun_gaji->tahun_nama?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>            

              <div class="form-group">
                <label for="name">Nama Bulan Kerja</label>
                <input type="text" class="form-control" name="desc" placeholder="Nama Bulan Kerja">
              </div>
            </div>
         
            <div class="col-md-6">

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="description">Keterangan</label>
                    <textarea class="form-control textarea" placeholder="Keterangan" name="description" id="description"></textarea>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="is_publish">Status Aktif </label>
                    <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_status');?>">
                      <option value="1"><?php echo $this->lang->line('xin_published');?></option>
                      <option value="0"><?php echo $this->lang->line('xin_unpublished');?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="is_payroll">Status Gaji</label>
                    <select name="is_payroll" class="select2" data-plugin="select_hrm" data-placeholder="Pilih Status Release Gaji">
                      <option value="1"> Release </option>
                      <option value="0"> Draft </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="is_recap">Status Laporan </label>
                    <select name="is_recap" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_status');?>">
                      <option value="1"><?php echo $this->lang->line('xin_published');?></option>
                      <option value="0"><?php echo $this->lang->line('xin_unpublished');?></option>
                    </select>
                  </div>
                </div>
              </div>   
              
               <div class="row">
                <div class="col-md-12">
                  <div class="form-group simpan_atur">
                    <div class="box-footer hris-salary-button">
                      <button type="submit" class="btn btn-primary"> 
                        <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> 
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <?php echo form_close(); ?> 
            </div>
          </div>

        </div>
      </div> 

    </div>
  </div>     


<div class="row m-b-1"> 
  <div class="col-md-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_works');?> </h3>
       
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th width="150px"><center> <?php echo $this->lang->line('xin_action');?></center></th>
                <th width="200px"><center> <i class="fa fa-calendar"></i> Periode Tanggal </center></th>  
                <th width="100px"><center> Bulan </center></th>
                <th width="100px"><center> Tahun </center></th>
                <th width="100px"><center> Kode </center></th>
                <th width="250px"><center> Nama Bulan Kerja </center></th>              
                <th ><center> Keterangan </center></th>
                <th width="100px"> Status<br>Aktif </center></th>
                <th width="100px"> Status<br>Gaji  </center></th>
                <th width="100px" >Status<br>Laporan </center></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
.trumbowyg-editor { min-height:110px !important; }
</style>

<style type="text/css">
.box-tools {
    margin-right: -5px !important;
}
.col-md-8 {
  padding-left:0px !important;
  padding-right: 0px !important;
}
.dataTables_length {
  float:left;
}
.dt-buttons {
    position: relative;
    float: right;
    margin-left: 10px;
}
.hide-calendar .ui-datepicker-calendar { display:none !important; }
.hide-calendar .ui-priority-secondary { display:none !important; }
</style>