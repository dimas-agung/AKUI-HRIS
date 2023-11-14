<?php
/* Constants view
*/
?>
<?php $session     = $this->session->userdata('username'); ?>
<?php $moduleInfo  = $this->Core_model->read_setting_info(1); ?>
<?php $get_animate = $this->Core_model->get_content_animate(); ?>

<div class="row match-heights">

  <div class="col-lg-3 col-md-3 <?php echo $get_animate; ?>">
    <div class="box">
      <div class="box-blocks">
        <div class="list-group">
          <a class="list-group-item list-group-item-action nav-tabs-link active" href="#contract" data-constant="1" data-constant-block="contract" data-toggle="tab" aria-expanded="true" id="constant_1">
            <i class="fa fa-pencil-square-o"></i> Jenis Kontrak Kerja
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#qualification" data-constant="2" data-constant-block="qualification" data-toggle="tab" aria-expanded="true" id="constant_2">
            <i class="fa fa-mortar-board"></i> Jenis Jenjang Pendidikan
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#award_type" data-constant="4" data-constant-block="award_type" data-toggle="tab" aria-expanded="true" id="constant_4">
            <i class="fa fa-trophy"></i> Jenis Penghargaan
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#warning_type" data-constant="6" data-constant-block="warning_type" data-toggle="tab" aria-expanded="true" id="constant_6">
            <i class="fa fa-exclamation-triangle"></i> Jenis Peringatan
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#ethnicity_type" data-constant="16" data-constant-block="ethnicity_type" data-toggle="tab" aria-expanded="true" id="constant_16">
            <i class="fa fa-road"></i> Jenis Agama
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#leave_type" data-constant="5" data-constant-block="leave_type" data-toggle="tab" aria-expanded="true" id="constant_5">
            <i class="fa fa-plane"></i> Jenis Cuti
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#sick_type" data-constant="51" data-constant-block="sick_type" data-toggle="tab" aria-expanded="true" id="constant_51">
            <i class="fa fa-medkit"></i> Jenis Sakit
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#izin_type" data-constant="52" data-constant-block="izin_type" data-toggle="tab" aria-expanded="true" id="constant_52">
            <i class="fa fa-info"></i> Jenis Izin
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#exit_type" data-constant="10" data-constant-block="exit_type" data-toggle="tab" aria-expanded="true" id="constant_10">
            <i class="fa fa-sign-out"></i> Jenis Resign
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#exit_type" data-constant="101" data-constant-block="exit_type_reason" data-toggle="tab" aria-expanded="true" id="constant_101">
            <i class="fa fa-sign-out"></i> Jenis Alasan Resign
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#travel_arr_type" data-constant="11" data-constant-block="travel_arr_type" data-toggle="tab" aria-expanded="true" id="constant_11">
            <i class="fa fa-filter"></i> Jenis Dinas
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#transport_arr_type" data-constant="12" data-constant-block="transport_arr_type" data-toggle="tab" aria-expanded="true" id="constant_12">
            <i class="fa fa-car"></i> Jenis Transport
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#perizinan_type" data-constant="14" data-constant-block="perizinan_type" data-toggle="tab" aria-expanded="true" id="constant_14">
            <i class="fa fa-gavel"></i> Jenis Perizinan
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#perjanjian_type" data-constant="13" data-constant-block="perjanjian_type" data-toggle="tab" aria-expanded="true" id="constant_13">
            <i class="fa fa-building"></i> Jenis Perjanjian
          </a>

          <a class="list-group-item list-group-item-action nav-tabs-link" href="#payroll_year" data-constant="104" data-constant-block="payroll_year" data-toggle="tab" aria-expanded="true" id="constant_104">
            <i class="fa fa-calendar"></i> Tahun Penggajian
          </a>
        </div>
      </div>
    </div>

    <!-- <div class="clearfix mb-2"></div> -->



  </div>

  <!-- ===================================================================================================== -->
  <!-- 01 Jenis Kontrak Kerja -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="contract">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Kontrak Kerja </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'contract_type_info', 'id' => 'contract_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_contract_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/contract_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">Jenis Kontrak Kerja</label>
              <input type="text" class="form-control" name="contract_type" placeholder="Jenis Kontrak Kerja">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Kontrak Kerja </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_contract_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Kontrak Kerja </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 02 Jenjang Pendidikan -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="qualification" style="display:none;">
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenjang Pendidikan </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'edu_level_info', 'id' => 'edu_level_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_document_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/edu_level_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">Jenjang Pendidikan</label>
              <input type="text" class="form-control" name="name" placeholder="Jenjang Pendidikan">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenjang Pendidikan </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_education_level">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th width="90%">
                      <center> Jenjang Pendidikan </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> <?php echo $this->lang->line('xin_e_details_language'); ?> </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'edu_language_info', 'id' => 'edu_language_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_edu_language' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/edu_language_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_e_details_language'); ?></label>
              <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_language'); ?>">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> <?php echo $this->lang->line('xin_e_details_language'); ?> </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_qualification_language">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center><?php echo $this->lang->line('xin_e_details_language'); ?> </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> <?php echo $this->lang->line('xin_skill'); ?> </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'edu_skill_info', 'id' => 'edu_skill_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_edu_skill' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/edu_skill_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_skill'); ?></label>
              <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_skill'); ?>">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> <?php echo $this->lang->line('xin_skill'); ?> </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_qualification_skill">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> <?php echo $this->lang->line('xin_skill'); ?> </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 03 Jenis Penghargaan -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="award_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Penghargaan </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'award_type_info', 'id' => 'award_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_award_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/award_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">Jenis Penghargaan</label>
              <input type="text" class="form-control" name="award_type" placeholder="Jenis Penghargaan">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Penghargaan </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_award_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Penghargaan </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 04 Jenis Peringatan -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="warning_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Peringatan </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'warning_type_info', 'id' => 'warning_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_warning_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/warning_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">Jenis Peringatan</label>
              <input type="text" class="form-control" name="warning_type" placeholder="Jenis Peringatan">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Peringatan </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_warning_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Peringatan </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 05 Jenis Agama -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="ethnicity_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Agama </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'ethnicity_type_info', 'id' => 'ethnicity_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_ethnicity_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/ethnicity_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="ethnicity_type">Jenis Agama</label>
              <input type="text" class="form-control" name="ethnicity_type" placeholder="Jenis Agama">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Agama </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_ethnicity_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Agama </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 06 Jenis Cuti -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="leave_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Cuti </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'leave_type_info', 'id' => 'leave_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_leave_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/leave_type_info/', $attributes, $hidden); ?>

            <div class="form-group">
              <label for="name">Jenis Cuti</label>
              <input type="text" class="form-control" name="leave_type" placeholder="Jenis Cuti">
            </div>

            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_days_per_year'); ?></label>
              <input type="text" class="form-control" name="days_per_year" placeholder="<?php echo $this->lang->line('xin_days_per_year'); ?>">
            </div>

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>

            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Cuti </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_leave_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Cuti </center>
                    </th>
                    <th>
                      <center> Jumlah </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 07 Jenis Sakit -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="sick_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Sakit </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'sick_type_info', 'id' => 'sick_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>

            <?php $hidden = array('set_sick_type' => 'UPDATE'); ?>

            <?php echo form_open('admin/settings/sick_type_info/', $attributes, $hidden); ?>

            <div class="form-group">
              <label for="name"> Jenis Sakit</label>
              <input type="text" class="form-control" name="sick_type" placeholder=" Jenis Sakit">
            </div>

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>

            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Sakit </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_sick_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Sakit </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 08 Jenis Izin -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="izin_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Izin </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'izin_type_info', 'id' => 'izin_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>

            <?php $hidden = array('set_izin_type' => 'UPDATE'); ?>

            <?php echo form_open('admin/settings/izin_type_info/', $attributes, $hidden); ?>

            <div class="form-group">
              <label for="name">Jenis Izin </label>
              <input type="text" class="form-control" name="izin_type" placeholder="Jenis Izin ">
            </div>

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>

            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Izin </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_izin_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Izin </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 09 Jenis Resign -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="exit_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> <?php echo $this->lang->line('xin_exit_type'); ?> </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'exit_type_info', 'id' => 'exit_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_exit_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/exit_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">Jenis Resign</label>
              <input type="text" class="form-control" name="exit_type" placeholder="<?php echo $this->lang->line('xin_exit_type'); ?>">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> <?php echo $this->lang->line('xin_exit_type'); ?> </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_exit_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Resign </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 09 Jenis Resign -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="exit_type_reason" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> <?php echo $this->lang->line('xin_exit_type_reason'); ?> </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'exit_type_reason_info', 'id' => 'exit_type_reason_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_exit_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/exit_type_reason_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">Jenis Alasan Resign</label>
              <input type="text" class="form-control" name="exit_type_reason" placeholder="<?php echo $this->lang->line('xin_exit_type_reason'); ?>">
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> <?php echo $this->lang->line('xin_exit_type_reason'); ?> </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_exit_type_reason" width="100%">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Alasan Resign </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 10 Jenis Dinas -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="travel_arr_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Dinas </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'travel_arr_type_info', 'id' => 'travel_arr_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_travel_arr_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/travel_arr_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name"> Jenis Dinas </label>
              <input type="text" class="form-control" name="travel_arr_type" placeholder=" Jenis Dinas" />
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Dinas </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_travel_arr_type">
                <thead>
                  <tr>
                    <th>
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Dinas </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 11 Jenis Transport -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="transport_arr_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Transport </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'transport_arr_type_info', 'id' => 'transport_arr_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_transport_arr_type' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/transport_arr_type_info/', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name"> Jenis Transport </label>
              <input type="text" class="form-control" name="transport_arr_type" placeholder=" Jenis Transport" />
            </div>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Jenis Transport </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_transport_arr_type">
                <thead>
                  <tr>
                    <th width="5%">
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Transport </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 11 Jenis Perjanjian -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="perjanjian_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Perjanjian </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'perjanjian_type_info', 'id' => 'perjanjian_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden = array('set_perjanjian' => 'UPDATE'); ?>
            <?php echo form_open('admin/settings/perjanjian_type_info/', $attributes, $hidden); ?>

            <div class="form-group">
              <label for="name"> Jenis Perjanjian </label>
              <input type="text" class="form-control" name="perjanjian_type_name" placeholder="Jenis Perjanjian" />
            </div>

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Daftar Jenis Perjanjian </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped-perjanjian table-bordered" id="xin_table_perjanjian_type">
                <thead>
                  <tr>
                    <th width="5%">
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Perjanjian </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 12 Jenis Perizinan -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="perizinan_type" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Jenis Perizinan </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'perizinan_type_info', 'id' => 'perizinan_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
            <?php $hidden     = array('set_perizinan' => 'UPDATE'); ?>

            <?php echo form_open('admin/settings/perizinan_type_info/', $attributes, $hidden); ?>

            <div class="form-group">
              <label for="name"> Jenis Perizinan </label>
              <input type="text" class="form-control" name="perizinan_type_name" placeholder="Jenis Perizinan" />
            </div>

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Daftar Jenis Perizinan </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped-perizinan table-bordered" id="xin_table_perizinan_type">
                <thead>
                  <tr>
                    <th width="5%">
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Jenis Perizinan </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================================================================== -->
  <!-- 102 Tahun Penggajian -->
  <!-- ===================================================================================================== -->

  <div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="payroll_year" style="display:none;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?> Tahun Penggajian </h3>
          </div>
          <div class="box-body">
            <?php $attributes = array('name' => 'add_payroll_year', 'id' => 'add_payroll_year', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>

            <?php echo form_open('admin/settings/add_payroll_year/', $attributes, $hidden); ?>

            <div class="form-group">
              <label for="name"> Tahun </label>
              <input type="number" class="form-control" name="year" placeholder="Tahun" />
            </div>

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Tahun Penggajian </h3>
          </div>
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_payroll_year">
                <thead>
                  <tr>
                    <th width="5%">
                      <center> <?php echo $this->lang->line('xin_action'); ?></center>
                    </th>
                    <th>
                      <center> Tahun Penggajian </center>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade edit_setting_datail" id="edit_setting_datail" tabindex="-1" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_setting_info"></div>
  </div>
</div>

<style type="text/css">
  .table-striped {
    width: 100% !important;
  }

  .table-striped-perjanjian {
    width: 100% !important;
  }
</style>
