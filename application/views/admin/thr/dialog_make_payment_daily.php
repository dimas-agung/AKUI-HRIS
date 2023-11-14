<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payment' && $_GET['type'] == 'daily_payment') {

    $user_id = $thr->user_id;
    $employee_department_id = $thr->department_id;
    $employee_date_of_joining = $thr->join_date->format('Y-m-d');
    $employee_company_id = $thr->company_id;
    $employee_location_id = $thr->location_id;
    $employee_designation_id = $thr->designation_id;
    $employee_wages_type = $thr->wages_type;
    $basic_salary = $thr->basic_salary;
    $total_gaji = $thr->avg_salary;
    $total_net_salary = floor($thr->total_thr);
    $thr_type = $thr->count_salary > 12 ? 'THR PENUH' : ($thr->count_salary > 0 ? 'THR Prorate' : 'Tidak Dapat THR');
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-money"></i>
            Bayar Gaji Harian - Per Karyawan!
        </h4>
    </div>

    <div class="modal-body" style="overflow:auto; height:520px;">

        <?php $attributes = array('name' => 'pay_daily', 'id' => 'pay_daily', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
        <?php $hidden = array('_method' => 'ADD'); ?>
        <?php echo form_open('admin/thr/add_pay_daily/', $attributes, $hidden); ?>

        <input type="hidden" value="<?= $user_id ?>" name="emp_id" id="emp_id">
        <input type="hidden" value="<?= $employee_department_id ?>" name="employee_department_id" />
        <input type="hidden" value="<?= $employee_date_of_joining ?>" name="employee_date_of_joining" />
        <input type="hidden" value="<?= $employee_company_id ?>" name="employee_company_id" />
        <input type="hidden" value="<?= $employee_location_id ?>" name="employee_location_id" />
        <input type="hidden" value="<?= $employee_designation_id ?>" name="employee_designation_id" />
        <input type="hidden" value="<?= $employee_wages_type ?>" name="employee_wages_type" />
        <input type="hidden" value="<?= $basic_salary ?>" name="basic_salary">
        <input type="hidden" value="<?= $total_gaji ?>" name="total_gaji">
        <input type="hidden" value="<?= $total_net_salary ?>" name="total_net_salary">
        <input type="hidden" value="<?= $thr->bank_account_name ?>" name="rekening_name">
        <input type="hidden" value="<?= $thr->bank_account_number ?>" name="rekening_no">
        <input type="hidden" value="<?= $thr->bank_name ?>" name="bank_name">
        <input type="hidden" value="<?= $thr_type ?>" name="thr_type">
        <input type="hidden" value="<?= $thr->thr_date->format('Y') ?>" name="thr_year">
        <input type="hidden" value="<?= $thr->thr_date->format('Y-m-d') ?>" name="thr_date">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border box-hijau">
                        <h3 class="box-title" style="font-size: 16px; font-weight: bold;">
                            <?= $thr->full_name ?>
                        </h3>
                        <br>
                        <h5 class="box-title-sub">
                            <?= $designation->designation_name ?> - <?= $department->department_name ?>
                        </h5>
                        <br>
                        <h5 class="box-title-sub-sub">
                            THR Tahun : <?= $thr->thr_date->format('Y') ?>, Tanggal Batas THR : <?= $thr->thr_date->format('d-m-Y') ?>
                        </h5>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="datatables-demo table table-striped  dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td colspan="2" align="center"><strong><i class="fa fa-plus-circle"></i> Detail Tunjangan Hari Raya :</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong> Gaji Pokok </strong></td>
                                        <td>: <span class="pull-right"><?= number_format($basic_salary, 0, ',', '.'); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong> Mulai Bekerja </strong></td>
                                        <td>: <span class="pull-right"><?= $thr->join_date->format('d-m-Y') ?> - <?= $thr->years_of_service ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong> Info THR </strong></td>
                                        <td>: <span class="pull-right"><?= $thr_type ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong> Gaji Rata-rata </strong></td>
                                        <td>: <span class="pull-right"><?= number_format($total_gaji, 0, ',', '.'); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong> Total THR </strong></td>
                                        <td>: <span class="pull-right text-bold"> <?= number_format($total_net_salary, 0, ',', '.'); ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions box-footer">
            <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => $this->Core_model->form_button_close_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
            <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-save"></i> ' . $this->lang->line('xin_pay'))); ?>
        </div>
        <?php echo form_close(); ?>
    </div>

    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            // On page load: datatable
            $("#pay_daily").submit(function(e) {

                /*Form Submit*/
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');

                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=11&data=daily&add_type=add_daily_payment&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.emo_dayly_pay').modal('toggle');
                            $('#xin_table_harian').DataTable().ajax.reload();
                            alert_success('Sukses', JSON.result);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

<?php } ?>
