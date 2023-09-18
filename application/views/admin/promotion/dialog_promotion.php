<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(isset($_GET['jd']) && isset($_GET['promotion_id']) && $_GET['data']=='promotion'){ ?>

    <?php $session = $this->session->userdata('username');?>
    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-check-circle"></i> Aktifkan Promosi ini </h4>
    </div>
    <?php $attributes = array('name' => 'edit_promotion', 'id' => 'edit_promotion', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $promotion_id, 'ext_name' => $promotion_id);?>
    <?php echo form_open('admin/promotion/update/'.$promotion_id, $attributes, $hidden);?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="company_name"><?php echo $this->lang->line('left_company');?></label>
                 <input style ="background-color: #f1f1f1 !important;" disabled="disabled" class="form-control" placeholder="Perusahaan" name="company_name" type="text" value="<?php echo $company_name;?>">
                </div>
              </div>
            </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="company_name">Karyawan Promosi</label>
                 <input style ="background-color: #f1f1f1 !important;" disabled="disabled" class="form-control" placeholder="Karyawan" name="employee_name" type="text" value="<?php echo $employee_name;?>">
                </div>
              </div>
            </div>

             <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="company_name">Posisi Promosi</label>
                 <input style ="background-color: #f1f1f1 !important;" disabled="disabled" class="form-control" placeholder="Posisi Promosi" name="designation_name" type="text" value="<?php echo $designation_name;?>">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="company_name">Posisi Lama</label>
                 <input style ="background-color: #f1f1f1 !important;" disabled="disabled" class="form-control" placeholder="Posisi Lama" name="designation_name_old" type="text" value="<?php echo $designation_name_old;?>">
                </div>
              </div>
            </div>
            

            
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="promotion_date"><?php echo $this->lang->line('xin_promotion_date');?></label>
                  <input class="form-control d_date" placeholder="<?php echo $this->lang->line('xin_promotion_date');?>" readonly name="promotion_date" type="text" value="<?php echo $promotion_date;?>" />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="xin_promotion_title"><?php echo $this->lang->line('xin_promotion_title');?></label>
                  <select name="title" id="title" class="form-control" data-plugin="select_hrm">
                    <option value="1" <?php if($title==1):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_full_tTime');?></option>
                    <option value="2" <?php if($title==2):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_part_tTime');?></option>
                    <option value="3" <?php if($title==3):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_free_lance');?></option>
                  </select>
                </div>
              </div>
            </div>

            
            <div class="row">
              <div class="col-md-12">
              <div class="form-group">
              <label for="reason"><?php echo $this->lang->line('xin_description');?></label>
              <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" cols="30" rows="5" id="description2"><?php echo $description;?></textarea>
            </div>
          </div>
          </div>
          
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Aktifkan </button>
      </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
     $(document).ready(function(){
    							
    		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
    		
    		//$('#description2').trumbowyg();
    		jQuery("#ajx_company").change(function(){
    			jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
    				jQuery('#employee_ajx').html(data);
    			});
    		});
    		$('.d_date').datepicker({
    		changeMonth: true,
    		changeYear: true,
    		dateFormat:'yy-mm-dd',
    		yearRange: '1900:' + (new Date().getFullYear() + 10),
    		beforeShow: function(input) {
    			$(input).datepicker("widget").show();
    		}
    		});

    		/* Edit data */
    		$("#edit_promotion").submit(function(e){
    		e.preventDefault();
    			var obj = $(this), action = obj.attr('name');
    			$('.save').prop('disabled', true);
    			$.ajax({
    				type: "POST",
    				url: e.target.action,
    				data: obj.serialize()+"&is_ajax=1&edit_type=promotion&form="+action,
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
        							url : "<?php echo site_url("admin/promotion/promotion_list") ?>",
        							type : 'GET'
        						},
        					  "columns": [
                      {"name": "kolom_1", "className": "text-center"},
                      {"name": "kolom_2", "className": "text-center"},
                      {"name": "kolom_3", "className": "text-left"},
                      {"name": "kolom_4", "className": "text-left"},
                      {"name": "kolom_5", "className": "text-left"},        
                      {"name": "kolom_6", "className": "text-center"},  
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

<?php } ?>
