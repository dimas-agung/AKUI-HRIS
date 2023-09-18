<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php if(isset($_GET['jd']) && isset($_GET['office_shift_id']) && $_GET['data']=='shift'){ ?>

      <?php $assigned_ids = explode(',',$employee_id);  ?>

      <?php $session = $this->session->userdata('username');?>
      <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Pola Kerja Shift</h4>
      </div>

      <?php $attributes = array('name' => 'edit_office_shift', 'id' => 'edit_office_shift', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
      <?php $hidden     = array('_method' => 'EDIT', '_token' => $office_shift_id, 'ext_name' => $office_shift_id);?>

      <?php echo form_open('admin/pengaturan/edit_office_shift/'.$office_shift_id, $attributes, $hidden);?>

      <div class="modal-body">

            <div class="row">
              <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Nama Pola Kerja</label>
                        <input class="form-control" placeholder="Nama Pola Kerja Shift" name="shift_name" type="text" id="name" value="<?php echo $shift_name;?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Perusahaan</label>
                        <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                        <option value=""></option>
                        <?php foreach($get_all_companies as $company) {?>
                        <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
                        <?php } ?>
                      </select>
                      </div>
                    </div>
                  </div>
              </div>

              <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="date"><?php echo $this->lang->line('xin_e_details_month_work');?></label>
                         <select class="form-control input-sm" name="payroll_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work');?>">
                           <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                          <option value="<?php echo $bulan_gaji->payroll_id?>" <?php if($bulan_gaji->payroll_id==$payroll_id):?> selected="selected"<?php endif;?>><?php echo $bulan_gaji->desc?></option>
                       <?php } ?>
                      </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="date"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
                          <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="start_date" type="text"  value="<?php echo $start_date;?>" >
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="end_date" type="text" value="<?php echo $end_date;?>" >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

           <?php $result = $this->Department_model->ajax_company_employee_info_shift($company_id);?>

            <div class="row">
              <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group" id="employee_ajax">
                        <label for="employee" class="control-label"><?php echo $this->lang->line('xin_employee');?> Shift</label>
                        <select multiple class="form-control input-sm" name="employee_id[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_list');?>">
                          <option value=""></option>
                          <?php foreach($result as $employee) {?>
                          <option value="<?php echo $employee->user_id;?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif; ?>><?php echo $employee->first_name.' '.$employee->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                   <div style="background-color: #cbf3e6;padding: 10px;margin-bottom: 15px;">
                      Jadwal Kerja Harian :
                    </div>

                    <div class="form-group row">
                      <div class="col-md-12">
                         <div class="box-datatable table-responsive">
                         <table class="datatables-demo table table-striped table-bordered"  width="100%">
                          <thead>
                            <tr>
                              <th width="5%">Minggu </th>
                              <th width="5%" colspan="7">Tanggal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>1</th>
                              <?php foreach (range(1, 7) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(1, 7) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>" <?= $jam_shift->kode == ${"T{$key}"} ? 'selected' : '' ?>><?php echo $jam_shift->kode; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>2</th>
                              <?php foreach (range(8, 14) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(8, 14) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>" <?= $jam_shift->kode == ${"T{$key}"} ? 'selected' : '' ?>><?php echo $jam_shift->kode; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>3</th>
                              <?php foreach (range(15, 21) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(15, 21) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>" <?= $jam_shift->kode == ${"T{$key}"} ? 'selected' : '' ?>><?php echo $jam_shift->kode; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>3</th>
                              <?php foreach (range(22, 28) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(22, 28) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>" <?= $jam_shift->kode == ${"T{$key}"} ? 'selected' : '' ?>><?php echo $jam_shift->kode; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>3</th>
                              <?php foreach (range(29, 31) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                              <th colspan="4"></th>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(29, 31) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>" <?= $jam_shift->kode == ${"T{$key}"} ? 'selected' : '' ?>><?php echo $jam_shift->kode; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                              <td colspan="4"></td>
                            </tr>

                          </tbody>
                        </table>
                      </div>

                      </div>

                    </div>
                </div>

                </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update');?></button>
      </div>

      <?php echo form_close(); ?>

    <script type="text/javascript">

         $(document).ready(function(){

            jQuery("#ajx_company").change(function(){
                jQuery.get(base_url+"/get_employees_office/"+jQuery(this).val(), function(data, status){
                  jQuery('#employee_ajx').html(data);
                });
            });

            // Month & Year
            $('.attendance_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // maxDate: '0',
                dateFormat:'yy-mm-dd',
                altField: "#date_format",
                altFormat: js_date_format,
                yearRange: '1970:' + new Date().getFullYear(),
                beforeShow: function(input) {
                  $(input).datepicker("widget").show();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            /* Edit data */

            $("#edit_office_shift").submit(function(e){
                  /*Form Submit*/
                  e.preventDefault();

                  var obj = $(this), action = obj.attr('name');

                  $('.save').prop('disabled', true);

                  $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=3&edit_type=shift&form="+action,
                    cache: false,
                    success: function (JSON) {
                      if (JSON.error != '') {
                        alert_fail('Gagal',JSON.error);
                        $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                      } else {
                          $('.edit-modal-data-shift').modal('toggle');

                          var xin_table = $('#xin_table').dataTable({
                            "bDestroy": true,
                            "ajax": {
                              url : "<?php echo site_url("admin/pengaturan/office_shift_list") ?>",
                              type : 'GET'
                            },
                            "language": {
                                  "aria": {
                                      "sortAscending" : ": activate to sort column ascending",
                                      "sortDescending": ": activate to sort column descending"
                                  },
                                  "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                              "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                              "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                              "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                              "lengthMenu": "Tampilkan _MENU_ entri",
                              "loadingRecords": "Silahkan Tunggu...",
                              "processing": "Sedang memproses...",
                               "search"      : "Pencarian : ", "searchPlaceholder": "Masukan Kata Pencarian ...",
                              "zeroRecords": "Tidak ditemukan data yang sesuai",
                              "thousands": "'",
                              "paginate": {
                                  "first": "Pertama",
                                  "last": "Terakhir",
                                  "next": "Selanjutnya",
                                  "previous": "Sebelumnya"
                              },
                            },
                            dom: 'lBfrtip',
                            buttons: [
                              'print', {
                                extend: 'pdf',
                                orientation: 'landscape'
                              },
                              'excel'
                            ],
                            "fnDrawCallback": function(settings){
                            $('[data-toggle="tooltip"]').tooltip();
                            }
                          });

                          xin_table.api().ajax.reload(function(){
                            alert_success('Sukses',JSON.result);
                          }, true);

                          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                          $('.save').prop('disabled', false);
                      }
                    }
                  });
            });
        });

    </script>
<?php } ?>
