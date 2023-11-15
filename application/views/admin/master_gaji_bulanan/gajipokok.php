<?php

/**
 * Office Shift view
 */

$session = $this->session->userdata('username');
$get_animate = $this->Core_model->get_content_animate();
$role_resources_ids = $this->Core_model->user_role_resource();

if (in_array('0822', $role_resources_ids)) {
  $user_info = $this->Core_model->read_user_info($session['user_id']);
?>
  <div class="box mb-4 <?php echo $get_animate; ?>">
    <div id="accordion">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new'); ?></h3>
        <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new'); ?></button>
          </a> </div>
      </div>
      <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
        <div class="box-body">
          <?php $attributes = array('name' => 'add_office_shift', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
          <?php $hidden = array('user_id' => $session['user_id']); ?>
          <?php echo form_open('admin/master_gaji_bulanan/gajipokok_add', $attributes, $hidden); ?>
          <div class="bg-white">
            <div class="box-block">

              <div class="row">

                <div class="col-md-12">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Perusahaan</label>
                        <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>" required>
                          <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                          <?php foreach ($get_all_companies as $company) { ?>
                            <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                    <label for="nominal" class="control-label">Nominal</label>
                        <input class="form-control" placeholder="Nominal.." name="nominal" type="number" value="" id="nominal" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date" class="control-label">Start Date</label>
                        <input class="form-control" placeholder="Nama Pola Kerja Shift" name="start_date" type="date" value="" id="start_date" required>
                      </div>
                     
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date" class="control-label">End Date</label>
                        <input class="form-control" placeholder="Nama Pola Kerja Shift" name="end_date" type="date" value="" id="end_date" required>
                      </div>
                    </div>
                  </div>

                </div>

              </div>

              <div class="form-actions box-footer">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<div class="box <?php echo $get_animate; ?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <b>List Gaji Pokok </b> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th style="text-align: center;">Perusahaan</th>
            <th style="text-align: center;">Nominal</th>
            <th style="text-align: center;">Tanggal Mulai</th>
            <th style="text-align: center;">Tanggal Akhir</th>
            <th style="text-align: center;">Status</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<style type="text/css">
  .box-tools {
    margin-right: -5px !important;
  }

  .col-md-8 {
    padding-left: 0px !important;
    padding-right: 0px !important;
  }

  .dataTables_length {
    float: left;
  }

  .dt-buttons {
    position: relative;
    float: right;
    margin-left: 10px;
  }

  .hide-calendar .ui-datepicker-calendar {
    display: none !important;
  }

  .hide-calendar .ui-priority-secondary {
    display: none !important;
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    var xin_table = $('#xin_table').dataTable({
          "bDestroy": true,
          "ajax": {
            url : "<?php echo site_url("admin/master_gaji_bulanan/gajipokok_list") ?>",
            type : 'GET'
          },
          dom: 'lBfrtip',
          // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
          "fnDrawCallback": function(settings){
          $('[data-toggle="tooltip"]').tooltip();          
        }
      });
    });

    function aktif(id) {
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("admin/master_gaji_bulanan/gajipokok_aktif"); ?>',
        data: { id: id },
        success: function(response) {
          swal({ title: "Berhasil", text: "Berhasil Aktifkan Gaji Pokok", icon: "success" });
          $('#xin_table').DataTable().ajax.reload();
        },
        error: function(error) {
            console.error('Error:', error);
        }
      });
    }

    function nonaktif(id) {
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("admin/master_gaji_bulanan/gajipokok_nonaktif"); ?>',
        data: { id: id },
        success: function(response) {
          swal({ title: "Berhasil", text: "Berhasil Nonaktifkan Gaji Pokok", icon: "success" });
          $('#xin_table').DataTable().ajax.reload();
        },
        error: function(error) {
            console.error('Error:', error);
        }
      });
    }
</script>
