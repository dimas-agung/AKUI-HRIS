<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['trainer_id']) && $_GET['data']=='trainer'){
?>

<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Pelatih </h4>
</div>
<?php $attributes = array('name' => 'edit_trainer', 'id' => 'edit_trainer', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $trainer_id, 'ext_name' => $trainer_id);?>
<?php echo form_open('admin/trainers/update/'.$trainer_id, $attributes, $hidden);?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="<?php echo $first_name;?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_last_name');?></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" value="<?php echo $last_name;?>">
          </div>
        </div>
      </div>      
     
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="designation">Nama Vendor</label>
            <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="Nama Vendor">
              <option value=""></option>
              <?php foreach($all_vendors as $company) {?>
              <option value="<?php echo $company->vendor_id;?>" <?php if($company_id==$company->vendor_id):?> selected="selected" <?php endif;?>> <?php echo $company->name;?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="expertise">Bidang Keahlian</label>
            <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_expertise');?>" name="expertise" cols="30" rows="5" id="expertise2"><?php echo $expertise;?></textarea>
          </div>
        </div>
      </div>

     <div class="row">
        <div class="col-md-12">    
          <div class="form-group">
            <label for="address"><?php echo $this->lang->line('xin_address');?></label>
            <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_address');?>" name="address" cols="30" rows="3" id="address"><?php echo $address;?></textarea>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="contact_number"><?php echo $this->lang->line('xin_contact_number');?></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_number" type="text" value="<?php echo $contact_number;?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="text" value="<?php echo $email;?>">
          </div>
        </div>
      </div>
      
    </div>
    
  </div>
  
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update');?></button>
</div>
<?php echo form_close(); ?> 

<script type="text/javascript">
  $(document).ready(function(){
  						
  	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
  	$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
  	
  	//$('#expertise2').trumbowyg();
  	/* Edit data */
  	$("#edit_trainer").submit(function(e){
  	e.preventDefault();
  		var obj = $(this), action = obj.attr('name');
  		$('.save').prop('disabled', true);
  		$.ajax({
  			type: "POST",
  			url: e.target.action,
  			data: obj.serialize()+"&is_ajax=1&edit_type=trainer&form="+action,
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
  							url : "<?php echo site_url("admin/trainers/trainer_list") ?>",
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

<?php } else if(isset($_GET['jd']) && isset($_GET['trainer_id']) && $_GET['data']=='view_trainer'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-eye"></i> Lihat Pelatih </h4>
</div>
<form class="m-b-1">
<div class="modal-body">
  <table class="footable-details table table-striped table-hover toggle-circle">
    <tbody>
      <tr>
        <th>Nama Vendor</th>
        <td style="display: table-cell;"><?php foreach($all_vendors as $company) {?>
          <?php if($company_id==$company->vendor_id):?>
          <?php echo $company->name;?>
          <?php endif;?>
          <?php } ?></td>
      </tr>
      <tr>
        <th><?php echo $this->lang->line('xin_employee_first_name');?></th>
        <td style="display: table-cell;"><?php echo $first_name;?></td>
      </tr>
      <tr>
        <th><?php echo $this->lang->line('xin_employee_last_name');?></th>
        <td style="display: table-cell;"><?php echo $last_name;?></td>
      </tr>
      <tr>
        <th><?php echo $this->lang->line('xin_contact_number');?></th>
        <td style="display: table-cell;"><?php echo $contact_number;?></td>
      </tr>
      <tr>
        <th><?php echo $this->lang->line('dashboard_email');?></th>
        <td style="display: table-cell;"><?php echo $email;?></td>
      </tr>
      <tr>
        <th><?php echo $this->lang->line('xin_expertise');?></th>
        <td style="display: table-cell;"><?php echo html_entity_decode($expertise);?></td>
      </tr>
      <tr>
        <th><?php echo $this->lang->line('xin_address');?></th>
        <td style="display: table-cell;"><?php echo html_entity_decode($address);?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
</div>
<?php echo form_close(); ?>
<?php }
?>
