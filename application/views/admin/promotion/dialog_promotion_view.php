<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- <?php if(isset($_GET['jd']) && isset($_GET['promotion_id']) && $_GET['data']=='view_promotion'){ ?> -->
   
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-eye"></i> <?php echo $this->lang->line('xin_view_promotion');?></h4>
    </div>
    <form class="m-b-1">
      <div class="modal-body">
        <table class="footable-details table table-striped table-hover toggle-circle">
          <tbody>
            <tr>
              <th><?php echo $this->lang->line('left_company');?></th>
              <td style="display: table-cell;"><?php foreach($get_all_companies as $company) {?>
                <?php if($company_id==$company->company_id):?>
                <?php echo $company->name;?>
                <?php endif;?>
                <?php } ?></td>
            </tr>
            <tr>
              <th align="right"><?php echo $this->lang->line('xin_promotion_for');?></th>
              <td style="display: table-cell;">: <?php echo $employee_name;?></td>               
            </tr>
            <tr>
              <th align="right"><?php echo $this->lang->line('xin_promotion_title');?></th>
              <td style="display: table-cell;">: <?php echo $title;?></td>
            </tr>
            <tr>
              <th align="right"><?php echo $this->lang->line('xin_designation');?></th>
              <td style="display: table-cell;">: <?php echo $designation_name;?></td>
            </tr>
            <tr>
              <th align="right"><?php echo $this->lang->line('xin_promotion_date');?></th>
              <td style="display: table-cell;">: <?php echo $this->Core_model->set_date_format($promotion_date);?></td>
            </tr>
            <tr>
              <th align="right"><?php echo $this->lang->line('xin_description');?></th>
              <td style="display: table-cell;">: <?php echo html_entity_decode($description);?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
      </div>
    <?php echo form_close(); ?>

<!-- <?php } ?> -->
