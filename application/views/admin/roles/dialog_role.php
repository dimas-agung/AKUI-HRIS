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
 
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['role_id']) && $_GET['data']=='role'){
	$role_resources_ids = explode(',',$role_resources);
?>

<div class="modal-header">
  <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
  <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_role_editrole');?></h4>
</div>

<?php $attributes = array('name' => 'edit_role', 'id' => 'edit_role', 'autocomplete' => 'off','class' => '"m-b-1');?>
<?php $hidden     = array('_method' => 'EDIT', 'ext_name' => $role_name, '_token' => $role_id);?>
<?php echo form_open('admin/roles/update/'.$role_id, $attributes, $hidden);?>

  	<div class="modal-body">
	    <div class="row">
	        <div class="col-md-4">
		        <div class="row">
		          <div class="col-md-12">
		            <div class="form-group">
		              <label for="role_name"><?php echo $this->lang->line('xin_role_name');?></label>
		              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_role_name');?>" name="role_name" type="text" value="<?php echo $role_name;?>">
		            </div>
		          </div>
		        </div>
		        <div class="row">
		        	<input type="checkbox" name="role_resources[]" value="0" checked style="display:none;"/>
		          <div class="col-md-12">
		            <div class="form-group">
		              <label for="role_access"><?php echo $this->lang->line('xin_role_access');?></label>
		              <select class="form-control custom-select" id="role_access_modal" name="role_access" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_role_access');?>">
		                <option value="">&nbsp;</option>
		                <option value="1" <?php if($role_access==1):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_role_all_menu');?></option>
		                <option value="2" <?php if($role_access==2):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_role_cmenu');?></option>
		              </select>
		            </div>
		          </div>
		        </div>
		        <div class="row">
		            <div class="col-md-12">
		            <p><strong><?php echo $this->lang->line('xin_role_note_title');?></strong></p>
		            <p><?php echo $this->lang->line('xin_role_note1');?></p>
		            <p><?php echo $this->lang->line('xin_role_note2');?></p>
		            <p><?php echo $this->lang->line('xin_role_note3');?></p>
		            </div>
		          </div>
		    </div>
	      
	      	<div class="col-md-4">
		        <div class="row">
		          <div class="col-md-12">
		            <div class="form-group">
		              <label for="resources"><?php echo $this->lang->line('xin_role_resource');?></label>
		              <div id="all_resources">
		                <div class="demo-section k-content">
		                  <div>
		                    <div id="treeview_m1"></div>
		                  </div>
		                </div>
		              </div>
		            </div>
		          </div>
		        </div>
		    </div>

		    <div class="col-md-4">
		        <div class="row">
		          <div class="col-md-12">
		            <div class="form-group">
		            	<label for="resources"></label>
		              <div id="all_resources">
		                <div class="demo-section k-content">
		                  <div>
		                    <div id="treeview_m2"></div>
		                  </div>
		                </div>
		              </div>
		            </div>
		          </div>
		        </div>
		    </div>

	    </div>
	</div>
  
	<div class="modal-footer">
	    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
	    <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa-save"></i> '.$this->lang->line('xin_update'))); ?> 
	</div>

<?php echo form_close(); ?>

<script type="text/javascript">

 	$(document).ready(function(){
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass   : 'iradio_minimal-blue'
		});

		/* Edit data */
		$("#edit_role").submit(function(e){
			e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=role&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					} else {
						// On page load: datatable
						var xin_table = $('#xin_table').dataTable({
							"bDestroy": true,
							"ajax": {
								url : "<?php echo site_url("admin/roles/role_list") ?>",
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
							"fnDrawCallback": function(settings){
							$('[data-toggle="tooltip"]').tooltip();          
							}
						});
						xin_table.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
</script>

<script>

jQuery("#treeview_m1").kendoTreeView({
checkboxes: {
checkChildren: true,
//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"*/

template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"

},

check: onCheck,
dataSource: [
	
		// ======================================================================================================== 
	    // 01. SETTING DATA -> OK
	    // ========================================================================================================

			{ value: "0100", id: "", class: "role-checkbox-modal", text: "01. Data",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  
				
				items: [
					{ value: "0101",  id: "", class: "role-checkbox-modal", text: "01. Master Data",  add_info: "Master Data",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0101',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
					{ value: "0102",  id: "", class: "role-checkbox-modal", text: "02. Backup Data",  add_info: "Backup Data",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0102',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					{ value: "0103", id: "", class: "role-checkbox-modal", text: "03. Import Produktifitas ",       add_info: "Import Gram", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0103',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "01031", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('01031',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "01032", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('01032',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "01033", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('01033',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "01034", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('01034',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},
					{ value: "0104", id: "", class: "role-checkbox-modal", text: "03. Rekap Produktifitas ",       add_info: "Import Gram", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0104',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "01041", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('01041',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "01042", id: "", class: "role-checkbox-modal", text: "Rekap",    add_info: "Rekap",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('01042',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								
						]
					},
				]
			},

		// ======================================================================================================== 
	    // 02. PERUSAHAAN -> 0K
	    // ========================================================================================================

			{ value:"0200", id: "", class: "role-checkbox-modal", text: "02. Organisasi", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0200',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "",  

				items: [

					{ value: "0200",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0200',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
										
					{ value: "0210", id: "", class: "role-checkbox-modal", text: "01. Perusahaan",  add_info: "Perusahaan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0210',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0211", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0211',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0212", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0212',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0220", id: "", class: "role-checkbox-modal", text: "02. Lokasi Kantor",  add_info: "Lokasi Kantor", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0220',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0221", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0221',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0222", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0222',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0223", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0223',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0224", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0224',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0230", id: "", class: "role-checkbox-modal", text: "03. Departemen",  add_info: "Departemen",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0230',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0231", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0231',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0232", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0232',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0233", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0233',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0234", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0234',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},

					{ value: "0240", id: "", class: "role-checkbox-modal", text: "04. Posisi",  add_info: "Posisi",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0240',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0241", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0241',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0242", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0242',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0243", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0243',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0244", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0244',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								
						]
					},

					{ value: "0250", id: "", class: "role-checkbox-modal", text: "05. Workstation",       add_info: "Workstation", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0250',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0251", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0251',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0252", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0252',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0253", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0253',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0254", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0254',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					
						
				]
			}, 

		// ======================================================================================================== 
	    // 03. REKRUTMEN - OK 
	    // ========================================================================================================
		
			{ value: "0300", id: "", class: "role-checkbox-modal", text: "03. Rekrutmen",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0300',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  

				items: [

					{ value: "0300",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0300',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
					
					{ value: "0310", id: "", class: "role-checkbox-modal", text: "01. Karyawan Baru",  add_info: "Karyawan Baru", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0310',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0311", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0311',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0312", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0312',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0313", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0313',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0314", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0314',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					}			
				]
			},

		// ======================================================================================================== 
	    // 04. KARYAWAN - OK
	    // ========================================================================================================

			{ value: "0500", id: "", class: "role-checkbox-modal", text: "05. Karyawan",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0500',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  

				items: [
					{ value: "0500",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0500',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
					
					{ value: "0510", id: "", class: "role-checkbox-modal", text: "01. Karyawan Aktif",  add_info: "Karyawan Aktif", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0510',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0511", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0511',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses						
							{ value: "0513", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0513',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0514", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0514',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},												
						]
					},
					{ value: "0520",  id: "", class: "role-checkbox-modal", text: "02. Karyawan Resign",  add_info: "Karyawan Resign", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0520',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					
					{ value: "0530",  id: "", class: "role-checkbox-modal", text: "02. Karyawan History",  add_info: "Karyawan History", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0530',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					
					{ value: "0540",  id: "", class: "role-checkbox-modal", text: "03. Info Login",       add_info: "Info Login",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0540',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
				]
			},

		// ======================================================================================================== 
	    // 05. LEGAL - OK
	    // ========================================================================================================
		
			{ value: "0400", id: "", class: "role-checkbox-modal", text: "04. GA & Legal",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0400',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  

				items: [

					{ value: "0400",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0400',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
					
					{ value: "0410", id: "", class: "role-checkbox-modal", text: "01. Kontrak ",  add_info: "Kontrak Karyawan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0410',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0411", id: "", class: "role-checkbox-modal", text: "Aktifkan",   add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0411',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0412", id: "", class: "role-checkbox-modal", text: "Tambah",     add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0412',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0413", id: "", class: "role-checkbox-modal", text: "Hapus",      add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0413',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},

					{ value: "0420", id: "", class: "role-checkbox-modal", text: "02. Instansi ",  add_info: "Instansi Perusahaan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0420',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0421", id: "", class: "role-checkbox-modal", text: "Aktifkan",   add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0421',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0422", id: "", class: "role-checkbox-modal", text: "Tambah",     add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0422',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0423", id: "", class: "role-checkbox-modal", text: "Hapus",      add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0423',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},


					{ value: "0430", id: "", class: "role-checkbox-modal", text: "03. Perizinan",  add_info: "Legalitas Perizinan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0430',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0431", id: "", class: "role-checkbox-modal", text: "Aktifkan",   add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0431',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0432", id: "", class: "role-checkbox-modal", text: "Tambah",     add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0432',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0433", id: "", class: "role-checkbox-modal", text: "Hapus",      add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0433',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},

					{ value: "0440", id: "", class: "role-checkbox-modal", text: "04. Perjanjian ",  add_info: "Perjanjian Perusahaan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0440',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0441", id: "", class: "role-checkbox-modal", text: "Aktifkan",   add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0441',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0442", id: "", class: "role-checkbox-modal", text: "Tambah",     add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0442',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0443", id: "", class: "role-checkbox-modal", text: "Hapus",      add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0443',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},		
				]
			},		

		// ======================================================================================================== 
	    // 06. PERSONALIA - OK
	    // ========================================================================================================

			{ value: "0600", id: "", class: "role-checkbox-modal", text: "06. Personalia",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0600',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  

				items: [

					{ value: "0600",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0600',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
					
					{ value: "0610", id: "", class: "role-checkbox-modal", text: "01. Exit Clearence", add_info: "Exit Clearence",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0610',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
							{ value: "0611",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0611',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0612", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0612',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0613", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0613',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0614", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0614',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0615", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0615',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							
						]
					},

					{ value: "0620", id: "", class: "role-checkbox-modal", text: "02. Perhargaan",  add_info: "Penghargaan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0620',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

						items: [
							{ value: "0621", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0621',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0622", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0622',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0623", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0623',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0624", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0624',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0625", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0625',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]	
					},

					{ value: "0630", id: "", class: "role-checkbox-modal", text: "03. Peringatan",  add_info: "Peringatan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0630',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

						items: [
							{ value: "0631",   id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0631',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0632", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0632',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0633", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0633',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0634", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0634',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0635", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0635',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0640", id: "", class: "role-checkbox-modal", text: "04. Dinas",  add_info: "Dinas",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0640',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

						items: [
								{ value: "0641",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0641',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0642",  id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0642',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0643",  id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0643',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0644",  id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0644',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
								{ value: "0645",  id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0645',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]								
					},

					{ value: "0650", id: "", class: "role-checkbox-modal", text: "05. Mutasi",  add_info: "Mutasi",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0650',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

						items: [
							{ value: "0651",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  	add_info: "Aktifkan",  	check: "<?php if(isset($_GET['role_id'])) { if(in_array('0651',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							// Proses
							{ value: "0652", id: "", class: "role-checkbox-modal", text: "Tambah",  	add_info: "Tambah",  	check: "<?php if(isset($_GET['role_id'])) { if(in_array('0652',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0653", id: "", class: "role-checkbox-modal", text: "Edit",  	    add_info: "Tambah",  	check: "<?php if(isset($_GET['role_id'])) { if(in_array('0653',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0654", id: "", class: "role-checkbox-modal", text: "Hapus",       add_info: "Hapus",  	check: "<?php if(isset($_GET['role_id'])) { if(in_array('0654',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							{ value: "0655",  id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0655',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},							
						]
					},
					
					{ value: "0660", id: "", class: "role-checkbox-modal", text: "06. Promosi",  add_info: "Promosi",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0660',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

						items: [
								{ value: "0661",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0661',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0662", id: "", class: "role-checkbox-modal", text: "Tambah",     add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0662',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0663", id: "", class: "role-checkbox-modal", text: "Edit",       add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0663',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0664", id: "", class: "role-checkbox-modal", text: "Hapus",      add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0664',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
								{ value: "0665", id: "", class: "role-checkbox-modal", text: "Lihat",      add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0665',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
						]
					},

					{ value: "0670", id: "", class: "role-checkbox-modal", text: "07. Demosi",  add_info: "Demosi",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0670',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

						items: [
								{ value: "0671",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0671',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0672", id: "", class: "role-checkbox-modal", text: "Tambah",     add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0672',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0673", id: "", class: "role-checkbox-modal", text: "Edit",       add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0673',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0674", id: "", class: "role-checkbox-modal", text: "Hapus",      add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0674',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
								{ value: "0675", id: "", class: "role-checkbox-modal", text: "Lihat",      add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0675',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
						]
					},

					{ value: "0680", id: "", class: "role-checkbox-modal", text: "08. Lembur Bulanan",  add_info: "Lembur",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0680',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						
						items: [
								{ value: "0681", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0681',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0682", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0682',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0683", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0683',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0684", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0684',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0685", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0685',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
						]
					},

					{ value: "0690", id: "", class: "role-checkbox-modal", text: "09. Lembur Harian",  add_info: "Lembur",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0690',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						
						items: [
								{ value: "0691", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0691',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0692", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0692',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0693", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0693',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0694", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0694',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0695", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0695',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},								
						]
					}
				]
			},

		// ======================================================================================================== 
	    // 07. PENGAJUAN - OK
	    // ========================================================================================================
		
			{ value: "0700", id: "", class: "role-checkbox-modal", text: "07. Pengajuan",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0700',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",   

				items: [

					{ value: "0700",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0700',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			

					{ value: "0710", id: "", class: "role-checkbox-modal", text: "01. Cuti",  add_info: "Cuti",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0710',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

							items: [						
									{ value: "0711",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('0711',$role_resources_ids)):  echo 'checked'; else: echo ''; endif; }?>"},
									// Proses
									{ value: "0712",  id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0712',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0713",  id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",       check: "<?php if(isset($_GET['role_id'])) { if(in_array('0713',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0714",  id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0714',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							    	{ value: "0715",  id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0715',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									
									{ value: "0716",  id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_1st_level_approval');?>",  add_info: "Lihat", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0716',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0717",  id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_2nd_level_approval');?>",  add_info: "Lihat", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0717',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
							
							]
					},
					
					{ value: "0720", id: "", class: "role-checkbox-modal", text: "02. Sakit",  add_info: "Sakit",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0720',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

							items: [						
									{ value: "0721", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0721',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									// Proses
									{ value: "0722", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0722',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0723", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0723',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0724", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0724',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0725", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0725',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
							
									{ value: "0726", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_1st_level_approval');?>",  add_info: "Lihat",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0726',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0727", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_2nd_level_approval');?>",  add_info: "Lihat",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0727',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
							
							]
					},

					{ value: "0730", id: "", class: "role-checkbox-modal", text: "03. Izin",  add_info: "Izin",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0730',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

							items: [						
									{ value: "0731", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0731',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									// Proses
									{ value: "0732", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0732',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0733", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0733',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0734", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0734',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0735", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0735',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									
									{ value: "0736", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_1st_level_approval');?>",  add_info: "Lihat",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0736',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0737", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_2nd_level_approval');?>",  add_info: "Lihat",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0737',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
							
							]
					},

					{ value: "0740", id: "", class: "role-checkbox-modal", text: "04. Resign",  add_info: "Resign",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0740',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

							items: [
									{value: "0741", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0741',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									// Proses
									{value: "0742", id: "", class: "role-checkbox-modal", text: "Tambah",  	add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0742',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{value: "0743", id: "", class: "role-checkbox-modal", text: "Edit",  	add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0743',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{value: "0744", id: "", class: "role-checkbox-modal", text: "Hapus",  	add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0744',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{value: "0745", id: "", class: "role-checkbox-modal", text: "Lihat",  	add_info: "Lihat",	   check: "<?php if(isset($_GET['role_id'])) { if(in_array('0745',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
							]
					},	

					{ value: "0750", id: "", class: "role-checkbox-modal", text: "05. Libur",  add_info: "Libur",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0750',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",

							items: [						
									{ value: "0751", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0751',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									// Proses
									{ value: "0752", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0752',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0753", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0753',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0754", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0754',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0755", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0755',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									
									{ value: "0756", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_1st_level_approval');?>",  add_info: "Lihat",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0756',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
									{ value: "0757", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_2nd_level_approval');?>",  add_info: "Lihat",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0757',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
							
							]
					},					
				]
			},

		// ======================================================================================================== 
	    // 08. PENGATURAN - OK
	    // ========================================================================================================

			{ value: "0800", id: "", class: "role-checkbox-modal", text: "08. Pengaturan",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0800',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",   
				items: [	
					{ value: "0800",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0800',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
						
					{ value: "0810", id: "", class: "role-checkbox-modal", text: "01. Atur Jadwal Reguler",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0810',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0811",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0811',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0812",  id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0812',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0813",  id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0813',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0814",  id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0814',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0815",  id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0815',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0820", id: "", class: "role-checkbox-modal", text: "02. Atur Jadwal Shift",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0820',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0821",   id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0821',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0822",   id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0822',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0823",   id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0823',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0824",   id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0824',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},									
								{ value: "0825",   id: "", class: "role-checkbox-modal", text: "Jam Shift",     add_info: "Jam Shift",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0825',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0830", id: "", class: "role-checkbox-modal", text: "03. Atur Jam Shift",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0830',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0831",   id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0831',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0832",   id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0832',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0833",   id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0833',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0834",   id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0834',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},									
								{ value: "0835",   id: "", class: "role-checkbox-modal", text: "Jam Shift",     add_info: "Jam Shift",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0835',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0840", id: "", class: "role-checkbox-modal", text: "04. Atur Hari Libur",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0840',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0841",   id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0841',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0842",   id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0842',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0843",   id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0843',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0844",   id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0844',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0845",   id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0845',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},

					{ value: "0850", id: "", class: "role-checkbox-modal", text: "05. Atur Bulan Kerja",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0850',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0851",   id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0851',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0852",   id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0852',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0853",   id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0853',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0854",   id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0854',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0855",   id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0855',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},

					{ value: "0870", id: "", class: "role-checkbox-modal", text: "06. Atur Periode Kerja",  add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0870',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
						items: [
								{ value: "0871",   id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0871',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0872",   id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0872',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0873",   id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0873',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0874",   id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0874',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0875",   id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0875',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						]
					},	

					{ value: "0860", id: "", class: "role-checkbox-modal", text: "07. Atur Skala Upah",       add_info: "Skala Upah", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0860',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0861", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0861',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// Proses
								{ value: "0862", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('0862',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0863", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('0863',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								{ value: "0864", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('0864',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},


				]
			},

		// ======================================================================================================== 
	    // 09. KEHADIRAN - OK
	    // ========================================================================================================

			{ value: "0900", id: "", class: "role-checkbox-modal", text: "09. Kehadiran",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('0900',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",   
				items: [					
					{ value: "0900",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0900',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
						
					{ value: "0910", id: "", class: "role-checkbox-modal", text: "01. Tarik Absensi Reguler",  add_info: "<?php echo $this->lang->line('xin_view');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0910',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0911", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0911',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0912", id: "", class: "role-checkbox-modal", text: "Tarik",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0912',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0920", id: "", class: "role-checkbox-modal", text: "02. Tarik Absensi Shift",  add_info: "<?php echo $this->lang->line('xin_view');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0920',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0921", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0921',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0922", id: "", class: "role-checkbox-modal", text: "Tarik",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0922',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0930", id: "", class: "role-checkbox-modal", text: "03. Rekap Bulanan ",  add_info: "Rekap Bulanan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0930',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0931", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0931',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0932", id: "", class: "role-checkbox-modal", text: "Rekap",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0932',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},


					{ value: "0950", id: "", class: "role-checkbox-modal", text: "04. Rekap Harian ",  add_info: "Rekap Harian",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0950',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0951", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0951',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0952", id: "", class: "role-checkbox-modal", text: "Rekap",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0952',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},

					{ value: "0940", id: "", class: "role-checkbox-modal", text: "05. Rekap Lembur ",  add_info: "Rekap Lembur",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0930',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
						items: [
								{ value: "0941", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0941',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
								// 
								{ value: "0942", id: "", class: "role-checkbox-modal", text: "Rekap",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('0942',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						]
					},	

					

									
				]
			},

		
	]
});

jQuery("#treeview_m2").kendoTreeView({
checkboxes: {
checkChildren: true,
//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"*/
template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"
},
check: onCheck,
dataSource: [		

	
	// ======================================================================================================== 
    // 10. PENGGAJIAN
    // ========================================================================================================
	
		{ value: "1000", id: "", class: "role-checkbox-modal", text: "10. Penggajian",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1000',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  
			items: [

				{ value: "1000",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1000',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
				{ value: "1010", id: "", class: "role-checkbox-modal", text: "01. Gaji Bulanan",  add_info: "Gaji Bulanan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1010',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

					items: [
						{ value: "1011", id: "", class: "role-checkbox-modal", text: "Aktifkan",       add_info: "Aktifkan",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('1011',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1012", id: "", class: "role-checkbox-modal", text: "Simpan Gaji",    add_info: "Simpan Gaji",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1012',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1013", id: "", class: "role-checkbox-modal", text: "Edit Gaji",           add_info: "Edit Gaji",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('1013',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10131", id: "", class: "role-checkbox-modal", text: "Hapus Gaji",         add_info: "Hapus Gaji",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('10131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1014", id: "", class: "role-checkbox-modal", text: "Lihat Slip",          add_info: "Lihat Slip",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1014',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1015", id: "", class: "role-checkbox-modal", text: "Download Slip",       add_info: "Download Slip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1015',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1016", id: "", class: "role-checkbox-modal", text: "Bayar Per Individu",  add_info: "Bayar Per Individu", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1016',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},

				{ value: "1020", id: "", class: "role-checkbox-modal", text: "02. Gaji Harian",  add_info: "Gaji Harian",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1020',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

					items: [
						{ value: "1021", id: "", class: "role-checkbox-modal", text: "Aktifkan",       add_info: "Aktifkan",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('1021',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1022", id: "", class: "role-checkbox-modal", text: "Simpan Gaji",    add_info: "Simpan Gaji",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1022',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1023", id: "", class: "role-checkbox-modal", text: "Edit Gaji",         add_info: "Edit Gaji",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('1023',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10231", id: "", class: "role-checkbox-modal", text: "Hapus Gaji",         add_info: "Hapus Gaji",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('10231',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1024", id: "", class: "role-checkbox-modal", text: "Lihat Slip",     add_info: "Lihat Slip",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1024',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1025", id: "", class: "role-checkbox-modal", text: "Download Slip",  add_info: "Download Slip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1025',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1026", id: "", class: "role-checkbox-modal", text: "Bayar Per Individu",  add_info: "Bayar Per Individu", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1026',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},

				{ value: "1030", id: "", class: "role-checkbox-modal", text: "03. Gaji Borongan",  add_info: "Gaji Borongan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1030',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

					items: [
						{ value: "1031", id: "", class: "role-checkbox-modal", text: "Aktifkan",       add_info: "Aktifkan",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('1031',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1032", id: "", class: "role-checkbox-modal", text: "Simpan Gaji",    add_info: "Simpan Gaji",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1032',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1033", id: "", class: "role-checkbox-modal", text: "Edit Gaji",         add_info: "Edit Gaji",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('1033',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10331", id: "", class: "role-checkbox-modal", text: "Hapus Gaji",         add_info: "Hapus Gaji",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('10331',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1034", id: "", class: "role-checkbox-modal", text: "Lihat Slip",     add_info: "Lihat Slip",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1034',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1035", id: "", class: "role-checkbox-modal", text: "Download Slip",  add_info: "Download Slip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1035',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1036", id: "", class: "role-checkbox-modal", text: "Bayar Per Individu",  add_info: "Bayar Per Individu", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1036',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},
							
			]
		},

	// ======================================================================================================== 
    // 101. THR
    // ========================================================================================================
	
		{ value: "10100", id: "", class: "role-checkbox-modal", text: "11. THR",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",  
			items: [

				{ value: "10100",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('10100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
				{ value: "10110", id: "", class: "role-checkbox-modal", text: "01. THR Bulanan",  add_info: "THR Bulanan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('10110',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

					items: [
						{ value: "10111", id: "", class: "role-checkbox-modal", text: "Aktifkan",       add_info: "Aktifkan",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('10111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10112", id: "", class: "role-checkbox-modal", text: "Simpan THR",    add_info: "Simpan THR",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('10112',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "10113", id: "", class: "role-checkbox-modal", text: "Edit THR",         add_info: "Edit THR",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('10113',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "101131", id: "", class: "role-checkbox-modal", text: "Hapus THR",         add_info: "Hapus THR",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('101131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "10114", id: "", class: "role-checkbox-modal", text: "Lihat Slip",     add_info: "Lihat Slip",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('10114',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10115", id: "", class: "role-checkbox-modal", text: "Download Slip",  add_info: "Download Slip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10115',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10116", id: "", class: "role-checkbox-modal", text: "Bayar Per Individu",  add_info: "Bayar Per Individu", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10116',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},

				{ value: "10120", id: "", class: "role-checkbox-modal", text: "02. THR Harian",  add_info: "THR Bulanan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('10120',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

					items: [
						{ value: "10121", id: "", class: "role-checkbox-modal", text: "Aktifkan",       add_info: "Aktifkan",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('10121',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10122", id: "", class: "role-checkbox-modal", text: "Simpan THR",    add_info: "Simpan THR",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('10122',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "10123", id: "", class: "role-checkbox-modal", text: "Edit THR",         add_info: "Edit THR",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('10123',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "101231", id: "", class: "role-checkbox-modal", text: "Hapus THR",         add_info: "Hapus THR",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('101231',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "10124", id: "", class: "role-checkbox-modal", text: "Lihat Slip",     add_info: "Lihat Slip",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('10124',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10125", id: "", class: "role-checkbox-modal", text: "Download Slip",  add_info: "Download Slip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10125',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10126", id: "", class: "role-checkbox-modal", text: "Bayar Per Individu",  add_info: "Bayar Per Individu", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10126',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},

				{ value: "10130", id: "", class: "role-checkbox-modal", text: "03. THR Borongan",  add_info: "THR Bulanan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('10130',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

					items: [
						{ value: "10131", id: "", class: "role-checkbox-modal", text: "Aktifkan",       add_info: "Aktifkan",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('10131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10132", id: "", class: "role-checkbox-modal", text: "Simpan THR",    add_info: "Simpan THR",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('10132',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "10133", id: "", class: "role-checkbox-modal", text: "Edit THR",         add_info: "Edit THR",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('10133',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "101331", id: "", class: "role-checkbox-modal", text: "Hapus THR",         add_info: "Hapus THR",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('101331',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "10134", id: "", class: "role-checkbox-modal", text: "Lihat Slip",     add_info: "Lihat Slip",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('10134',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10135", id: "", class: "role-checkbox-modal", text: "Download Slip",  add_info: "Download Slip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10135',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "10136", id: "", class: "role-checkbox-modal", text: "Bayar Per Individu",  add_info: "Bayar Per Individu", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10136',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},			
							
			]
		},

	// ======================================================================================================== 
    // 11. FINANCE
    // ========================================================================================================
	
		{ value: "1100", id: "", class: "role-checkbox-modal", text: "12. Finance",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",   
			items: [	

				{ value: "1100",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
				{ value: "1110", id: "", class: "role-checkbox-modal", text: "01. Gaji Bulanan",  add_info: "<?php echo $this->lang->line('xin_view_payslip');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1110',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1111", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1112", id: "", class: "role-checkbox-modal", text: "Eksport",     add_info: "Eksport",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1112',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
					]
				},
				{ value: "1120", id: "", class: "role-checkbox-modal", text: "02. THR Bulanan",  add_info: "<?php echo $this->lang->line('xin_view_payslip');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1120',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1121", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1121',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1122", id: "", class: "role-checkbox-modal", text: "Eksport",     add_info: "Eksport",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1122',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
					]
				},
				{ value: "1130", id: "", class: "role-checkbox-modal", text: "03. Gaji Harian",  add_info: "<?php echo $this->lang->line('xin_view_payslip');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1130',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1131", id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1132", id: "", class: "role-checkbox-modal", text: "Eksport",     add_info: "Eksport",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1132',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
					]
				},
			]
		},

	// ======================================================================================================== 
    // 12. PELATIHAN
    // ========================================================================================================	
	
		{ value: "53", id: "", class: "role-checkbox-modal", text: "13. Pelatihan",  add_info: "",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('53',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [

				{ value: "53",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('53',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
				{ value: "54", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_training_list');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", 
					items: [
						{ value: "54",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "341", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('341',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "342", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('342',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "343", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('343',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "344", id: "", class: "role-checkbox-modal", text: "Lihat",     add_info: "Lihat",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('344',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					]
				},
				{ value: "55", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_training_type');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('55',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
					items: [
						{ value: "55",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('55',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "551", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('551',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "552", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('552',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "553", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('553',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						
					]
				},
				{ value: "56", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_trainers_list');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('56',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
					items: [
						{ value: "56",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('56',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "561", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('561',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "562", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('562',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "563", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('563',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},
				{ value: "57", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('left_vendors_list');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('57',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
					items: [
						{ value: "57",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('57',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "571", id: "", class: "role-checkbox-modal", text: "Tambah",    add_info: "Tambah",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('571',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "572", id: "", class: "role-checkbox-modal", text: "Edit",      add_info: "Edit",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('572',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "573", id: "", class: "role-checkbox-modal", text: "Hapus",     add_info: "Hapus",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('573',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
						
					]
				},
			]
		},

	// ======================================================================================================== 
    // 13. LAPORAN
    // ========================================================================================================
	
		{ value: "1300", id: "", class: "role-checkbox-modal", text: "14. Laporan",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1300',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 

			items: [

				{ value: "1300",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1300',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
				{ value: "1310", id: "", class: "role-checkbox-modal", text: "01. Karyawan",  add_info: "Laporan Karyawan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1310',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1311", id: "", class: "role-checkbox-modal", text: "Karyawan Aktif",  add_info: "Laporan Karyawan Aktif",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1311',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1312", id: "", class: "role-checkbox-modal", text: "Karyawan Resign", add_info: "Laporan Karyawan Resign", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1312',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					]
				},

				{ value: "1330", id: "", class: "role-checkbox-modal", text: "02. Personalia",  add_info: "Laporan Personalia",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1330',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1331", id: "", class: "role-checkbox-modal", text: "Rekap Lembur",   add_info: "Laporan Lembur",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1331',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1332", id: "", class: "role-checkbox-modal", text: "Rekap Cuti Tahunan",     add_info: "Laporan Cuti Tahunan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1332',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					]
				},

				{ value: "1320", id: "", class: "role-checkbox-modal", text: "03. Legal",  add_info: "Laporan Legal",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1320',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1321", id: "", class: "role-checkbox-modal", text: "Aktifkan",             add_info: "Status Aktif",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1321',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "1322", id: "", class: "role-checkbox-modal", text: "Kontrak Habis",        add_info: "Laporan Kontrak Habis",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1322',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1323", id: "", class: "role-checkbox-modal", text: "Kontrak Akan Habis",   add_info: "Laporan Kontrak Akan Habis", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1323',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1324", id: "", class: "role-checkbox-modal", text: "Kontrak Berlangsung",  add_info: "Laporan Kontrak Berlangsung", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1324',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1325", id: "", class: "role-checkbox-modal", text: "Kontrak Belum Dibuat", add_info: "Laporan Kontrak Belum Dibuat", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1325',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1326", id: "", class: "role-checkbox-modal", text: "Tetap",                add_info: "Laporan Tetep", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1326',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					]
				},

				{ value: "13210", id: "", class: "role-checkbox-modal", text: "04. GA",  add_info: "Laporan GA",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('13210',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "13211", id: "", class: "role-checkbox-modal", text: "Aktifkan",     add_info: "Status Aktif",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('13211',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "13212", id: "", class: "role-checkbox-modal", text: "Perjanjian ",  add_info: "Laporan Perjanjian",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('13212',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "13213", id: "", class: "role-checkbox-modal", text: "Perizinan",    add_info: "Laporan Perizinan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('13213',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					]
				},				

				{ value: "1340", id: "", class: "role-checkbox-modal", text: "05. Reguler",  add_info: "Laporan Kehadiran",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1340',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1341", id: "", class: "role-checkbox-modal", text: "Per Karyawan",   add_info: "Laporan Per Karyawan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1341',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1342", id: "", class: "role-checkbox-modal", text: "Bulanan",     add_info: "Laporan Kehadiran Bulanan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1342',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1343", id: "", class: "role-checkbox-modal", text: "Harian",     add_info: "Laporan Kehadiran Harian", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1343',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1344", id: "", class: "role-checkbox-modal", text: "Borongan",     add_info: "Laporan Kehadiran Borongan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1344',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					]
				},

				{ value: "1350", id: "", class: "role-checkbox-modal", text: "06. Shift",  add_info: "Laporan Kehadiran",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1350',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [
						{ value: "1351", id: "", class: "role-checkbox-modal", text: "Per Karyawan",   add_info: "Laporan Per Karyawan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1351',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1352", id: "", class: "role-checkbox-modal", text: "Bulanan",     add_info: "Laporan Per Bulan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1352',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					]
				},


				{ value: "1360", id: "", class: "role-checkbox-modal", text: "07. Gaji Bulanan",  add_info: "Laporan Gaji Bulanan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1360',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [

						{ value: "1360",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1360',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
						{ value: "1361", id: "", class: "role-checkbox-modal", text: "Karyawan Gaji Bulanan",         add_info: "Laporan Karyawan Bulanan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1361',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1362", id: "", class: "role-checkbox-modal", text: "Detail Gaji Bulanan",           add_info: "Laporan Detail Gaji",     check: "<?php if(isset($_GET['role_id'])) { if(in_array('1362',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1363", id: "", class: "role-checkbox-modal", text: "Rekap Gaji Bulanan",            add_info: "Laporan Rekap Bulanan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1363',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1364", id: "", class: "role-checkbox-modal", text: "Resume Gaji Tahunan",           add_info: "Laporan Resum Tahunan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1364',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1365", id: "", class: "role-checkbox-modal", text: "Resume Pengajuan",              add_info: "Laporan Resum Pengajuan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1365',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1366", id: "", class: "role-checkbox-modal", text: "Slip Gaji Bulanan",             add_info: "Laporan Slip Gaji Bulanan",                 check: "<?php if(isset($_GET['role_id'])) { if(in_array('1366',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					
					]
				},

				{ value: "1370", id: "", class: "role-checkbox-modal", text: "08. Gaji Harian",  add_info: "Laporan Gaji Harian",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1370',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [

						{ value: "1370",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1370',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
						{ value: "1371", id: "", class: "role-checkbox-modal", text: "Karyawan Gaji Harian",          add_info: "Laporan Karyawan Gaji Harian",             check: "<?php if(isset($_GET['role_id'])) { if(in_array('1371',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1372", id: "", class: "role-checkbox-modal", text: "Detail Gaji Harian Periode",    add_info: "Laporan Detail Gaji Harian Periode",       check: "<?php if(isset($_GET['role_id'])) { if(in_array('1372',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1373", id: "", class: "role-checkbox-modal", text: "Rekap Gaji Harian Periode",     add_info: "Laporan Rekap Gaji Harian Periode",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1373',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1374", id: "", class: "role-checkbox-modal", text: "Rekap Gaji Harian ",     add_info: "Laporan Rekap Gaji Harian Bulanan",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1374',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1375", id: "", class: "role-checkbox-modal", text: "Resume Gaji Harian ",    add_info: "Laporan Resume Gaji Harian Bulanan",       check: "<?php if(isset($_GET['role_id'])) { if(in_array('1375',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1376", id: "", class: "role-checkbox-modal", text: "Slip Gaji Harian",              add_info: "Laporan Slip Gaji Harian",                 check: "<?php if(isset($_GET['role_id'])) { if(in_array('1376',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					
					]
				},

				{ value: "1380", id: "", class: "role-checkbox-modal", text: "09. Gaji Borongan",  add_info: "Laporan Gaji Borongan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1380',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [

						{ value: "1380",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1380',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
						{ value: "1381", id: "", class: "role-checkbox-modal", text: "Karyawan Gaji Borongan",           add_info: "Laporan Karyawan Borongan",                  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1381',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					 	{ value: "1382", id: "", class: "role-checkbox-modal", text: "Detail Gaji Borongan Periode",     add_info: "Laporan Detail Gaji Borongan Periode",       check: "<?php if(isset($_GET['role_id'])) { if(in_array('1382',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1383", id: "", class: "role-checkbox-modal", text: "Rekap Gaji Borongan Periode",      add_info: "Laporan Rekap Gaji Borongan Periode",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1383',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1384", id: "", class: "role-checkbox-modal", text: "Rekap Gaji Borongan ",             add_info: "Laporan Rekap Gaji Borongan Bulanan",        check: "<?php if(isset($_GET['role_id'])) { if(in_array('1384',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},	
						{ value: "1385", id: "", class: "role-checkbox-modal", text: "Resume Gaji Borongan",             add_info: "Laporan Resume Gaji Borongan ",              check: "<?php if(isset($_GET['role_id'])) { if(in_array('1385',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1386", id: "", class: "role-checkbox-modal", text: "Slip Gaji Borongan",               add_info: "Laporan Slip Gaji Borongan",                 check: "<?php if(isset($_GET['role_id'])) { if(in_array('1386',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					
					]
				},

				{ value: "1390", id: "", class: "role-checkbox-modal", text: "10. THR",  add_info: "THR",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1390',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [

						{ value: "1390",  id: "", class: "role-checkbox-modal", text: "Aktifkan",    add_info: "Aktifkan",             check: "<?php if(isset($_GET['role_id'])) { if(in_array('1390',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
						{ value: "1391", id: "", class: "role-checkbox-modal", text: "Detail THR",   add_info: "Laporan Detail THR",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1391',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1392", id: "", class: "role-checkbox-modal", text: "Rekap THR",    add_info: "Laporan Rekap THR",    check: "<?php if(isset($_GET['role_id'])) { if(in_array('1392',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "1393", id: "", class: "role-checkbox-modal", text: "Resume THR",   add_info: "Laporan Resume THR",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1393',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
					]
				},

				{ value: "1400", id: "", class: "role-checkbox-modal", text: "11. Finance",  add_info: "Finance",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1400',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [

						{ value: "1400", id: "", class: "role-checkbox-modal", text: "Aktifkan",          add_info: "Aktifkan",                check: "<?php if(isset($_GET['role_id'])) { if(in_array('1400',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
						
						{ value: "1401", id: "", class: "role-checkbox-modal", text: "Gaji Bulanan",      add_info: "Gaji Bulanan",            check: "<?php if(isset($_GET['role_id'])) { if(in_array('1401',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
						{ value: "1402", id: "", class: "role-checkbox-modal", text: "Rekap  Bulanan",    add_info: "Laporan Rekap Bulanan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1402',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1403", id: "", class: "role-checkbox-modal", text: "Resume Tahunan",    add_info: "Laporan Resum Tahunan",   check: "<?php if(isset($_GET['role_id'])) { if(in_array('1403',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1404", id: "", class: "role-checkbox-modal", text: "Resume Pengajuan",  add_info: "Laporan Resum Pengajuan", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1404',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						
						
						{ value: "1405", id: "", class: "role-checkbox-modal", text: "Gaji Harian",       add_info: "Gaji Harian",            check: "<?php if(isset($_GET['role_id'])) { if(in_array('1405',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
						{ value: "1406", id: "", class: "role-checkbox-modal", text: "Rekap  THR",        add_info: "Laporan Rekap THR",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('1406',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1407", id: "", class: "role-checkbox-modal", text: "Resume THR",        add_info: "Laporan Resum THR",      check: "<?php if(isset($_GET['role_id'])) { if(in_array('1407',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
					
					]
				},

				{ value: "1500", id: "", class: "role-checkbox-modal", text: "12. Produktifitas",  add_info: "Laporan Produktifitas",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1500',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					
					items: [

						{ value: "1500",  id: "", class: "role-checkbox-modal", text: "Aktifkan",  add_info: "Aktifkan",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('1500',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},			
				
						{ value: "1501", id: "", class: "role-checkbox-modal", text: "Produktifitas Per Periode",        add_info: "Laporan Produktifitas Per Periode",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('1501',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						{ value: "1502", id: "", class: "role-checkbox-modal", text: "Produktifitas Biaya Per Bulan",    add_info: "Laporan Produktifitas Per Bulanan",          check: "<?php if(isset($_GET['role_id'])) { if(in_array('1502',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},					
						
					]
				},
			

			]
		},	

	// ======================================================================================================== 
    // 14. NOTIFIKASI
    // ========================================================================================================
		{ value: "90", id: "", class: "role-checkbox-modal", text: "15. Notifikasi",  add_info: "<?php echo $this->lang->line('xin_view');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('90',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},

	// ========================================================================================================
	// 15. ASSET
	// ========================================================================================================
		{ value: "24", id: "", class: "role-checkbox-modal", text: "16. Asset ",  add_info: "", check: "<?php if(isset($_GET['role_id'])) { if(in_array('24',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",   
			items: [
				

				{ value: "26", id: "", class: "role-checkbox-modal", text: "Kategori",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					items: [
						{ value: "26",  id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "266", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('266',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "267", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('267',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "268", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('268',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},

				{ value: "25", id: "", class: "role-checkbox-modal", text: "Asset",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('25',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
					items: [
						{ value: "25",  id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('25',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "262", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('262',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "263", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('263',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "264", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('264',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "265", id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_assets').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('265',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},


				{ value: "27", id: "", class: "role-checkbox-modal", text: "Pinjam",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('27',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					items: [
						{ value: "27",  id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('27',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "276", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('276',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "277", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('277',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "278", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('278',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "279", id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_assets').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('279',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},

				{ value: "28", id: "", class: "role-checkbox-modal", text: "Kembali",  add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					items: [
						{ value: "28",  id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_enable');?>",  add_info: "<?php echo $this->lang->line('xin_role_enable');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						
						{ value: "286", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_add');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('286',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "287", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_edit');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('287',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "288", id: "", class: "role-checkbox-modal", text: "<?php echo $this->lang->line('xin_role_delete');?>",  add_info: "<?php echo $this->lang->line('xin_role_add');?>", check: "<?php if(isset($_GET['role_id'])) { if(in_array('288',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
						{ value: "289", id: "", class: "role-checkbox-modal", text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('xin_assets').'</small>';?>",  add_info: "<?php echo $this->lang->line('xin_role_view');?>",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('289',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"}
					]
				},
			]
		},
]
});
		
// show checked node IDs on datasource change
function onCheck() {
	var checkedNodes = [],
	treeView = jQuery("#treeview").data("kendoTreeView"),
	message;
	//checkedNodeIds(treeView.dataSource.view(), checkedNodes);
	jQuery("#result").html(message);
}
$(document).ready(function(){
	$("#role_access_modal").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox-modal').prop('checked', true);
		} else {
			$('.role-checkbox-modal').prop("checked", false);
		}
	});
});
</script>
<?php }
?>
