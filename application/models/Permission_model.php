<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Permission_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db2 = $this->load->database('finger', TRUE);
    }
 
	// get office shifts
	public function get_office_shifts() {
	  return $this->db->get("xin_office_shift");
	}
	

	// get all tasks
	public function get_tasks() {
	  return $this->db->get("xin_tasks");
	}
	
	// get all project tasks
	public function get_project_tasks($id) {
		$sql = 'SELECT * FROM xin_tasks WHERE project_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get all project variations
	public function get_project_variations($id) {
		$sql = 'SELECT * FROM xin_project_variations WHERE project_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
			
	// check if check-in available
	public function attendance_first_in_check($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// Masuk
	public function attendance_first_in_check_new($employee_nik,$attendance_date) {
	
		$sql = 'SELECT * FROM view_absen_masuk WHERE nik = ? and attendance_date = ?';
		$binds = array($employee_nik,$attendance_date);
		$query = $this->db2->query($sql, $binds);

		return $query;
	}

	public function attendance_first_in_new($employee_nik,$attendance_date) {
	
		$sql = 'SELECT * FROM view_absen_masuk WHERE nik = ? and attendance_date = ? limit 1';
		$binds = array($employee_nik,$attendance_date);
		$query = $this->db2->query($sql, $binds);
		
		return $query->result();
	}
	
	// Pulang
	public function attendance_first_out_check_new($employee_nik,$attendance_date) {
	
		$sql = 'SELECT * FROM view_absen_pulang WHERE nik = ? and attendance_date = ? ';
		$binds = array($employee_nik,$attendance_date);
		$query = $this->db2->query($sql, $binds);
		
		// print_r($this->db->last_query());
		// die();

		return $query;
	}

	public function attendance_first_out_new($employee_nik,$attendance_date) {
	
		$sql = 'SELECT * FROM view_absen_pulang WHERE nik = ? and attendance_date = ? limit 1';
		$binds = array($employee_nik,$attendance_date);
		$query = $this->db2->query($sql, $binds);
		
		return $query->result();
	}

	// get user attendance
	public function attendance_time_check($employee_id) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ?';
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// check if check-in available
	public function attendance_first_in($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ?';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}

	
	
	
	
	// check if check-out available
	public function attendance_first_out_check($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? order by time_attendance_id desc limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	

	// get leave types
	public function all_leave_types() {
	  $query = $this->db->get("xin_leave_type");
	  return $query->result();
	}

	public function all_sick_types() {
		$query = $this->db->get("xin_sick_type");
		return $query->result();
	  }

	public function all_izin_types() {
		$query = $this->db->get("xin_izin_type");
		return $query->result();
	  }

	public function all_libur_types() {
		$query = $this->db->get("xin_libur_type");
		return $query->result();
	  }

	// get company offshifts
	public function get_company_shifts($company_id) {
	
		$sql = 'SELECT * FROM xin_office_shift WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company holidays
	public function get_company_holidays($company_id) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// filter company holidays
	public function filter_company_holidays($company_id) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// filter company|status holidays
	public function filter_company_publish_holidays($company_id,$is_publish) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE company_id = ? and is_publish = ?';
		$binds = array($company_id,$is_publish);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// filter company|status holidays
	public function filter_notcompany_publish_holidays($is_publish) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE is_publish = ?';
		$binds = array($is_publish);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company leaves
	public function get_company_leaves($company_id) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get multi company leaves
	public function get_multi_company_leaves($company_ids) {
	
		$sql = 'SELECT * FROM xin_leave_applications where company_id IN ?';
		$binds = array($company_ids);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get company sicks
	public function get_company_sicks($company_id) {
	
		$sql = 'SELECT * FROM xin_sick_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get multi company sicks
	public function get_multi_company_sicks($company_ids) {
	
		$sql = 'SELECT * FROM xin_sick_applications where company_id IN ?';
		$binds = array($company_ids);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get company izins
	public function get_company_izins($company_id) {
	
		$sql = 'SELECT * FROM xin_izin_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	
	// get multi company izins
	public function get_multi_company_izins($company_ids) {
	
		$sql = 'SELECT * FROM xin_izin_applications where company_id IN ?';
		$binds = array($company_ids);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get company liburs
	public function get_company_liburs($company_id) {
	
		$sql = 'SELECT * FROM xin_libur_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get multi company liburs
	public function get_multi_company_liburs($company_ids) {
	
		$sql = 'SELECT * FROM xin_libur_applications where company_id IN ?';
		$binds = array($company_ids);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get company tasks
	public function get_company_tasks($company_id) {
	
		$sql = 'SELECT * FROM xin_tasks WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get employee tasks
	public function get_employee_tasks($id) {
	
		$sql = "SELECT * FROM `xin_tasks` where assigned_to like '%$id,%' or assigned_to like '%,$id%' or assigned_to = '$id'";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
		
	// check if check-out available
	public function attendance_first_out($employee_id,$attendance_date) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? order by time_attendance_id desc limit 1';
		$binds = array($employee_id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
	
	// get total hours work > attendance
	public function total_hours_worked_attendance($id,$attendance_date) {
		
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? and total_work != ""';
		$binds = array($id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get total rest > attendance
	public function total_rest_attendance($id,$attendance_date) {
		
		$sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? and total_rest != ""';
		$binds = array($id,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	
	public function get_sicks() {
		$session = $this->session->userdata('username');		
		$sql = "SELECT * FROM xin_sick_applications WHERE company_id in (".$session['companies'].") ORDER BY applied_on DESC";
		$binds = array();
		$query = $this->db->query($sql, $binds);
		return $query;	
	}

	public function get_izins() {
		$session = $this->session->userdata('username');	
		$sql = "SELECT * FROM xin_izin_applications WHERE company_id in (".$session['companies'].")  ORDER BY applied_on DESC";
		$binds = array();
		$query = $this->db->query($sql, $binds);
		return $query;		
	}

	public function get_liburs() {
		$session = $this->session->userdata('username');	
		$sql = "SELECT * FROM xin_libur_applications WHERE company_id in (".$session['companies'].")  ORDER BY applied_on DESC";
		$binds = array();
		$query = $this->db->query($sql, $binds);
		return $query;		
	}

	// get all leaves
	public function get_leaves() {
		$session = $this->session->userdata('username');	
		$sql = "SELECT * FROM xin_leave_applications WHERE company_id in (".$session['companies'].")  ORDER BY applied_on DESC";
		$binds = array();
		$query = $this->db->query($sql, $binds);
		return $query;
	  
	}
	
	// get company leaves
	public function filter_company_leaves($company_id) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company|employees leaves
	public function filter_company_employees_leaves($company_id,$employee_id) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and employee_id = ?';
		$binds = array($company_id,$employee_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company|employees leaves
	public function filter_company_employees_status_leaves($company_id,$employee_id,$status) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and employee_id = ? and status = ?';
		$binds = array($company_id,$employee_id,$status);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get company|employees leaves
	public function filter_company_only_status_leaves($company_id,$status) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and status = ?';
		$binds = array($company_id,$status);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get all employee leaves
	public function get_employee_leaves($id) {
		
		$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
		
	}	
	
	// get all holidays
	public function get_holidays() {
		
		$sql = 'SELECT * FROM xin_holidays ORDER BY start_date DESC';		
		$query = $this->db->query($sql);		
		return $query;

	}
	
	// get all holidays>calendar
	public function get_holidays_calendar() {
	  	
		$sql = 'SELECT * FROM xin_holidays WHERE is_publish = ?';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);		
		return $query;

	}
	
	// get all leaves>calendar
	public function get_leaves_request_calendar() {
	  return $query = $this->db->query("SELECT * from xin_leave_applications");
	}

	// get all sick>calendar
	public function get_sick_request_calendar() {
	  return $query = $this->db->query("SELECT * from xin_sick_applications");
	}
	
	// get all izin>calendar
	public function get_izin_request_calendar() {
	  return $query = $this->db->query("SELECT * from xin_izin_applications");
	}

	public function get_libur_request_calendar() {
	  return $query = $this->db->query("SELECT * from xin_libur_applications");
	}

	// check if leave available
	public function leave_date_check($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_leave_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}

	// check if sick available
	public function sick_date_check($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_sick_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? ';

		

		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}

	// check if izin available
	public function izin_date_check($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_izin_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}

	// check if libur available
	public function libur_date_check($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_libur_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// check if leave available
	public function leave_date($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_leave_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}

	// check if sick available
	public function sick_date($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_sick_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}

	// check if izin available
	public function izin_date($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_izin_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}

	// check if libur available
	public function libur_date($emp_id,$attendance_date) {
	
		$sql = 'SELECT * from xin_libur_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
		$binds = array($emp_id,$attendance_date,$attendance_date);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
	
	// get total number of leave > employee
	public function count_total_leaves($leave_type_id,$employee_id,$tahun) {
		
		$sql = 'SELECT * FROM view_hris_jumlah_cuti_cek WHERE employee_id = ? and tahun = ? and leave_type_id = ? and status = ?';
		
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
		// die();

		$binds = array($employee_id,$leave_type_id,2,$tahun);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}

	// get total number of sick > employee
	public function count_total_sicks($sick_type_id,$employee_id) {
		
		$sql = 'SELECT * FROM xin_sick_applications WHERE employee_id = ? and sick_type_id = ? and status = ?';
		$binds = array($employee_id,$sick_type_id,2);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}


	// get total number of izin > employee
	public function count_total_izins($izin_type_id,$employee_id) {
		
		$sql = 'SELECT * FROM xin_izin_applications WHERE employee_id = ? and izin_type_id = ? and status = ?';
		$binds = array($employee_id,$izin_type_id,2);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}

	// get total number of izin > employee
	public function count_total_liburs($libur_type_id,$employee_id) {
		
		$sql = 'SELECT * FROM xin_libur_applications WHERE employee_id = ? and libur_type_id = ? and status = ?';
		$binds = array($employee_id,$libur_type_id,2);
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}
	
	
	// get payroll templates > NOT USED
	public function attendance_employee_with_date($emp_id,$attendance_date) {
		
		$sql = 'SELECT * FROM xin_attendance_time where attendance_date = ? and employee_id = ?';
		$binds = array($attendance_date,$emp_id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
		 
	//  // get record of office shift > by id
	//  public function read_office_shift_information($id) {
	
	// 	$sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? limit 1';
	// 	$binds = array($id);
	// 	$query = $this->db->query($sql, $binds);
		
	// 	if ($query->num_rows() > 0) {
	// 		return $query->result();
	// 	} else {
	// 		return false;
	// 	}
	// }
	
	// get record of leave > by id
	 public function read_leave_information($id) {
	
		$sql = 'SELECT * FROM xin_leave_applications WHERE leave_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get leave type by id
	public function read_leave_type_information($id) {
	
		$sql = 'SELECT * FROM xin_leave_type WHERE leave_type_id = ? ';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get record of leave > by id
	public function read_sick_information($id) {
	
		$sql = 'SELECT * FROM xin_sick_applications WHERE sick_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get record of leave > by id
	public function read_izin_information($id) {
	
		$sql = 'SELECT * FROM xin_izin_applications WHERE izin_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get record of leave > by id
	public function read_libur_information($id) {
	
		$sql = 'SELECT * FROM xin_libur_applications WHERE libur_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get sick type by id
	public function read_sick_type_information($id) {
	
		$sql = 'SELECT * FROM xin_sick_type WHERE sick_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get izin type by id
	public function read_izin_type_information($id) {
	
		$sql = 'SELECT * FROM xin_izin_type WHERE izin_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get libur type by id
	public function read_libur_type_information($id) {
	
		$sql = 'SELECT * FROM xin_libur_type WHERE libur_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// Function to add record in table
	public function add_employee_attendance($data){
		$this->db->insert('xin_attendance_time', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_leave_record($data){
		$this->db->insert('xin_leave_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_sick_record($data){
		$this->db->insert('xin_sick_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_izin_record($data){
		$this->db->insert('xin_izin_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_libur_record($data){
		$this->db->insert('xin_libur_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function add_task_record($data){
		$this->db->insert('xin_tasks', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_project_variations($data){
		$this->db->insert('xin_project_variations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_office_shift_record($data){
		$this->db->insert('xin_office_shift', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_holiday_record($data){
		$this->db->insert('xin_holidays', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// get record of task by id
	 public function read_task_information($id) {
	
		$sql = 'SELECT * FROM xin_tasks WHERE task_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get record of variation by id
	 public function read_variation_information($id) {
	
		$sql = 'SELECT * FROM xin_project_variations WHERE variation_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get record of holiday by id
	 public function read_holiday_information($id) {
	
		$sql = 'SELECT * FROM xin_holidays WHERE holiday_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get record of attendance by id
	 public function read_attendance_information($id) {
	
		$sql = 'SELECT * FROM xin_attendance_time WHERE time_attendance_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_attendance_record($id){ 
		$this->db->where('time_attendance_id', $id);
		$this->db->delete('xin_attendance_time');
		
	}
	
	// Function to Delete selected record from table
	public function delete_task_record($id){ 
		$this->db->where('task_id', $id);
		$this->db->delete('xin_tasks');
		
	}
	// Function to Delete selected record from table
	public function delete_variation_record($id){ 
		$this->db->where('variation_id', $id);
		$this->db->delete('xin_project_variations');
		
	}
	
	// Function to Delete selected record from table
	public function delete_holiday_record($id){ 
		$this->db->where('holiday_id', $id);
		$this->db->delete('xin_holidays');
		
	}
	
	// Function to Delete selected record from table
	public function delete_shift_record($id){ 
		$this->db->where('office_shift_id', $id);
		$this->db->delete('xin_office_shift');
		
	}
	
	// Function to Delete selected record from table
	public function delete_leave_record($id){ 
		$this->db->where('leave_id', $id);
		$this->db->delete('xin_leave_applications');
		
	}

	// Function to Delete selected record from table
	public function delete_sick_record($id){ 
		$this->db->where('sick_id', $id);
		$this->db->delete('xin_sick_applications');
		
	}

	// Function to Delete selected record from table
	public function delete_izin_record($id){ 
		$this->db->where('izin_id', $id);
		$this->db->delete('xin_izin_applications');
		
	}

	// Function to Delete selected record from table
	public function delete_libur_record($id){ 
		$this->db->where('libur_id', $id);
		$this->db->delete('xin_libur_applications');
		
	}
	
	// Function to update record in table
	public function update_task_record($data, $id){
		$this->db->where('task_id', $id);
		if( $this->db->update('xin_tasks',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table
	public function update_project_variations($data, $id){
		$this->db->where('variation_id', $id);
		if( $this->db->update('xin_project_variations',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_leave_record($data, $id){
		$this->db->where('leave_id', $id);
		if( $this->db->update('xin_leave_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to update record in table
	public function update_sick_record($data, $id){
		$this->db->where('sick_id', $id);
		if( $this->db->update('xin_sick_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to update record in table
	public function update_izin_record($data, $id){
		$this->db->where('izin_id', $id);
		if( $this->db->update('xin_izin_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to update record in table
	public function update_libur_record($data, $id){
		$this->db->where('libur_id', $id);
		if( $this->db->update('xin_libur_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_holiday_record($data, $id){
		$this->db->where('holiday_id', $id);
		if( $this->db->update('xin_holidays',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_attendance_record($data, $id){
		$this->db->where('time_attendance_id', $id);
		if( $this->db->update('xin_attendance_time',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_shift_record($data, $id){
		$this->db->where('office_shift_id', $id);
		if( $this->db->update('xin_office_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}	
	
	// Function to update record in table
	public function update_default_shift_record($data, $id){
		$this->db->where('office_shift_id', $id);
		if( $this->db->update('xin_office_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_default_shift_zero($data){
		$this->db->where("office_shift_id!=''");
		if( $this->db->update('xin_office_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function assign_task_user($data, $id){
		$this->db->where('task_id', $id);
		if( $this->db->update('xin_tasks',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get comments
	public function get_comments($id) {
		
		$sql = 'SELECT * FROM xin_tasks_comments WHERE task_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get comments
	public function get_attachments($id) {
		
		$sql = 'SELECT * FROM xin_tasks_attachment WHERE task_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// Function to add record in table > add comment
	public function add_comment($data){
		$this->db->insert('xin_tasks_comments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_comment_record($id){
		$this->db->where('comment_id', $id);
		$this->db->delete('xin_tasks_comments');
		
	}
	
	// Function to Delete selected record from table
	public function delete_attachment_record($id){
		$this->db->where('task_attachment_id', $id);
		$this->db->delete('xin_tasks_attachment');
		
	}
	
	// Function to add record in table > add attachment
	public function add_new_attachment($data){
		$this->db->insert('xin_tasks_attachment', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// check user attendance 
	public function check_user_attendance() {
		$today_date = date('Y-m-d');
		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? order by time_attendance_id desc limit 1';
		$binds = array($session['user_id'],$today_date);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// check user attendance 
	public function check_user_attendance_clockout() {
		$today_date = date('Y-m-d');
		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? and clock_out = ? order by time_attendance_id desc limit 1';
		$binds = array($session['user_id'],$today_date,'');
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	//  set clock in- attendance > user
	public function add_new_attendance($data){
		$this->db->insert('xin_attendance_time', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// get last user attendance 
	public function get_last_user_attendance() {

		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? order by time_attendance_id desc limit 1';
		$binds = array($session['user_id']);
		$query = $this->db->query($sql, $binds);
	
		return $query->result();
	}
	
	// get last user attendance > check if loged in-
	public function attendance_time_checks($id) {

		$session = $this->session->userdata('username');
		$sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and clock_out = ? order by time_attendance_id desc limit 1';
		$binds = array($id,'');
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// Function to update record in table > update attendace.
	public function update_attendance_clockedout($data,$id){
		//$this->db->where("time_attendance_id!=''");
		$this->db->where('time_attendance_id', $id);
		if( $this->db->update('xin_attendance_time',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// get employees > active
	public function get_xin_employees() {
		
		$sql = 'SELECT * FROM xin_employees WHERE is_active = ? and user_role_id!=1';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}
	
	// get all employee leaves>department wise
	public function get_employee_leaves_department_wise($department_id) {
		
		$sql = 'SELECT * FROM xin_leave_applications WHERE department_id = ?';
		$binds = array($department_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get total number of leave > employee
	public function employee_count_total_leaves($leave_type_id,$employee_id) {
		
		// $sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ? and created_at >= DATE_SUB(NOW(),INTERVAL 1 YEAR)';
		$sql = 'SELECT * FROM view_hris_jumlah_cuti_cek WHERE  leave_type_id = ?  and employee_id = ? and status = ?';
		$binds = array($leave_type_id,$employee_id,2);
		$query = $this->db->query($sql, $binds);

		
		return $query->num_rows();
	}
	
	// get total number of leave > employee
	public function employee_show_last_leave($tahun,$tanggal, $employee_id,$leave_id) {

		// $tahun = date('Y');

		$sql = "SELECT * FROM view_hris_employee_leave WHERE tahun ='".$tahun."' AND created_at < '".$tanggal."' AND leave_id != '".$leave_id."' and employee_id = ? order by leave_id desc limit 1";
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}
	// get total number of sick > employee
	public function employee_show_last_sick($employee_id,$sick_id) {
		$sql = "SELECT * FROM xin_sick_applications WHERE sick_id != '".$sick_id."' and employee_id = ? order by sick_id desc limit 1";
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	// get total number of izin > employee
	public function employee_show_last_izin($employee_id,$izin_id) {
		$sql = "SELECT * FROM xin_izin_applications WHERE izin_id != '".$izin_id."' and employee_id = ? order by izin_id desc limit 1";
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	// get total number of izin > employee
	public function employee_show_last_libur($employee_id,$libur_id) {
		$sql = "SELECT * FROM xin_libur_applications WHERE libur_id != '".$libur_id."' and employee_id = ? order by libur_id desc limit 1";
		$binds = array($employee_id);
		$query = $this->db->query($sql, $binds);
		return $query->result();
	}

	public function count_attendance_hadir($location_id,$attendance_date) {
		
		$sql = 'SELECT count(*) as jumlah FROM xin_attendance_time WHERE location_id = ? and attendance_date = ? and attendance_status = ?';
		$binds = array($location_id,$attendance_date,'Hadir');
		$query = $this->db->query($sql, $binds);

		return $query->result();
	}
}
?>