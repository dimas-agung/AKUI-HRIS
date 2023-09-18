<?php

/**
 * INFORMASI
 *
 * Nama Aplikasi :  HRIS
 * Email Support :  hris@karyasoftware.com
 * Developer     :  Nizar Basyrewan, S.Si - 0895 606460 731
 * Tahun         :  2020
 * Copyright     :  ©karyasoftware.com. All Rights Reserved
 */
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- ==================================================================================================================================================== -->
<!-- START -->
<!-- ==================================================================================================================================================== -->

<!-- Jenis Kontrak -->
<?php if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_contract_type' && $_GET['type'] == 'ed_contract_type') { ?>

    <?php $row = $this->Core_model->read_contract_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_edit_contract_type'); ?></h4>
    </div>

    <?php $attributes = array('name' => 'ed_contract_type_info', 'id' => 'ed_contract_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $row[0]->contract_type_id, 'ext_name' => $row[0]->name); ?>
    <?php echo form_open('admin/settings/update_contract_type/' . $row[0]->contract_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_e_details_contract_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_contract_type'); ?>" value="<?php echo $row[0]->name ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update'); ?></button>
    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_contract_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=22&type=edit_record&data=ed_contract_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {

                            alert_fail('Gagal', JSON.error);

                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);

                        } else {

                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_contract_type = $('#xin_table_contract_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/contract_type_list") ?>",
                                    type: 'GET'
                                },
                                "columns": [{
                                        "name": "kolom_0",
                                        "orderable": false,
                                        "searchable": false,
                                        "className": "text-center",
                                        "width": "7%"
                                    },
                                    {
                                        "name": "kolom_1"
                                    }
                                ],
                                "language": {
                                    "aria": {
                                        "sortAscending": ": activate to sort column ascending",
                                        "sortDescending": ": activate to sort column descending"
                                    },
                                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                                    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                                    "lengthMenu": "Tampilkan _MENU_ entri",
                                    "loadingRecords": "Silahkan Tunggu...",
                                    "processing": "Sedang memproses...",
                                    "search": "Pencarian : ",
                                    "searchPlaceholder": "Masukan Kata Pencarian ...",
                                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                                    "thousands": "'",
                                    "paginate": {
                                        "first": "Pertama",
                                        "last": "Terakhir",
                                        "next": "Selanjutnya",
                                        "previous": "Sebelumnya"
                                    },
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_contract_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- Jenis Jenjang Pendidikan -->
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_education_level' && $_GET['type'] == 'ed_education_level') { ?>

    <?php $row = $this->Core_model->read_education_level($_GET['field_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_education_level'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'ed_education_level_info', 'id' => 'ed_education_level_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $row[0]->education_level_id, 'ext_name' => $row[0]->name); ?>
    <?php echo form_open('admin/settings/update_education_level/' . $row[0]->education_level_id, $attributes, $hidden); ?>

    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_e_details_edu_level'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_edu_level'); ?>" value="<?php echo $row[0]->name; ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_education_level_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=24&type=edit_record&data=ed_education_level_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_education_level = $('#xin_table_education_level').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/education_level_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_education_level.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- Jenis Pengalaman -->
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_qualification_skill' && $_GET['type'] == 'ed_qualification_skill') { ?>

    <?php $row = $this->Core_model->read_qualification_skill($_GET['field_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_skill'); ?></h4>
    </div>

    <?php $attributes = array('name' => 'ed_qualification_skill_info', 'id' => 'ed_qualification_skill_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->skill_id, 'ext_name' => $row[0]->name); ?>
    <?php echo form_open('admin/settings/update_qualification_skill/' . $row[0]->skill_id, $attributes, $hidden); ?>

    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_skill'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_skill'); ?>" value="<?php echo $row[0]->name; ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_qualification_skill_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=26&type=edit_record&data=ed_qualification_skill_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_qualification_skill = $('#xin_table_qualification_skill').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/qualification_skill_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_qualification_skill.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- ==================================================================================================================================================== -->
    <!-- KINERJA -->
    <!-- ==================================================================================================================================================== -->

    <!-- Jenis Penghargaan -->
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_award_type' && $_GET['type'] == 'ed_award_type') { ?>

    <?php $row = $this->Core_model->read_award_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Jenis Penghargaan</h4>
    </div>

    <?php $attributes = array('name' => 'ed_award_type_info', 'id' => 'ed_award_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->award_type_id, 'ext_name' => $row[0]->award_type); ?>
    <?php echo form_open('admin/settings/update_award_type/' . $row[0]->award_type_id, $attributes, $hidden); ?>

    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_award_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_award_type'); ?>" value="<?php echo $row[0]->award_type; ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_award_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=38&type=edit_record&data=ed_award_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_award_type = $('#xin_table_award_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/award_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_award_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_warning_type' && $_GET['type'] == 'ed_warning_type') { ?>

    <?php $row = $this->Core_model->read_warning_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_warning_type'); ?></h4>
    </div>

    <?php $attributes = array('name' => 'ed_warning_type_info', 'id' => 'ed_warning_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->warning_type_id, 'ext_name' => $row[0]->type); ?>
    <?php echo form_open('admin/settings/update_warning_type/' . $row[0]->warning_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_warning_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_warning_type'); ?>" value="<?php echo $row[0]->type; ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_warning_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=40&type=edit_record&data=ed_warning_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_warning_type = $('#xin_table_warning_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/warning_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_warning_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- ==================================================================================================================================================== -->
    <!-- JENIS AGAMA -->
    <!-- ==================================================================================================================================================== -->

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_ethnicity_type' && $_GET['type'] == 'ed_ethnicity_type') { ?>

    <?php $row = $this->Core_model->read_ethnicity_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_ethnicity_type'); ?></h4>
    </div>

    <?php $attributes = array('name' => 'ed_ethnicity_type_info', 'id' => 'ed_ethnicity_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->ethnicity_type_id, 'ext_name' => $row[0]->type); ?>
    <?php echo form_open('admin/settings/update_ethnicity_type/' . $row[0]->ethnicity_type_id, $attributes, $hidden); ?>

    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_ethnicity_type_title'); ?>:</label>
            <input type="text" class="form-control" name="ethnicity_type" placeholder="<?php echo $this->lang->line('xin_ethnicity_type_title'); ?>" value="<?php echo $row[0]->type ?>">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_ethnicity_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=22&type=edit_record&data=ed_ethnicity_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_ethnicity_type = $('#xin_table_ethnicity_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/ethnicity_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_ethnicity_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- ==================================================================================================================================================== -->
    <!-- JENIS PERJANJIAN -->
    <!-- ==================================================================================================================================================== -->

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_perjanjian_type' && $_GET['type'] == 'ed_perjanjian_type') { ?>

    <?php $row = $this->Core_model->read_perjanjian_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Jenis Perjanjian</h4>
    </div>

    <?php $attributes = array('name' => 'ed_perjanjian_type', 'id' => 'ed_perjanjian_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $row[0]->perjanjian_type_id, 'ext_name' => $row[0]->perjanjian_type_name); ?>
    <?php echo form_open('admin/settings/update_perjanjian_type/' . $row[0]->perjanjian_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label">Jenis Perjanjian </label>
            <input type="text" class="form-control" name="perjanjian_type_name" placeholder="Nama Perjanjian" value="<?php echo $row[0]->perjanjian_type_name ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_perjanjian_type").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=22&type=edit_record&data=ed_perjanjian_type&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_perjanjian_type = $('#xin_table_perjanjian_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/perjanjian_type") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_perjanjian_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>


    <!-- ==================================================================================================================================================== -->
    <!-- JENIS PERIZINAN -->
    <!-- ==================================================================================================================================================== -->

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_perizinan_type' && $_GET['type'] == 'ed_perizinan_type') { ?>

    <?php $row = $this->Core_model->read_perizinan_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Jenis Perizinan</h4>
    </div>

    <?php $attributes = array('name' => 'ed_perizinan_type', 'id' => 'ed_perizinan_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $row[0]->perizinan_type_id, 'ext_name' => $row[0]->perizinan_type_name); ?>
    <?php echo form_open('admin/settings/update_perizinan_type/' . $row[0]->perizinan_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label">Jenis Perizinan </label>
            <input type="text" class="form-control" name="perizinan_type_name" placeholder="Nama Perizinan" value="<?php echo $row[0]->perizinan_type_name ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_perizinan_type").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=22&type=edit_record&data=ed_perizinan_type&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_perizinan_type = $('#xin_table_perizinan_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/perizinan_type") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_perizinan_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>


    <!-- ==================================================================================================================================================== -->
    <!-- PENGAJUAN -->
    <!-- ==================================================================================================================================================== -->

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_leave_type' && $_GET['type'] == 'ed_leave_type') { ?>

    <?php $row = $this->Core_model->read_leave_type($_GET['field_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Jenis Cuti</h4>
    </div>

    <?php $attributes = array('name' => 'ed_leave_type_info', 'id' => 'ed_leave_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->leave_type_id, 'ext_name' => $row[0]->type_name); ?>
    <?php echo form_open('admin/settings/update_leave_type/' . $row[0]->leave_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_leave_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>" value="<?php echo $row[0]->type_name; ?>">
        </div>
        <div class="form-group">
            <label for="days_per_year" class="form-control-label"><?php echo $this->lang->line('xin_days_per_year'); ?>:</label>
            <input type="text" class="form-control" name="days_per_year" placeholder="<?php echo $this->lang->line('xin_days_per_year'); ?>" value="<?php echo $row[0]->days_per_year ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_leave_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=39&type=edit_record&data=ed_leave_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_leave_type = $('#xin_table_leave_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/leave_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_leave_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_sick_type' && $_GET['type'] == 'ed_sick_type') { ?>

    <?php $row = $this->Core_model->read_sick_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Jenis Sakit</h4>
    </div>

    <?php $attributes = array('name' => 'ed_sick_type_info', 'id' => 'ed_sick_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>

    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->sick_type_id, 'ext_name' => $row[0]->type_name); ?>

    <?php echo form_open('admin/settings/update_sick_type/' . $row[0]->sick_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_sick_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_sick_type'); ?>" value="<?php echo $row[0]->type_name; ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            /* Edit data */

            $("#ed_sick_type_info").submit(function(e) {

                /* Form Submit */
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=39&type=edit_record&data=ed_sick_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_sick_type = $('#xin_table_sick_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/sick_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_sick_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_izin_type' && $_GET['type'] == 'ed_izin_type') { ?>

    <?php $row = $this->Core_model->read_izin_type($_GET['field_id']);     ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Jenis Izin</h4>
    </div>

    <?php $attributes = array('name' => 'ed_izin_type_info', 'id' => 'ed_izin_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>

    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->izin_type_id, 'ext_name' => $row[0]->type_name); ?>

    <?php echo form_open('admin/settings/update_izin_type/' . $row[0]->izin_type_id, $attributes, $hidden); ?>

    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_izin_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_izin_type'); ?>" value="<?php echo $row[0]->type_name; ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });

            /* Edit data */

            $("#ed_izin_type_info").submit(function(e) {

                /* Form Submit */
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=39&type=edit_record&data=ed_izin_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_izin_type = $('#xin_table_izin_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/izin_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_izin_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_exit_type' && $_GET['type'] == 'ed_exit_type') { ?>

    <?php $row = $this->Core_model->read_exit_type($_GET['field_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">
            <i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_edit_employee_exit_type'); ?>
        </h4>
    </div>
    <?php $attributes = array('name' => 'ed_exit_type_info', 'id' => 'ed_exit_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->exit_type_id, 'ext_name' => $row[0]->type); ?>
    <?php echo form_open('admin/settings/update_exit_type/' . $row[0]->exit_type_id, $attributes, $hidden); ?>
    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_employee_exit_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_employee_exit_type'); ?>" value="<?php echo $row[0]->type; ?>">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_exit_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=44&type=edit_record&data=ed_exit_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_exit_type = $('#xin_table_exit_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/exit_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_exit_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>


<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_exit_type_reason' && $_GET['type'] == 'ed_exit_type_reason') { ?>

    <?php $row = $this->Core_model->read_exit_type_reason($_GET['field_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_edit_employee_exit_type_reason'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'ed_exit_type_reason_info', 'id' => 'ed_exit_type_reason_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->exit_type_id, 'ext_name' => $row[0]->type); ?>
    <?php echo form_open('admin/settings/update_exit_type_reason/' . $row[0]->exit_type_id, $attributes, $hidden); ?>
    <div class="modal-body">

        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_employee_exit_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_employee_exit_type_reason'); ?>" value="<?php echo $row[0]->type; ?>">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_exit_type_reason_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=44&type=edit_record_reason&data=ed_exit_type_reason_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_exit_type_reason = $('#xin_table_exit_type_reason').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/exit_type_reason_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_exit_type_reason.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- ==================================================================================================================================================== -->
    <!-- DINAS -->
    <!-- ==================================================================================================================================================== -->

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_travel_arr_type' && $_GET['type'] == 'ed_travel_arr_type') { ?>

    <?php $row = $this->Core_model->read_travel_arr_type($_GET['field_id']); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_travel_arr_type'); ?></h4>
    </div>

    <?php $attributes = array('name' => 'ed_travel_arr_type_info', 'id' => 'ed_travel_arr_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->arrangement_type_id, 'ext_name' => $row[0]->type); ?>
    <?php echo form_open('admin/settings/update_travel_arr_type/' . $row[0]->arrangement_type_id, $attributes, $hidden); ?>

    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="form-control-label"><?php echo $this->lang->line('xin_travel_arrangement_type'); ?>:</label>
            <input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_travel_arrangement_type'); ?>" value="<?php echo $row[0]->type; ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_travel_arr_type_info").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&is_ajax=46&type=edit_record&data=ed_travel_arr_type_info&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_travel_arr_type = $('#xin_table_travel_arr_type').dataTable({
                                "bDestroy": true,
                                "bFilter": false,
                                "iDisplayLength": 5,
                                "aLengthMenu": [
                                    [5, 10, 30, 50, 100, -1],
                                    [5, 10, 30, 50, 100, "All"]
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("admin/settings/travel_arr_type_list") ?>",
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
                            xin_table_travel_arr_type.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>

    <!-- ==================================================================================================================================================== -->
    <!-- END -->
    <!-- ==================================================================================================================================================== -->

    <!-- ==================================================================================================================================================== -->
    <!-- TAHUN PENGGAJIAN -->
    <!-- ==================================================================================================================================================== -->

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_payroll_year' && $_GET['type'] == 'ed_payroll_year') { ?>

    <?php $row = $this->Core_model->read_payroll_year($_GET['field_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_education_level'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'ed_payroll_year', 'id' => 'ed_payroll_year', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $row->payroll_id); ?>
    <?php echo form_open('admin/settings/update_payroll_year/' . $row->payroll_id, $attributes, $hidden); ?>

    <div class="modal-body">
        <div class="form-group">
            <label for="name" class="form-control-label">Tahun :</label>
            <input type="number" class="form-control" name="year" placeholder="Tahun" value="<?php echo $row->tahun; ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
    </div>

    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            /* Edit data */
            $("#ed_payroll_year").submit(function(e) {
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize() + "&type=edit_record&data=ed_payroll_year&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            alert_fail('Gagal', JSON.error);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit_setting_datail').modal('toggle');
                            // On page load: datatable
                            var xin_table_payroll_year = $('#xin_table_payroll_year').dataTable();
                            xin_table_payroll_year.api().ajax.reload(function() {
                                alert_success('Sukses', JSON.result);
                            }, true);
                            $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
    <!-- ==================================================================================================================================================== -->
    <!-- END -->
    <!-- ==================================================================================================================================================== -->

<?php } ?>
