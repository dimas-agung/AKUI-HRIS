<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class instansi_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_instansi()
	{
	  return $this->db->get("xin_instansi_applications");
	}

	public function get_instansies() 
	{		 
	    $query = $this->db->query("SELECT * FROM xin_instansi_applications");
  	    return $query->result();
	}
	 
	public function read_instansi_information($id) {
	
		$sql = 'SELECT * FROM xin_instansi_applications WHERE instansi_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function get_company_office_instansi($company_id) {
	
		$sql = 'SELECT * FROM xin_instansi_applications WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_instansi_applications', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('instansi_id', $id);
		$this->db->delete('xin_instansi_applications');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('instansi_id', $id);
		if( $this->db->update('xin_instansi_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id){
		$this->db->where('instansi_id', $id);
		if( $this->db->update('xin_instansi_applications',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all office instansi
	public function all_office_instansi() {
	  $query = $this->db->query("SELECT * from xin_instansi_applications");
  	  return $query->result();
	}

	// get all office instansi
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