<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
if (isset($_GET['jd']) && isset($_GET['payslip_id']) && $_GET['data'] == 'payment' && $_GET['type'] == 'daily_payment_delete') {

    $thr_date = new DateTime($thr->tanggal_thr);
    $join_date = new DateTimeImmutable($thr->doj);
    $diff = $thr_date->diff($join_date);
    $years_of_service = str_replace("0 thn ", "", $diff->format('%y thn %m bln'));
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="delete-modal-data"> <i class="fa fa-trash"></i>
            Hapus THR Harian - Per Karyawan !
        </h4>
    </div>

    <div class="modal-body" style="overflow:auto; height:450px;">

        <?php $attributes = array('name' => 'del_pay_daily', 'id' => 'del_pay_daily', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
        <?php $hidden     = array('_method' => 'ADD'); ?>

        <?php echo form_open('admin/thr/del_pay_daily/', $attributes, $hidden); ?>

        <input type="hidden" value="<?= $thr->payslip_id ?>" name="payslip_id" id="payslip_id" />
        <input type="hidden" value="<?= $thr->tahun_thr ?>" name="tahun_thr" id="tahun_thr" />
        <input type="hidden" value="<?= $thr->tanggal_thr ?>" name="tanggal_thr" id="tanggal_thr" />
        <input type="hidden" value="<?= $thr->company_id ?>" name="company_id" id="company_id" />

        <div class="row">
            <div class="col-md-12">
                <div class="box">

                    <div class="box-header with-border box-merah">
                        <h3 class="box-title" style="font-size: 16px; font-weight: bold;">
                            <?= "{$thr->first_name} {$thr->last_name}"; ?>
                        </h3>
                        <br>
                        <h5 class="box-title-sub">
                            <?= $thr->designation_name; ?> - <?= $thr->department_name; ?>
                        </h5>
                        <br>
                        <h5 class="box-title-sub-sub">
                            THR Tahun : <?= $thr->tahun_thr; ?>, Tanggal Batas THR : <?= (new DateTime($thr->tanggal_thr))->format('d-m-Y'); ?>
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
                                        <td>: <span class="pull-right"><?= number_format($thr->basic_salary, 0, ',', '.'); ?></span></td>
                                    </tr>

                                    <tr>
                                        <td><strong> Mulai Bekerja </strong></td>
                                        <td>: <span class="pull-right"><?= $join_date->format("d-m-Y"); ?> - <?= $years_of_service; ?></span></td>
                                    </tr>

                                    <tr>
                                        <td><strong> Info THR </strong></td>
                                        <td>: <span class="pull-right"><?= $thr->note; ?></span></td>
                                    </tr>

                                    <tr>
                                        <td><strong> Total THR </strong></td>
                                        <td>: <span class="pull-right text-bold"> <?= number_format($thr->net_salary, 0, ',', '.'); ?></span></td>
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
            <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_delete_class(), 'content' => '<i class="fa fa-trash"></i> ' . $this->lang->line('xin_delete'))); ?>
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
            $("#del_pay_daily").submit(function(e) {

                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                //$('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=11&data=daily&add_type=del_daily_payment&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.del_dayly_pay').modal('toggle');
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
