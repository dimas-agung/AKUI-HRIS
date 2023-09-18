<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class resignation_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_resignations()
	{
		$sql = 'SELECT * FROM view_employees_resign ';		
		$query = $this->db->query($sql);
		return $query;

	  // return $this->db->get("view_employees_resign");
	}
	 
	 public function read_resignation_information($id) {
	
		$sql = 'SELECT * FROM view_employees_resign WHERE resignation_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	// get company resignations
	public function get_company_resignations($company_id) {
	
		$sql = 'SELECT * FROM view_employees_resign WHERE company_id = ? ';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function get_employee_resignation($id) {
		
		$sql = 'SELECT * FROM view_employees_resign WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
	 	return $query;
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_employee_resignations', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('resignation_id', $id);
		$this->db->delete('xin_employee_resignations');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('resignation_id', $id);
		if( $this->db->update('xin_employee_resignations',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>