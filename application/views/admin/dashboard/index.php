<?php 
$session = $this->session->userdata('username');
$user_info = $this->Core_model->read_user_info($session['user_id']);
$theme = $this->Core_model->read_theme_info(1);
?>
<?php

if($user_info[0]->user_role_id == 1):
  	$this->load->view('admin/dashboard/administrator_dashboard');

elseif($user_info[0]->user_role_id == 2):
	$this->load->view('admin/dashboard/employee_dashboard');

elseif($user_info[0]->user_role_id == 3):
	$this->load->view('admin/dashboard/employee_dashboard');

elseif($user_info[0]->user_role_id == 4):
	$this->load->view('admin/dashboard/rekrutmen_dashboard');

elseif($user_info[0]->user_role_id == 5):
	$this->load->view('admin/dashboard/legal_dashboard');

elseif($user_info[0]->user_role_id == 6):
	$this->load->view('admin/dashboard/payroll_dashboard');

elseif($user_info[0]->user_role_id == 9):
	$this->load->view('admin/dashboard/payroll_dashboard');

elseif($user_info[0]->user_role_id == 10):
	$this->load->view('admin/dashboard/hr_dashboard');

elseif($user_info[0]->user_role_id == 11):
	$this->load->view('admin/dashboard/ga_dashboard');

elseif($user_info[0]->user_role_id == 12):
	$this->load->view('admin/dashboard/employee_dashboard');

else:
	$this->load->view('admin/dashboard/employee_dashboard');
endif;

// if($theme[0]->dashboard_option == 'dashboard_1') {
		
	// } else if($theme[0]->dashboard_option == 'dashboard_2') {
	// 	$this->load->view('admin/dashboard/administrator_dashboard_2');
	// } else if($theme[0]->dashboard_option == 'dashboard_3') {
	// 	$this->load->view('admin/dashboard/administrator_dashboard_3');
	// } else if($theme[0]->dashboard_option == 'dashboard_4') {
	// 	$this->load->view('admin/dashboard/administrator_dashboard_4');
	// } else {
	// 	$this->load->view('admin/dashboard/administrator_dashboard_1');
	// }

?>

