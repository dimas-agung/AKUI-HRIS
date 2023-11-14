<?php
/* Holidays view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $xuser_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>

<!-- <?php if($xuser_info[0]->user_role_id==1){ ?> -->
<div id="filter_hris" class="collapse add-formd <?php echo $get_animate;?>" data-parent="#accordion" style="">
<div class="row">
  <div class="col-md-12">
    <div class="box mb-4">
    <div class="box-header  with-border">
      <h3 class="box-title"><?php echo $this->lang->line('xin_filter');?></h3>
          <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-minus"></span> <?php echo $this->lang->line('xin_hide');?></button>
            </a> </div>
        </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <?php $attributes = array('name' => 'ihr_report', 'id' => 'ihr_report', 'class' => 'm-b-1 add form-hrm');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/pengaturan/holidays_list', $attributes, $hidden);?>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="department"><?php echo $this->lang->line('module_company_title');?></label>
                  <select class="form-control" name="company" id="aj_companyf" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>" required>
                    <option value="0"><?php echo $this->lang->line('xin_all_companies');?></option>
                    <?php foreach($get_all_companies as $company) {?>
                    <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                    <select class="form-control" name="status" id="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                      <option value="all" ><?php echo $this->lang->line('xin_acc_all');?></option>
                      <option value="1"><?php echo $this->lang->line('xin_published');?></option>
                	  <option value="0"><?php echo $this->lang->line('xin_unpublished');?></option>
                    </select>
                  </div>
              </div>
              <div class="col-md-1"><label for="xin_get">&nbsp;</label><button name="hris_form" type="submit" class="btn btn-primary"><i class="fa fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_get');?></button>
            </div>
            </div>
            
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- <?php } ?> -->

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
        
          <?php $attributes = array('name' => 'add_holiday', 'id' => 'xin-form', 'autocomplete' => 'off');?>
          <?php $hidden = array('user_id' => $session['user_id']);?>
          <?php echo form_open('admin/pengaturan/add_holiday', $attributes, $hidden);?>
        
          <div class="row">

            <div class="col-md-6">

              <div class="form-group">
                <label for="name"><?php echo $this->lang->line('xin_event_name');?></label>
                <input type="text" class="form-control" name="event_name" placeholder="<?php echo $this->lang->line('xin_event_name');?>">
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                    <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly name="start_date" type="text">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                    <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date');?>" readonly name="end_date" type="text">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                <select multiple class="form-control"  name="company_id[]"  data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                  <option value=""></option>
                  <?php foreach($get_all_companies as $company) {?>
                  <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
         
            <div class="col-md-6">

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                    <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" id="description"></textarea>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="is_publish"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                    <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_status');?>">
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
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_holidays');?> </h3>
        <?php if($xuser_info[0]->user_role_id==1){ ?><div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-filter"></span> <?php echo $this->lang->line('xin_filter');?></button>
       </a> </div><?php } ?>
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th style="width:80px;"><?php echo $this->lang->line('xin_action');?></th>
                <th width="200px"><i class="fa fa-calendar"></i> Tanggal Libur</th>                               
                <th width="300px"><i class="fa fa-building"></i> Perusahaan </th>
                <th width="300px">Nama Hari Libur</th>
                <th >Keterangan </th>
                <th width="100px">Status</th>
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