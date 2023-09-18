<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['pinjam_id']) && $_GET['data']=='eassets_pinjam'){ ?>
 
  <?php $session = $this->session->userdata('username');?>
  <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
  
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Pinjam Aset</h4>
  </div>
  
  <?php $attributes = array('name'    => 'update_assets_pinjam', 'id'     => 'update_assets_pinjam', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
  <?php $hidden     = array('_method' => 'EDIT',                '_token' => $pinjam_id, 'ext_name' => $pinjam_id);?>
  <?php echo form_open_multipart('admin/assets/update_assets_pinjam/'.$pinjam_id, $attributes, $hidden);?>

    <div class="modal-body">
      <div class="row">
        <div class="col-md-6">         

          <div class="row">
            <div class="col-md-6">
             
              <div class="form-group">
                <label for="company_id"><?php echo $this->lang->line('left_company');?></label>
                <select class="form-control" name="company_id" id="ajx_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                  <option value=""></option>
                  <?php foreach($all_companies as $company) {?>
                  <option value="<?php echo $company->company_id?>" <?php if($company_id==$company->company_id):?> selected="selected" <?php endif;?>><?php echo $company->name?></option>
                  <?php } ?>
                </select>
              </div>
            
            </div>
            <div class="col-md-6">
              <?php $result = $this->Department_model->ajax_company_employee_info($company_id);?>
              <div class="form-group" id="employee_ajx">
                <label for="first_name"><?php echo $this->lang->line('xin_assets_assign_to');?></label>
                <select class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                  <option value=""></option>
                  <?php foreach($result as $employee) {?>
                  <option value="<?php echo $employee->user_id?>" <?php if($employee_id==$employee->user_id):?> selected="selected" <?php endif;?>><?php echo $employee->first_name.' '.$employee->last_name;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="category_id">Kategori Aset</label>
                <select class="form-control" name="category_id" id="category_id" data-plugin="select_hrm" data-placeholder="Pilih Kategori">
                  <option value=""></option>
                  <?php foreach($all_categories as $assets_category) {?>
                  <option value="<?php echo $assets_category->assets_category_id?>" <?php if($category_id==$assets_category->assets_category_id):?> selected="selected" <?php endif;?>><?php echo $assets_category->category_name?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <?php $result = $this->Assets_model->ajax_assets_info($category_id);?>
              <div class="form-group" id="assets_ajx">
                <label for="assets_id">Master Aset</label>
                <select class="form-control" name="assets_id" id="assets_id" data-plugin="select_hrm" data-placeholder="Pilih Aset">
                  <option value=""></option>
                  <?php foreach($result as $assets) {?>
                  <option value="<?php echo $assets->assets_id?>" <?php if($assets_id==$assets->assets_id):?> selected="selected" <?php endif;?>><?php echo $assets->name ;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="pinjam_date">Tanggal Pinjam</label>
                <input class="form-control d_assets_date" placeholder="Tanggal Pinjam" name="pinjam_date" type="text" value="<?php echo $pinjam_date?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="kembali_date">Tanggal Kembali</label>
                <input class="form-control d_assets_date" placeholder="Tanggal Kembali" name="kembali_date" type="text" value="<?php echo $kembali_date?>">
              </div>
            </div>
          </div>
          
        </div>
        <div class="col-md-6">          

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="award_information"><?php echo $this->lang->line('xin_asset_note');?></label>
                <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_asset_note');?>" name="asset_note" cols="30" rows="6" id="asset_note"><?php echo $asset_note; ?></textarea>
              </div>
            </div>
          </div>

          <div class="row">            
            <div class="col-md-6">
              <div class="form-group">
                <label for="is_pinjam" class="control-label"> Status Aset</label>
                <select class="form-control" name="is_pinjam" data-plugin="select_hrm" data-placeholder="Pilih Status Aset">
                  <option value="1" <?php if($is_pinjam==1):?> selected="selected" <?php endif;?>> Dipinjam </option>
                  <option value="2" <?php if($is_pinjam==2):?> selected="selected" <?php endif;?>> Dikembalikan </option>
                </select>
              </div>
            </div>            
          </div>

        </div>
      </div>

      
    </div>
    <div class="modal-footer"> 
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => $this->Core_model->form_button_close_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
    <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-save"></i> '.$this->lang->line('xin_update'))); ?> 
  </div>
  <?php echo form_close(); ?>
  
  <script type="text/javascript">
  
  $(document).ready(function(){
  						
  	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
  	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
  	
  	jQuery("#ajx_company").change(function(){
  		jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
  			jQuery('#employee_ajx').html(data);
  		});
  	}); 

    jQuery("#category_id").change(function(){
      jQuery.get(base_url+"/get_assets/"+jQuery(this).val(), function(data, status){
        jQuery('#assets_ajax').html(data);
      });
    });

  	// Award Date
  	$('.d_assets_date').datepicker({
      	changeMonth: true,
      	changeYear: true,
      	dateFormat:'yy-mm-dd',
      	yearRange: '1900:' + (new Date().getFullYear() + 15),
      	beforeShow: function(input) {
      		$(input).datepicker("widget").show();
      	}
  	});

  	/* Edit data */
  	$("#update_assets_pinjam").submit(function(e){
  		var fd = new FormData(this);
  		var obj = $(this), action = obj.attr('name');
  		fd.append("is_ajax", 2);
  		fd.append("edit_type", 'update_assets_pinjam');
  		fd.append("form", action);
  		e.preventDefault();
  		$('.save').prop('disabled', true);
  		$.ajax({
  			url: e.target.action,
  			type: "POST",
  			data:  fd,
  			contentType: false,
  			cache: false,
  			processData:false,
  			success: function(JSON)
  			{
  				if (JSON.error != '') {
  					toastr.error(JSON.error);
  					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
  					$('.save').prop('disabled', false);
  				} else {

  					// On page load: datatable
  					var xin_table = $('#xin_table').dataTable({
  						"bDestroy": true,
  						"ajax": {
  							url : "<?php echo site_url("admin/assets/pinjam_list"); ?>",
  							type : 'GET'
  						}, 						
              
              "columns": [
                  {"name": "kolom_1", "className": "text-center"},
                  {"name": "kolom_2", "className": "text-center"},
                  {"name": "kolom_3", "className": "text-center"},
                  {"name": "kolom_4", "className": "text-center"},      
                  {"name": "kolom_5", "className": "text-left"},
                  {"name": "kolom_6", "className": "text-left"},
                  {"name": "kolom_7", "className": "text-left"},
                  {"name": "kolom_8", "className": "text-center"},      
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
  						toastr.success(JSON.result);
  					}, true);
  					
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
  					$('.edit-modal-data').modal('toggle');
  					$('.save').prop('disabled', false);
  				}
  			},
  			error: function() 
  			{
  				toastr.error(JSON.error);
  				$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
  				$('.save').prop('disabled', false);
  			} 	        
  	   });
  	});
  });	
  
  </script>

<?php } if(isset($_GET['jd']) && isset($_GET['type']) && $_GET['data']=='view_asset_pinjam'){ ?>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-eye"></i> Lihat Pinjam Aset</h4>
    </div>
    <form class="m-b-1">
      <div class="modal-body">
        <table class="footable-details table table-striped table-hover toggle-circle">
          <tbody>
                                   
            <tr>
              <th class="text-right" width="40%"> Nama Perusahaan </th>
              <td style="display: table-cell;">:
                <?php foreach($all_companies as $company) {?>
                <?php if($company_id==$company->company_id):?>
                <?php echo $company->name;?>
                <?php endif;?>
                <?php } ?></td>
            </tr>
            <tr>
              <th class="text-right" width="40%"> Nama Karyawan </th>
              <td style="display: table-cell;">:
                <?php foreach($all_employees as $employee) {?>
                <?php if($employee_id==$employee->user_id):?>
                <?php echo $employee->first_name.' '.$employee->last_name;?>
                <?php endif;?>
                <?php } ?></td>
            </tr>

            <tr>
              <th class="text-right" width="40%"> Kategori Aset </th>
              <td style="display: table-cell;">:
                <?php foreach($all_categories as $category) {?>
                <?php if($category_id==$category->assets_category_id):?>
                <?php echo $category->category_name;?>
                <?php endif;?>
                <?php } ?></td>
            </tr>

             <tr>
              <th class="text-right" width="40%"> Master Aset </th>
              <td style="display: table-cell;">:
                <?php foreach($all_assets as $assets) {?>
                <?php if($assets_id==$assets->assets_id):?>
                <?php echo $assets->name;?>
                <?php endif;?>
                <?php } ?></td>
            </tr>

            <tr>
              <th class="text-right" width="40%">Tanggal Pinjam </th>
              <td style="display: table-cell;">: <?php echo $this->Core_model->set_date_format($pinjam_date);?></td>
            </tr>
            <tr>
              <th class="text-right" width="40%">Tanggal Kembali </th>
              <td style="display: table-cell;">: <?php echo $this->Core_model->set_date_format($kembali_date);?></td>
            </tr>

            <tr>
              <th class="text-right" width="40%"> Status Aset</th>
              <td style="display: table-cell;">: 
          		  <?php
          			if($is_pinjam==1){
          				echo $working = 'Dipinjam';
          			} else {
          				echo $working = 'Dikembalikan';
          			}
    		        ?>                
              </td>
            </tr>           
            
            <tr>
              <th class="text-right" width="40%"><?php echo $this->lang->line('xin_asset_note');?></th>
              <td style="display: table-cell;">: <?php echo html_entity_decode($asset_note);?></td>
            </tr>

          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
      </div>

    <?php echo form_close(); ?>

<?php } ?>
