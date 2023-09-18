<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
class designation_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_designations()
	{
	  return $this->db->get("xin_designations");
	}
	 
	 public function read_designation_information($id) {
		if (is_array($id)) {
            $query = $this->db->where_in('designation_id', $id);
        } else {
            $query = $this->db->where('designation_id', $id);
        }

        $query = $query->get('xin_designations');

		// $sql = 'SELECT * FROM xin_designations WHERE designation_id = ?';
		// $binds = array($id);
		// $query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_designation_workstation_information($id) {
	
		$sql = 'SELECT * FROM view_designation_workstation WHERE designation_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	 public function read_wages_information($id) {
	
		$sql = 'SELECT * FROM xin_payroll_jenis WHERE jenis_gaji_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_pola_information($id) {
	
		$sql = 'SELECT * FROM xin_payroll_pola WHERE pola_name = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_jadwal_reguler_information($id) {
	
		$sql = 'SELECT * FROM xin_office_reguler WHERE office_shift_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_jadwal_shift_information($id) {
	
		$sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ?';
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
		$this->db->insert('xin_designations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('designation_id', $id);
		$this->db->delete('xin_designations');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('designation_id', $id);
		if( $this->db->update('xin_designations',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	
	// get all designations
	public function all_designations()
	{
	  $query = $this->db->query("SELECT * from xin_designations  ORDER BY designation_name ASC");
  	  return $query->result();
	}
	
	// get department > designations
	public function ajax_designation_information($id) {
	
		$sql = 'SELECT * FROM xin_designations WHERE sub_department_id = ?  ORDER BY designation_name ASC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_designation_departments_info($id,$yd) 
	{	
		$sql = 'SELECT * FROM xin_designations WHERE company_id = ? AND department_id = ?  ORDER BY designation_name ASC';
		$binds = array($id,$yd);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}

		
	}

	// get department > designations
	public function ajax_is_designation_information($id) {
	
		$sql = 'SELECT * FROM xin_designations WHERE department_id = ? ORDER BY designation_name ASC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function ajax_is_designation_workstation_information($id) {
	
		$sql = 'SELECT * FROM xin_designations WHERE workstation_id = ? ORDER BY designation_name ASC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		// echo "<pre>";
		// print_r($this->db->last_query());
		// echo "</pre>";
		// die();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// get company > designations
	public function ajax_company_designation_info($id) {
	
		$sql = 'SELECT * FROM xin_designations WHERE company_id = ? ORDER BY designation_name ASC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		// echo "<pre>";
		// print_r($this->db->last_query());
		// echo "</pre>";
		// die();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function get_company_designations($company_id) {
	
		$sql = 'SELECT * FROM xin_designations WHERE company_id = ? ORDER BY designation_name ASC';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
}
?>