<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class workstation_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_workstations()
	{
	  return $this->db->get("xin_workstation");
	}
	 
	public function read_workstation_designation($workstation_id) {
	
		$sql = 'SELECT * FROM xin_designations WHERE workstation_id = ? ORDER BY designation_name ASC';
		$binds = array($workstation_id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
		
	}

	 public function read_workstation_information($id) {
	
		$sql = 'SELECT * FROM xin_workstation WHERE workstation_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function get_company_office_workstation($company_id) {
	
		$sql = 'SELECT * FROM xin_workstation WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_workstation', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('workstation_id', $id);
		$this->db->delete('xin_workstation');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('workstation_id', $id);
		if( $this->db->update('xin_workstation',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id){
		$this->db->where('workstation_id', $id);
		if( $this->db->update('xin_workstation',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all office workstations
	public function all_office_workstations() {
	  $query = $this->db->query("SELECT * from xin_workstation");
  	  return $query->result();
	}

	// get all office workstations
	public function all_payroll_jenis() {
	  $query = $this->db->query("SELECT * from xin_payroll_jenis");
  	  return $query->result();
	}

	public function all_payroll_pola() {
	  $query = $this->db->query("SELECT * from xin_payroll_pola");
  	  return $query->result();
	}
}

?>