<?php 
 /** 
 * ---------------------------------------------------------------------
 * INFORMASI
 * -----------------------------------------------------------------------
 * Nama Aplikasi :  HRIS
 * Email Support :  hris@karyasoftware.com
 * Developer     :  Nizar Basyrewan, S.Si - 0895 606460 731
 * Tahun         :  2020
 * Copyright     :  ©karyasoftware.com. All Rights Reserved
 * ----------------------------------------------------------------------
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Department_model");
		$this->load->model("Employees_model");
		$this->load->model("Location_model");
		$this->load->model("Core_model");
		$this->load->model("Company_model");
	}
	
	// ======================================================================================================
	// START
	// ======================================================================================================

		/*Function to set JSON output*/
		public function output($Return=array()){
			/*Set response header*/
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			/*Final JSON response*/
			exit(json_encode($Return));
		}
	
	// ======================================================================================================
	// DAFTAR
	// ======================================================================================================

		public function index()
	    {
			$session = $this->session->userdata('username');
			if(!$session){ 
				redirect('admin/');
			}
			$session = $this->session->userdata('username');
			$data['title']         = $this->lang->line('xin_departments').' | '.$this->Core_model->site_title();
			$data['icon']          = '<i class="fa fa-briefcase"></i>';
			$data['breadcrumbs']   = $this->lang->line('xin_departments');
			$data['path_url']      = 'department';

			$data['all_locations'] = $this->Core_model->all_locations();
			$data['all_employees'] = $this->Core_model->all_employees();
			$data['get_all_companies'] = $this->Company_model->get_company();
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
					
			if(in_array('0231',$role_resources_ids)) {
				if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/department/department_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }	 

		public function department_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/department/department_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);			
			
			$company = $this->Company_model->get_companies();			
			
			$data = array();
			$no = 1;
	        
	        foreach($company->result() as $r) {
						
				$icname = $r->name;

				$departemen = '';

				$sql_departemen = " SELECT *
							FROM
								 xin_departments
							WHERE
								1 = 1
							AND company_id  = '".$r->company_id."'								
							ORDER BY department_id ASC";                                    

				// echo "<pre>";
				// print_r( $sql_departemen );
				// echo "</pre>";
				// die;

				$query_departemen = $this->db->query($sql_departemen);

				if ($query_departemen->num_rows() > 0) {
					

					$departemen ='<table class="datatables-demo table table-striped table-bordered" id="xin_table">
					            <thead>
					              <tr>
					                <th class="text-center" width="50px">No.</th>			                
					                <th class="text-center" > Departemen </th>
					                <th class="text-center" width="200px"> Kepala Departemen</th>
					                <th class="text-center" width="200px"> Lokasi Kantor</th>			               
					                <th class="text-center" width="100px"> <i class="fa fa-user"></i> Karyawan</th>
					                <th class="text-center" width="100px"> Aksi</th>
					              </tr>
					            </thead>
					            <tbody>';
	                        	$mo = 1;
                                foreach($query_departemen->result() as $row_departemen):
  									
  									$head_user = $this->Core_model->read_user_info($row_departemen->employee_id);
  									if(!is_null($head_user)){
										$dep_head = $head_user[0]->first_name.' '.$head_user[0]->last_name;
									} else {
										$dep_head = '--';	
									}

									$location = $this->Location_model->read_location_information($row_departemen->location_id);
									if(!is_null($location)){
										$location_name = $location[0]->location_name;
									} else {
										$location_name = '--';	
									}

									$jum_karyawan = $this->Employees_model->get_total_employees_departemen($row_departemen->department_id);
									if(!is_null($jum_karyawan)){
										$jumlah_karyawan = $jum_karyawan[0]->jumlah;
									} else {
										$jumlah_karyawan = '--';	
									}

									if(in_array('0233',$role_resources_ids)) { //edit
										$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-department_id="'. $row_departemen->department_id . '"><span class="fa fa-pencil"></span> Edit </button></span>';
									} else {
										$edit = '';
									}
									if(in_array('0234',$role_resources_ids)) { // delete
										$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $row_departemen->department_id . '"><span class="fa fa-trash"></span></button></span>';
									} else {
										$delete = '';
									}

									$ititle = strtoupper($row_departemen->department_name);
									$departemen = $departemen.' 
									        <tr">
			                                    <td width="2%" align="center">'.$mo.'.</td>
			                                    
			                                    <td align="left">
			                                     '.$ititle.' 
			                                    </td>

			                                    <td width="20%" align="left">
			                                     '.$dep_head.' 
			                                    </td>
			                                    
			                                    <td width="20%" align="left">
			                                     '.strtoupper($location_name).' 
			                                    </td>
												
												<td width="12%" align="center">
			                                     '.$jumlah_karyawan.' 
			                                    </td>
													
												<td width="8%" align="center">
			                                     '.$edit.$delete.' 
			                                    </td>
					                        </tr>';
			                    $mo++;
                          		endforeach;
								
					$departemen = $departemen.'
								</tbody>
					            </table>';
				} else {

					$departemen ='<div class="warning-msg" style="padding:5px;">
					                <i class ="fa fa-question-circle"></i> Tidak Ada Departemen		                      
					             </div>';
				}

			    $data[] = array(
					$no,
					$icname,
					$departemen
					
			    );
			    $no++;
	        }

	        $output = array(
	            "draw" => $draw,
	            "recordsTotal" => $company->num_rows(),
	            "recordsFiltered" => $company->num_rows(),
	            "data" => $data
	        );
	        echo json_encode($output);
	        exit();
	    }

	// ======================================================================================================
	// PROSES
	// ======================================================================================================	 
	
		public function read()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$keywords = preg_split("/[\s,]+/", $this->input->get('department_id'));
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
				$id = $this->security->xss_clean($id);
				$result = $this->Department_model->read_department_information($id);
				$data = array(
					'department_id' => $result[0]->department_id,
					'location_id' => $result[0]->location_id,
					'department_name' => $result[0]->department_name,
					'company_id' => $result[0]->company_id,
					'employee_id' => $result[0]->employee_id,
					'all_locations' => $this->Core_model->all_locations(),
					'all_employees' => $this->Core_model->all_employees(),
					'get_all_companies' => $this->Company_model->get_company()
					);
				$session = $this->session->userdata('username');
				
				if(!empty($session)){ 
					$this->load->view('admin/department/dialog_department', $data);
				} else {
					redirect('admin/');
				}
			}
		}		
		
		// Validate and add info in database
		public function add_department() 
		{
			if($this->input->post('add_type')=='department') {
			// Check validation for user input
			$session = $this->session->userdata('username');
			
			$this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('company_id', 'Company', 'trim|required|xss_clean');
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			//if($this->form_validation->run() == FALSE) {
					//$Return['error'] = 'validation error.';
			//}
			/* Server side PHP input validation */
			if($this->input->post('department_name')==='') {
	        	$Return['error'] = $this->lang->line('error_department_field');
			} else if($this->input->post('company_id')==='') {
				$Return['error'] = $this->lang->line('error_company_field');
			} else if($this->input->post('location_id')==='') {
				$Return['error'] = $this->lang->line('xin_location_field_error');
			} 
			if($Return['error']!=''){
				
	       		$this->output($Return);
	    	}
		
			$data = array(
			'department_name' => $this->input->post('department_name'),
			'company_id' => $this->input->post('company_id'),
			'location_id' => $this->input->post('location_id'),
			'employee_id' => $this->input->post('employee_id'),
			'added_by' => $this->input->post('user_id'),
			'created_at' => date('Y-m-d H:i:s'),
			
			);

			$data = $this->security->xss_clean($data);
			$result = $this->Department_model->add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_add_department');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}
		
		// Validate and update info in database
		public function update() 
		{
			if($this->input->post('edit_type')=='department') {			
			
			$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				// Check validation for user input
				$this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('location_id', 'Location', 'trim|required|xss_clean');
				$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();	
				/* Server side PHP input validation */
				if($this->input->post('department_name')==='') {
					$Return['error'] = $this->lang->line('error_department_field');
				} else if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');
				} else if($this->input->post('location_id')==='') {
					$Return['error'] = $this->lang->line('xin_location_field_error');
				} 
						
				if($Return['error']!=''){
					$this->output($Return);
				}
			
				$data = array(
				'department_name' => $this->input->post('department_name'),
				'company_id' => $this->input->post('company_id'),
				'location_id' => $this->input->post('location_id'),
				'employee_id' => $this->input->post('employee_id'),
				);
				$data = $this->security->xss_clean($data);
				$result = $this->Department_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_success_update_department');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
			}
		}
		
		public function delete() 
		{
			if($this->input->post('is_ajax')==2) {
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('');
				}
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				if(is_numeric($keywords[0])) {
					$id = $keywords[0];
					$id = $this->security->xss_clean($id);

					$jum_karyawan = $this->Employees_model->get_total_employees_departemen($id);
					
					if( $jum_karyawan[0]->jumlah != 0 ){						
					
						$Return['result'] = 'Tidak Bisa Hapus karena Masih ada '.$jum_karyawan[0]->jumlah.' Karyawan yang terhubung dengan departemen ini';	
					
					} else {										
						$result = $this->Department_model->delete_record($id);
						if(isset($id)) {
							$Return['result'] = $this->lang->line('xin_success_delete_department');
						} else {
							$Return['error'] = $this->lang->line('xin_error_msg');
						}
					}			
					
					$this->output($Return);
				}
			}
		}

	// ======================================================================================================
	// TAMPILKAN
	// ======================================================================================================

		// get company => employees
		public function get_employees() 
		{

			$data['title'] = $this->Core_model->site_title();
			$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				$data = array(
					'company_id' => $id
					);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$data = $this->security->xss_clean($data);
					$this->load->view("admin/department/get_employees", $data);
				} else {
					redirect('admin/');
				}
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		 
		// get company => employees
		public function get_company_locations() 
		{
			$data['title'] = $this->Core_model->site_title();
			$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				$data = array(
					'company_id' => $id
					);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$data = $this->security->xss_clean($data);
					$this->load->view("admin/department/get_company_locations", $data);
				} else {
					redirect('admin/');
				}
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		 
		// get location => departments
		public function get_location_departments() 
		{
			$data['title'] = $this->Core_model->site_title();
			$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				$data = array(
					'company_id' => $id
					);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$data = $this->security->xss_clean($data);
					$this->load->view("admin/department/get_company_locations", $data);
				} else {
					redirect('admin/');
				}
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
	 
	// ======================================================================================================
	// END
	// ======================================================================================================	
	
}
