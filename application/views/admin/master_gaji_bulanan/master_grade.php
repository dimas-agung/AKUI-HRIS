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
          <?php echo form_open('admin/master_gaji_bulanan/master_grade_add', $attributes, $hidden); ?>
          <div class="bg-white">
            <div class="box-block">

              <div class="row">

                <div class="col-md-12">

                  <div class="row">
                    <div class="col-md-12">
                    <label for="level" class="control-label">Level (1-9)</label>
                        <input class="form-control" placeholder="Level.." name="level" type="number" value="" id="level" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                    <label for="grade" class="control-label">Grade (A-Z)</label>
                        <input class="form-control" placeholder="Grade.." name="grade" type="text" value="" id="grade" required>
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
    <h3 class="box-title"> <b>List Grade </b> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th style="text-align: center;">Nama</th>
            <th style="text-align: center;">Level</th>
            <th style="text-align: center;">Grade</th>
            <!-- <th style="text-align: center;">Tanggal Akhir</th> -->
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
                								url : "<?php echo site_url("admin/master_gaji_bulanan/master_grade_list") ?>",
                								type : 'GET'
                							},
                							dom: 'lBfrtip',
                							// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
                							"fnDrawCallback": function(settings){
                							$('[data-toggle="tooltip"]').tooltip();          
            							}
    });
  });
  // $(document).ready(function(){
  //      document.getElementById("T29").disabled = false;
  //       document.getElementById("T30").disabled = false;
  //       document.getElementById("T31").disabled = false;
  // });

  function cekBulanKerja() {
    var payroll_id = document.getElementsByName("payroll_id")[0].value;

    document.getElementById("T29").disabled = false;
    document.getElementById("T30").disabled = false;
    document.getElementById("T31").disabled = false;

    if (payroll_id == '1') {
      $(".info").html('Januari 2022');
    } else if (payroll_id == '2') {
      $(".info").html('Februari 2022');
    } else if (payroll_id == '3') {
      $(".info").html('Maret 2022');
    } else if (payroll_id == '4') {
      $(".info").html('April 2022');
    } else if (payroll_id == '5') {
      $(".info").html('Mei 2022');
    } else if (payroll_id == '6') {
      $(".info").html('Juni 2022');
    } else if (payroll_id == '7') {
      $(".info").html('Juli 2022');
    } else if (payroll_id == '8') {
      $(".info").html('' + payroll_id + '');
    }
  }
</script>
