

<div class="modal fadeInRight edit-modal-data animated " id="edit-modal-data" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="ajax_modal"></div>
  </div>
</div>

<div class="modal fadeInRight edit-modal-data-min animated " id="edit-modal-data-min" role="dialog" aria-labelledby="edit-modal-data-min" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_modal_min"></div>
  </div>
</div>

<div class="modal fadeInRight edit-modal-data-max animated " id="edit-modal-data-max" role="dialog" aria-labelledby="edit-modal-data-max" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="ajax_modal_max"></div>
  </div>
</div>

<div class="modal modal-danger fadeInUp delete-modal-data animated " id="delete-modal-data" role="dialog" aria-labelledby="delete-modal-data" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="ajax_modal"></div>
  </div>
</div>

<div class="modal fadeInRight edit-modal-data-shift animated " id="edit-modal-data-shift" role="dialog" aria-labelledby="edit-modal-data-shift" aria-hidden="true">
  <div class="modal-dialog modal-full">
    <div class="modal-content" id="ajax_modal_shift"></div>
  </div>
</div>

<div class="modal modal-danger fadeInUp delete-modal animated " role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
        <div class="modal-header"> 
          <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?> 
          <strong class="modal-title"><i class="fa fa-trash"></i> <?php echo $this->lang->line('xin_delete_confirm');?></strong> 
        </div>
      
        <div class="info_delete">
          <span ><?php echo $this->lang->line('xin_d_not_restored');?></span>
        </div>
      
        <?php $attributes = array('name' => 'delete_record', 'id' => 'delete_record', 'autocomplete' => 'off', 'role'=>'form');?>
        <?php $hidden = array('_method' => 'DELETE', '_token' => '000');?>
        <?php echo form_open('', $attributes, $hidden);?> 
        <div class="modal-footer">        
          <?php
          $del_token = array(
            'type'  => 'hidden',
            'id'  => 'token_type',
            'name'  => 'token_type',
            'value' => 0,
          );
          echo form_input($del_token);
          ?>
              
          <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => $this->Core_model->form_button_close_class(), 'content' => '<i class="fa fa-power-off"></i> '.$this->lang->line('xin_close'))); ?> 
          <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_delete_class(), 'content' => '<i class="fa fa-trash"></i> '.$this->lang->line('xin_confirm_del'))); ?> 
          <?php echo form_close(); ?> 
        </div>
    </div>
  </div>
</div>

<div class="modal fadeInRight add-modal-data animated " id="add-modal-data" role="dialog" aria-labelledby="add-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="add_ajax_modal"></div>
  </div>
</div>

<div class="modal fadeInRight payroll_template_modal default-modal animated " id="payroll_template_modal" role="dialog" aria-labelledby="detail-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_modal_payroll"></div>
  </div>
</div>

<div class="modal fadeInRight view-modal-data animated " id="view-modal-data" role="dialog" aria-labelledby="view-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_modal_view"></div>
  </div>
</div>

<div class="modal fadeInRight emo_monthly_pay animated " id="emo_monthly_pay" role="dialog" aria-labelledby="emo_monthly_pay" aria-hidden="true">
  <div class="modal-dialog modal-full">
    <div class="modal-content" id="emo_monthly_pay_aj"></div>
  </div>
</div>

<div class="modal fadeInRight del_monthly_pay animated " id="del_monthly_pay" role="dialog" aria-labelledby="del_monthly_pay" aria-hidden="true">
  <div class="modal-dialog modal-full">
    <div class="modal-content" id="del_monthly_pay_aj"></div>
  </div>
</div>

<div class="modal fadeInRight emo_dayly_pay animated " id="emo_dayly_pay" role="dialog" aria-labelledby="emo_dayly_pay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="emo_dayly_pay_aj"></div>
  </div>
</div>

<div class="modal fadeInRight del_dayly_pay animated " id="del_dayly_pay" role="dialog" aria-labelledby="del_dayly_pay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="del_dayly_pay_aj"></div>
  </div>
</div>

<div class="modal fadeInRight emo_borongan_pay animated " id="emo_borongan_pay" role="dialog" aria-labelledby="emo_borongan_pay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="emo_borongan_pay_aj"></div>
  </div>
</div>

<div class="modal fadeInRight del_borongan_pay animated " id="del_borongan_pay" role="dialog" aria-labelledby="del_borongan_pay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="del_borongan_pay_aj"></div>
  </div>
</div>