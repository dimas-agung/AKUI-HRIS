<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class perjanjian_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_perjanjian()
	{
	  return $this->db->get("xin_perjanjian_applications");
	}
	 
	public function read_perjanjian_information($id) {
	
		$sql = 'SELECT * FROM xin_perjanjian_applications WHERE perjanjian_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function get_company_office_perjanjian($company_id) {
	
		$sql = 'SELECT * FROM xin_perjanjian_applications WHERE perjanjian_type_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_perjanjian_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('perjanjian_id', $id);
		$this->db->delete('xin_perjanjian_applications');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('perjanjian_id', $id);
		if( $this->db->update('xin_perjanjian_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id){
		$this->db->where('perjanjian_id', $id);
		if( $this->db->update('xin_perjanjian_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all office perjanjian
	public function all_office_perjanjian() {
	  $query = $this->db->query("SELECT * from xin_perjanjian_applications");
  	  return $query->result();
	}

	
}

?>