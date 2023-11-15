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

<?php } ?>

<div class="box <?php echo $get_animate; ?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <b>List Grade Karyawan</b> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th style="text-align: center;">Nama</th>
            <th style="text-align: center;">NIP</th>
            <th style="text-align: center;">Tanggal Join</th>
            <th style="text-align: center;">Nama Departemen</th>
            <th style="text-align: center;">Grade</th>
            <th style="text-align: center;">Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="gradeModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalLabel">Edit Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="level" class="control-label">Karyawan Grade</label>
                        <select name="grade_type" id="grade_type" class="form-control" required>
                            <option value="">-- Pilih Grade --</option>
                            <?php foreach ($employee_grade as $key => $value) { ?>
                                <option value="<?= $value->name ?>"><?= $value->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" onclick="editGrade()">Edit</button>
            </div>
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
            url : "<?php echo site_url("admin/master_gaji_bulanan/grade_karyawan_list") ?>",
            type : 'GET'
        },
            dom: 'lBfrtip',
            // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
            "fnDrawCallback": function(settings){
            $('[data-toggle="tooltip"]').tooltip();          
        }
        });
    });

    function editGrade() {
      let id = $('#gradeModal').find('.btn.btn-success').data('id');
        let grade_type = $("#grade_type").val();
        if ( grade_type == 0) {
          alert('Grade belum dipilih, Silakan pilih Grade Terlebih Dahulu !');
          return;
        }
        console.log('ID: ', id);
        console.log('Grade Type: ', grade_type);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("admin/master_gaji_bulanan/grade_karyawan_update"); ?>',
            data: { id: id , grade_type: grade_type },
            success: function(response) {
            swal({ title: "Berhasil", text: "Berhasil Update Karyawan", icon: "success" });
            $('#gradeModal').modal('hide');
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
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
