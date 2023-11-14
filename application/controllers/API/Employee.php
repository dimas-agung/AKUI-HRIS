<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		//load the models
		$this->load->model("Employees_model");
		$this->load->model("Core_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
		$this->load->model("Company_model");
		$this->load->model("Timesheet_model");
		$this->load->library("pagination");
		$this->load->library('Pdf');
		$this->load->helper('string');
	}

	/*Function to set JSON output*/
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function index()
	{
		$employeeAll    = $this->Employees_model->get_employees()->result();
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($employeeAll);
	}
	public function DataBawahanLangsung()
	{
        $employee_id = $this->input->get('id');
        $sql = "SELECT * FROM xin_employees WHERE reports_to ='" . $employee_id . "' ";
        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}
	public function DataBawahanTidakLangsung()
	{
        $employee_id = $this->input->get('id');
        $sql = "SELECT * FROM xin_employees WHERE superior_reports_to ='" . $employee_id . "' ";
        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}

	public function CheckEmployeeExist()
	{
        $employee_id = $this->input->get('id');
        $sql = "SELECT user_id,employee_id,first_name,wages_type as jenis_gaji,
		last_name,
		(CASE
			WHEN grade_type = 'Grade 1' and grade_type != '' then 0
			ELSE 1
		END) as approval_access,
		reports_to as atasan_langsung_id, superior_reports_to as superior_atasan_langsung_id FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $data = $this->db->query($sql)->result();

        $sql1 = "SELECT COUNT(employee_id) as jumlah FROM xin_employees WHERE reports_to ='" . $data[0]->user_id . "' ";
		// echo $sql;return;
        $data1 = $this->db->query($sql1)->row();
        $sql2 = "SELECT COUNT(employee_id) as jumlah FROM xin_employees WHERE superior_reports_to ='" . $data[0]->user_id . "' ";
		// echo $sql;return;
        $data2 = $this->db->query($sql2)->row();
		$approval_level = 0;
		if ($data1->jumlah > 0) {
			$approval_level = 1;
			if ($data2->jumlah > 0) {
				$approval_level = 2;
				# code...
			}
		}
        $jenis_gaji = '';
        if ($data[0]->jenis_gaji == 1) {
            $jenis_gaji = 'Bulanan';
        };
        if ($data[0]->jenis_gaji == 2) {
            $jenis_gaji = 'Harian';
        };
        if ($data[0]->jenis_gaji == 3) {
            $jenis_gaji = 'Borongan';
        };
		$data[0]->jenis_gaji = $jenis_gaji;
		$data[0]->approval_access = $approval_level;

		
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}

	function checkSisaCutiTahunan() {
		$employee_id = $this->input->get('id');
        $sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $r = $this->db->query($sql)->row();
		$tanggal_cuti = date("d", strtotime($r->date_of_leaving));

		$bulan_cuti = date("m", strtotime($r->date_of_leaving));

		$tahun_cuti = date("Y", strtotime($r->date_of_leaving));
		// $tahun_now = date("Y", strtotime($r->date_of_now));
		$tahun_now = date("Y");

		if ( $bulan_cuti < 12 ){
		  if ($tahun_cuti < $tahun_now  ) {
			  $hak_cuti   = 12;
		  } else {
			if ($tanggal_cuti >= 1 and $tanggal_cuti <= 15) {
			  $hak_cuti   = 12-$bulan_cuti+1;

			} else if ($tanggal_cuti >= 16 and $tanggal_cuti <= 31) {
			  $hak_cuti   = 12-$bulan_cuti;
			}
		  }
		} else {
		  $hak_cuti   = 12;
		}
		//cek cuti terpakai
		$sql = 'SELECT sum(cuti) as jumlah FROM view_hris_jumlah_cuti_cek WHERE employee_id = ? AND tahun = ? ';
        $binds = array($r->user_id, $tahun_now);
        $query = $this->db->query($sql, $binds)->row();
		$cuti_terpakai = $query->jumlah;
		$sisa_cuti = (int)$hak_cuti-$cuti_terpakai;
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($sisa_cuti);
	}
	public function getAttendance()
    {
        $employee_id = $this->input->get('employee_id');
        // $employee_id = 'JBG-2021-824';
		$sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $period = date('Y-m');
        // $period = '2023-10';
        $employee = $this->db->query($sql)->row();
        $sql1 = "SELECT 
        * FROM xin_attendance_time WHERE
        employee_id ='" . $employee->user_id . "' AND DATE_FORMAT(attendance_date,'%Y-%m') = '".$period."' order by attendance_date  ";
        // $sql1 = "SELECT 
        // * FROM xin_attendance_time WHERE
        // employee_id ='1851' AND DATE_FORMAT(attendance_date,'%Y-%m') = '2023-01' order by attendance_date  ";

        $query1   = $this->db->query($sql1);
        // echo $sql1;
        $data = array();

        $no = 1;
        $dataInsert = [];
       
            # code...
            foreach ($query1->result() as $r) {
                // user full name
                $user_info      = $this->Employees_model->get_employees_by_user_id($r->employee_id);
                // var_dump($user_info);return;
                if (!empty($user_info)) {
                    $user_id        = $user_info->user_id;
                    $company_name        = $user_info->company_name;
                    $full_name      = $user_info->first_name . ' ' . $user_info->last_name;
                    $designation_name  = $user_info->designation_name;
                    $department_id  = $user_info->department_id;
                    $designation_id = $user_info->designation_id;
                    $start_join = $user_info->date_of_joining;
                } else {
                    $user_id        = '';
                    $emp_nik        = '';
                    $designation_name  = '';
                    $company_name        = '';
                    $full_name      = '';
                    $department_id  = '';
                    $designation_id = '';
                    $designation_id = '';
                }
        
                $attendance_status = $r->attendance_status;
                $attendance_keterangan = $r->attendance_keterangan;
                $time_late = $r->time_late;
                $early_leaving = $r->early_leaving;
                $overtime = $r->overtime;
                $total_work = $r->total_work;
                $attendance_jadwal = $r->attendance_jadwal;
                $jam_masuk = $r->clock_in;
                $jam_pulang = $r->clock_out;
                $timestamp = strtotime($r->attendance_date);

                $day = date('w', $timestamp);
                $hari = '';
                switch ($day) {
                    case 0:
                       $hari = "Minggu";
                        break;
                    case 1:
                       $hari = "Senin";
                        break;
                    case 2:
                       $hari = "Selasa";
                        break;
                    case 3:
                       $hari = "Rabu";
                        break;
                    case 4:
                       $hari = "Kamis";
                        break;
                    case 5:
                       $hari = "Jumat";
                        break;
                    
                    case 6:
                       $hari = "Sabtu";
                        break;
                    
                    default:
                        # code...
                        break;
                }
                $d_date = $this->Core_model->set_date_format($r->attendance_date);
    
                $data[] = array(
                    'no' => $no,
                    'tanggal' => $d_date,
                    'hari' => $hari,
                    'jam_kerja' => $attendance_jadwal,
                    'jam_masuk' => $jam_masuk,
                    'jam_pulang' => $jam_pulang,
                    'status' => $attendance_status,
                    'terlambat' => $time_late,
                    'pulang_cepat' => $early_leaving,
                    'lembur' => $overtime,
                    'total_jam_kerja' => $total_work,
                    'keterangan' => $attendance_keterangan
                );
                $no++;
                // break;
                // }
            }
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit();
    }
    function payslip_harian() {
        // $employee_id = $this-s>input->get('user_id');
        $employee_id = $this->input->get('employee_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        // $employee_id = 'JBG-2021-824';
		$sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $period = date('Y-m');
        // $period = '2023-10';
        $employee = $this->db->query($sql)->row();

        $employee_list = $this->Payroll_model->get_employee_payslip_perbulan_cetak($employee->user_id, $start_date, $end_date);
        $data = [];
        if (count($employee_list) > 0) {
            $no = 1;
            foreach ($employee_list->result() as $r) {

                // get addd by > template
                $user = $this->Core_model->read_user_info($r->employee_id);
                // user full name
                $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;

                // get workstation
                $workstation = $this->Core_model->read_workstation_info($r->workstation_id);
                if (!is_null($workstation)) {
                    $workstation_name = $workstation[0]->workstation_name;
                } else {
                    $workstation_name = '--';
                }

                $jumlah_gaji = $this->Payroll_model->cek_jumlah_gaji($r->employee_id, $start_date, $end_date);
                if (!is_null($jumlah_gaji)) {
                    $gaji_karyawan = $jumlah_gaji[0]->jumlah;
                } else {
                    $gaji_karyawan = '';
                }

                $data[] = [
                    'rekening' => $r->rekening_name . ' (' . $r->bank_name . ')',
                    'fullname' => strtoupper($full_name),
                    'gaji_pokok' => number_format($r->basic_salary, 0, ',', '.'),
                    'kehadiran' => number_format($r->jumlah_hadir, 1, ',', '.'),
                    'lembur' => number_format($r->overtime_amount, 0, ',', '.'),
                    'tambahan' => number_format($r->commissions_amount, 0, ',', '.'),
                    'potongan' => number_format($r->minus_amount, 0, ',', '.'),
                    'bpjs_kes' => number_format($r->bpjs_tk_amount, 0, ',', '.'),
                    'bpjs_tk' => number_format($r->bpjs_kes_amount, 0, ',', '.'),
                    'total_gaji' => number_format($gaji_karyawan, 0, ',', '.'),
                ];
            }
        }
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit();
    }
    function payslip_borongan() {
        // $employee_id = $this-s>input->get('user_id');
        $employee_id = $this->input->get('employee_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        // $employee_id = 'JBG-2021-824';
		$sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $period = date('Y-m');
        // $period = '2023-10';
        $employee = $this->db->query($sql)->row();

        $employee_list = $this->Payroll_model->get_employee_payslip_perbulan_cetak($employee->user_id, $start_date, $end_date);
        $data = [];
        if (count($employee_list) > 0) {
            $no = 1;
            foreach ($employee_list->result() as $r) {

                // get addd by > template
                $user = $this->Core_model->read_user_info($r->employee_id);
                // user full name
                $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;

                // get workstation
                $workstation = $this->Core_model->read_workstation_info($r->workstation_id);
                if (!is_null($workstation)) {
                    $workstation_name = $workstation[0]->workstation_name;
                } else {
                    $workstation_name = '--';
                }

                $jumlah_gaji = $this->Payroll_model->cek_jumlah_gaji($r->employee_id, $start_date, $end_date);
                if (!is_null($jumlah_gaji)) {
                    $gaji_karyawan = $jumlah_gaji[0]->jumlah;
                } else {
                    $gaji_karyawan = '';
                }

                $data[] = [
                    'rekening' => $r->rekening_name . ' (' . $r->bank_name . ')',
                    'fullname' => strtoupper($full_name),
                    'gaji_pokok' => number_format($r->basic_salary, 0, ',', '.'),
                    'kehadiran' => number_format($r->jumlah_hadir, 1, ',', '.'),
                    'lembur' => number_format($r->overtime_amount, 0, ',', '.'),
                    'tambahan' => number_format($r->commissions_amount, 0, ',', '.'),
                    'potongan' => number_format($r->minus_amount, 0, ',', '.'),
                    'bpjs_kes' => number_format($r->bpjs_tk_amount, 0, ',', '.'),
                    'bpjs_tk' => number_format($r->bpjs_kes_amount, 0, ',', '.'),
                    'total_gaji' => number_format($gaji_karyawan, 0, ',', '.'),
                ];
            }
        }
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit();
    }
}