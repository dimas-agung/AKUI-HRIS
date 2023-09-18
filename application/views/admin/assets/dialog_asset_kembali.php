<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['type']) && $_GET['data']=='view_asset_kembali'){ ?>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-eye"></i> Lihat Aset Dikembalikan </h4>
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
