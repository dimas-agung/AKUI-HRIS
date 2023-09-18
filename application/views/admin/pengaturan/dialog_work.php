<?php
defined('BASEPATH') OR exit('No direct script access allowed');  ?>

<?php if(isset($_GET['jd']) && isset($_GET['payroll_id']) && $_GET['data']=='view_work'){ ?>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-eye"></i> <?php echo $this->lang->line('xin_view_work');?></h4>
    </div>
    <form class="m-b-1">
    <div class="modal-body">
      <table class="footable-details table table-striped table-hover toggle-circle">
        <tbody>
          
          <tr>
            <th><?php echo $this->lang->line('xin_start_date');?></th>
            <td style="display: table-cell;">: <?php echo $this->Core_model->set_date_format($start_date);?></td>
          </tr>
          
          <tr>
            <th><?php echo $this->lang->line('xin_end_date');?></th>
            <td style="display: table-cell;">: <?php echo $this->Core_model->set_date_format($end_date);?></td>
          </tr>
          
          <tr>
            <th> Bulan</th>
            <td style="display: table-cell;">: <?php echo $bulan;?></td>
          </tr>
          <tr>
            <th> Tahun </th>
            <td style="display: table-cell;">: <?php echo $tahun;?></td>
          </tr>

          <tr>
            <th> Nama Bulan Kerja</th>
            <td style="display: table-cell;">: <?php echo $desc;?></td>
          </tr>
          <tr>
            <th> Keterangan</th>
            <td style="display: table-cell;">: <?php echo html_entity_decode($description);?></td>
          </tr>

          <tr>
            <th><?php echo $this->lang->line('dashboard_xin_status');?> Aktif </th>
            <td style="display: table-cell;">: <?php if($is_publish=='1'): $status = $this->lang->line('xin_published');?>
              <?php endif; ?>
              <?php if($is_publish=='0'): $status = $this->lang->line('xin_unpublished');?>
              <?php endif; ?>
              <?php echo $status;?></td>
          </tr>

          <tr>
            <th><?php echo $this->lang->line('dashboard_xin_status');?> Payroll </th>
            <td style="display: table-cell;">: <?php if($is_payroll=='1'): $status = 'Release';?>
              <?php endif; ?>
              <?php if($is_payroll=='0'): $status = 'Draft';?>
              <?php endif; ?>
              <?php echo $status;?></td>
          </tr>

          <tr>
            <th><?php echo $this->lang->line('dashboard_xin_status');?> Laporan</th>
            <td style="display: table-cell;">: <?php if($is_recap=='1'): $status = $this->lang->line('xin_published');?>
              <?php endif; ?>
              <?php if($is_recap=='0'): $status = $this->lang->line('xin_unpublished');?>
              <?php endif; ?>
              <?php echo $status;?></td>
          </tr>
         
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
    </div>
    <?php echo form_close(); ?>

<?php } else if(isset($_GET['jd']) && isset($_GET['payroll_id']) && $_GET['data']=='work'){ ?>
   
    <?php $session = $this->session->userdata('username');?>
    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_edit_work');?></h4>
    </div>
    <?php $attributes = array('name' => 'edit_work', 'id' => 'edit_work', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $payroll_id, 'ext_name' => $payroll_id);?>
    <?php echo form_open('admin/pengaturan/edit_work/'.$payroll_id, $attributes, $hidden);?>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-6">
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                <input class="form-control mdate" name="start_date" readonly="true" type="text" value="<?php echo $start_date;?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                <input class="form-control mdate" name="end_date" readonly="true" type="text" value="<?php echo $end_date;?>">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <?php $all_bulan = $this->Core_model->all_bulan();?>
              <div class="form-group">
                <label for="bulan" class="control-label"> Bulan </label>
                <select class="form-control" name="bulan" data-plugin="select_hrm" data-placeholder="Bulan">
                  <option value=""></option>
                  <?php foreach($all_bulan as $bulan_kerja) {?>
                    <option value="<?php echo $bulan_kerja->id?>" <?php if($bulan_kerja->id==$bulan):?> selected="selected"<?php endif;?>><?php echo $bulan_kerja->bulan_nama?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <?php $all_tahun = $this->Core_model->all_tahun();?>
              <div class="form-group">
                <label for="tahun" class="control-label"> Tahun </label>
                <select class="form-control" name="tahun" data-plugin="select_hrm" data-placeholder="Tahun">
                  <option value=""></option>
                  <?php foreach($all_tahun as $tahun_kerja) {?>
                    <option value="<?php echo $tahun_kerja->tahun_nama?>" <?php if($tahun_kerja->tahun_nama==$tahun):?> selected="selected"<?php endif;?>><?php echo $tahun_kerja->tahun_nama?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="description">Nama Bulan Kerja </label>
                <input type="text" class="form-control" name="desc" placeholder="Nama Bulan Kerja" value="<?php echo $desc;?>">          
              </div>
            </div>
          </div>

        </div>

        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="description">Keterangan</label>
                <textarea class="form-control textarea" placeholder="Keterangan" name="description" cols="30" rows="6" id="description2"><?php echo $description;?></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="is_publish" class="control-label"><?php echo $this->lang->line('dashboard_xin_status');?> Release</label>
                <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_status');?>">
                  <option value="1" <?php if($is_publish=='1') { ?> selected <?php } ?>><?php echo $this->lang->line('xin_published');?></option>
                  <option value="0" <?php if($is_publish=='0') { ?> selected <?php } ?>><?php echo $this->lang->line('xin_unpublished');?></option>
                </select>
              </div>
            </div>
             <div class="col-md-4">
              <div class="form-group">
                <label for="is_payroll" class="control-label"><?php echo $this->lang->line('dashboard_xin_status');?> Release</label>
                <select name="is_payroll" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_status');?>">
                  <option value="1" <?php if($is_payroll=='1') { ?> selected <?php } ?>> Release </option>
                  <option value="0" <?php if($is_payroll=='0') { ?> selected <?php } ?>> Draft </option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="is_recap" class="control-label"><?php echo $this->lang->line('dashboard_xin_status');?> Recap</label>
                <select name="is_recap" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_status');?>">
                  <option value="1" <?php if($is_recap=='1') { ?> selected <?php } ?>><?php echo $this->lang->line('xin_published');?></option>
                  <option value="0" <?php if($is_recap=='0') { ?> selected <?php } ?>><?php echo $this->lang->line('xin_unpublished');?></option>
                </select>
              </div>
            </div>
          </div>

          
        </div>

      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
      <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update');?></button>
    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
      $(document).ready(function(){
    	
        	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        	$('[data-plugin="select_hrm"]').select2({ width:'100%' });	
        	
          // Date
        	$('.mdate').datepicker({
          	  changeMonth: true,
          	  changeYear: true,
          	  dateFormat:'yy-mm-dd',
          	  yearRange: '1900:' + new Date().getFullYear()
        	});
        	
          /* Edit*/
        	$("#edit_work").submit(function(e){
          	  /*Form Submit*/
          	  e.preventDefault();
          		var obj = $(this), action = obj.attr('name');
          		$('.save').prop('disabled', true);
          		$.ajax({
          			type: "POST",
          			url: e.target.action,
          			data: obj.serialize()+"&is_ajax=2&edit_type=work&form="+action,
          			cache: false,
          			success: function (JSON) {
          				if (JSON.error != '') {
          					alert_fail('Gagal',JSON.error);
          					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
          					$('.save').prop('disabled', false);
          				} else {
          					$('.edit-modal-data').modal('toggle');
          					var xin_table = $('#xin_table').dataTable({
          						"bDestroy": true,
          						"ajax": {
          							url : "<?php echo site_url("admin/pengaturan/works_list") ?>",
          							type : 'GET'
          						},
          						"columns": [
                        {"name": "kolom_1", "className": "text-center"},
                        {"name": "kolom_2", "className": "text-center"},
                        {"name": "kolom_3", "className": "text-center"},
                        {"name": "kolom_4", "className": "text-center"},
                        {"name": "kolom_5", "className": "text-center"},
                        {"name": "kolom_6", "className": "text-left"},
                        {"name": "kolom_7", "className": "text-left"},
                        {"name": "kolom_8", "className": "text-center"},
                        {"name": "kolom_9", "className": "text-center"},
                        {"name": "kolom_10", "className": "text-center"},
                        
                        ],
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
          						//// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
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
