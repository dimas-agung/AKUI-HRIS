<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class company_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_companies() 
	{
	  	return $this->db->get("xin_companies");	     
	}

	public function get_training_kategori() 
	{
	  	return $this->db->get("xin_training_kategori");	     
	}

	public function get_training_jenis() 
	{
	  	return $this->db->get("xin_training_types");	     
	}
	
	public function get_company_documents() 
	{
	  return $this->db->get("xin_company_documents");
	}
	
	// company types
	public function get_company() 
	{
		$session  = $this->session->userdata('username');	 
	    $query = $this->db->query("SELECT * FROM xin_companies WHERE company_id in (".$session['companies'].") ");
  	    return $query->result();
	}

	public function get_training_kategori_combo()
	{			 
	    $query = $this->db->query("SELECT * FROM xin_training_kategori ");
  	    return $query->result();
	}

	public function get_vendor() 
	{
		$query = $this->db->query("SELECT * FROM xin_training_vendors ") ;
  	    return $query->result();
	}

	public function get_department() 
	{			 
	    $query = $this->db->query("SELECT * FROM xin_departments WHERE company_id = 1 ") ;
  	    return $query->result();
	}
	public function get_department_by_company($company_id) 
	{			 
	    $query ="SELECT * FROM xin_departments WHERE company_id = ? " ;
		$binds = array($company_id);
		$query = $this->db->query($query, $binds);
		return $query->result();
	}

	public function get_workstation_reports() 
	{			 
	    $query = $this->db->query("SELECT * FROM xin_workstation WHERE company_id = 1 ") ;
  	    return $query->result();
	}

	public function get_workstation() 
	{			 
	    $query = $this->db->query("SELECT * FROM xin_workstation ");
  	    return $query->result();
	}

	public function get_workstation_skala_upah() 
	{			 
	    $query = $this->db->query("SELECT * FROM xin_workstation WHERE workstation_name !='-' ORDER BY workstation_name ASC");
  	    return $query->result();
	}

	public function get_company_types() 
	{
		$query = $this->db->get("xin_company_type");
		return $query->result();
	}

	public function get_company_single($company_id) 
	{	
		$sql = 'SELECT * FROM xin_companies WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	public function get_company_documents_single($company_id) 
	{	
		$sql = 'SELECT * FROM xin_company_documents WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	public function get_all_companies() 
	{
	  $query = $this->db->get("xin_companies");
	  return $query->result();
	}
	 
	public function read_company_information($id) 
	{	
		$sql = 'SELECT * FROM xin_companies WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_company_type($id) 
	{	
		$sql = 'SELECT * FROM xin_company_type WHERE type_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function read_company_document_info($id) 
	{	
		$sql = 'SELECT * FROM xin_company_documents WHERE document_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add($data)
	{
		$this->db->insert('xin_companies', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	
	// Function to add record in table
	public function add_document($data)
	{
		$this->db->insert('xin_company_documents', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id)
	{
		$this->db->where('company_id', $id);
		$this->db->delete('xin_companies');		
	}
	
	// Function to Delete selected record from table
	public function delete_doc_record($id)
	{
		$this->db->where('document_id', $id);
		$this->db->delete('xin_company_documents');		
	}
	
	// Function to update record in table
	public function update_record($data, $id)
	{
		$this->db->where('company_id', $id);
		if( $this->db->update('xin_companies',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id)
	{
		$this->db->where('company_id', $id);
		if( $this->db->update('xin_companies',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_company_document_record($data, $id)
	{
		$this->db->where('document_id', $id);
		if( $this->db->update('xin_company_documents',$data)) 
		{
			return true;
		} else {
			return false;
		}		
	}
	
	// get company > departments
	public function ajax_company_departments_info($id) 
	{	
		$condition = "company_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_departments');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_designation_info($id) 
	{	
		$condition1 = "department_id =" . "'" . $id . "'";
		
		$this->db->select('*');
		$this->db->from('xin_designations');
		$this->db->where($condition1);
		
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_workstations_info($id) 
	{	
		$condition1 = "company_id =" . "'" . $id . "'";
		$condition2 = "workstation_name !='-' ";
		$this->db->select('*');
		$this->db->from('xin_workstation');
		$this->db->where($condition1);
		$this->db->where($condition2);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_workstations_skala_upah_info($id) 
	{	
		$condition1 = "company_id =" . "'" . $id . "' ";
		$condition2 = "workstation_name !='-' ";
		$this->db->select('*');
		$this->db->from('xin_workstation');
		$this->db->where($condition1);
		$this->db->where($condition2);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_workstation_info() 
	{	
		
		$this->db->select('*');
		$this->db->from('xin_workstation');		
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_wages_info($id) 
	{	
		$condition = "jenis_gaji_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_payroll_jenis');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_wages_type() 
	{	
		// $condition = "jenis_gaji_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_payroll_jenis');
		// $this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	Public function ajax_company_pola_info($id) 
	{	
		$condition = "pola_name =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_payroll_pola');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	Public function ajax_company_pola_kerja() 
	{	
		// $condition = "pola_name =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_payroll_pola');
		// $this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function ajax_company_info($id) 
	{	
		$condition = "company_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_companies');
		$this->db->where($condition);
		$this->db->limit(100);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}
?>