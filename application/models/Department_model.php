<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class department_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_departments() {
	  	$sql = 'SELECT * FROM xin_departments ORDER BY company_id ASC';		
		$query = $this->db->query($sql);
		return $query;
	}
	public function get_sub_departments() {
	  return $this->db->get("xin_sub_departments");
	}
	 
	public function read_company_information($id) {
	
		$sql = 'SELECT * FROM xin_companies WHERE company_id = ? ';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_department_information($id) {
		
		if (is_array($id)) {
            $query = $this->db->where_in('department_id', $id);
        } else {
            $query = $this->db->where('department_id', $id);
        }

        $query = $query->order_by('urut ASC')->get('xin_departments');

		// $sql = 'SELECT * FROM xin_departments WHERE department_id = ? ORDER by urut ASC';
		// $binds = array($id);
		// $query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function get_department_subdepartments($company_id) {
	
		$sql = 'SELECT * FROM xin_sub_departments WHERE department_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function get_company_departments($company_id) {
	
		$sql = 'SELECT * FROM xin_departments WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function read_sub_department_info($id) {
	
		$sql = 'SELECT * FROM xin_sub_departments WHERE sub_department_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get single record > company | locations
	 public function ajax_location_information($id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get single record > company | locations
	 public function ajax_company_location_information($id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get single record > company | locations
	 public function ajax_location_departments_information($id) {
	
		$sql = 'SELECT * FROM xin_departments WHERE location_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	 public function ajax_location_employee_info($id) {
	
	
		$sql = "SELECT * FROM xin_employees WHERE location_id = ? and is_active='1' ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single record > company | employees
	 public function ajax_company_employee_info($id) {
	
	
		$sql = "SELECT * FROM xin_employees WHERE company_id = ? and is_active='1' ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single record > company | employees
	 public function ajax_periode_info($id) {
	
	
		$sql = "SELECT * FROM xin_payroll_date_periode WHERE month_payroll = ? ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function ajax_periode_harian_info($id) {
	
	
		$sql = "SELECT * FROM xin_payroll_date_periode WHERE month_payroll = ? AND jenis = 2 ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function ajax_periode_borongan_info($id) {
	
	
		$sql = "SELECT * FROM xin_payroll_date_periode WHERE month_payroll = ? AND jenis = 3 ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function ajax_company_employee_info_bulanan($id) {
	
	
		$sql = "SELECT * FROM xin_employees WHERE company_id = ? and is_active='1' and wages_type = '1' ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function ajax_company_employee_info_harian($id) {
	
	
		$sql = "SELECT * FROM xin_employees WHERE company_id = ? and is_active='1' and wages_type = '2' ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	 public function ajax_company_employee_info_wages($id) {
	
	
		$sql = "SELECT * FROM view_employees_wages WHERE wages_type = ? and is_active='1' and office_id ='R' ORDER BY first_name ASC";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	 public function ajax_company_employee_info_wages_shift($id) {
	
	
		$sql = "SELECT * FROM view_employees_wages WHERE wages_type = ? and is_active='1' and office_id ='S' ORDER BY first_name ASC";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		// echo "<pre>";
		// print_r($this->db->last_query());
		// echo "</pre>";
		// die();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function ajax_company_employee_info_shift($id) {
	
	
		$sql = "SELECT * FROM xin_employees WHERE company_id = ? and is_active='1' and office_id='S' ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	
	 public function ajax_company_employee_info_resign($id) {
	
	
		$sql = "SELECT * FROM view_employees_resign WHERE company_id = ? and is_active ='1' ";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_departments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table
	public function add_sub($data){
		$this->db->insert('xin_sub_departments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('department_id', $id);
		$this->db->delete('xin_departments');
		
	}
	// Function to Delete selected record from table
	public function delete_sub_record($id){
		$this->db->where('sub_department_id', $id);
		$this->db->delete('xin_sub_departments');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('department_id', $id);
		$data = $this->security->xss_clean($data);
		if( $this->db->update('xin_departments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table
	public function update_sub_record($data, $id){
		$this->db->where('sub_department_id', $id);
		$data = $this->security->xss_clean($data);
		if( $this->db->update('xin_sub_departments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all departments
	public function all_departments()
	{
	  $query = $this->db->query("SELECT * from xin_departments");
  	  return $query->result();
	}

	public function all_departments_birdnest()
	{
	  $query = $this->db->query("SELECT * FROM xin_departments WHERE company_id = 1 ");
  	  return $query->result();
	}

	public function all_departments_trading()
	{
	  $query = $this->db->query("SELECT * FROM xin_departments WHERE company_id = 2 ");
  	  return $query->result();
	}

	public function all_departments_asa()
	{
	  $query = $this->db->query("SELECT * FROM xin_departments WHERE company_id = 3 ");
  	  return $query->result();
	}
	
	public function is_department_head($id) {

        $condition = "employee_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_departments');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
?>