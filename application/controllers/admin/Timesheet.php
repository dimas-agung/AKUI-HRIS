<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Location_model $Location_model
 * @property Timesheet_model $Timesheet_model
 * @property Employees_model $Employees_model
 * @property Designation_model $Designation_model
 */
class Timesheet extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Timesheet_model");
        $this->load->model("Employees_model");
        $this->load->model("Core_model");
        $this->load->library('email');
        $this->load->model("Department_model");
        $this->load->model("Designation_model");
        $this->load->model("Roles_model");

        $this->load->model("Location_model");
        $this->load->model("Company_model");
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

    // index > timesheet

    // =============================================================================
    // 0910 TARIK ABSENSI REGULER
    // =============================================================================

    // daily attendance > timesheet
    public function attendance_reguler()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Tarik Absensi Reguler | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-hand-o-up"></i>';
        $data['desc']        = '<span><b>INFORMASI : </b> Proses Tarik Absensi Setiap Hari ini dilakukan setelah Proses Pengajuan di Input semua </span>';
        $data['breadcrumbs'] = 'Tarik Absensi Reguler (Per Hari / Tanggal)';
        $data['path_url']    = 'attendance_reguler';

        $data['get_all_companies']    = $this->Company_model->get_company();
        $data['all_office_shifts'] = $this->Location_model->all_payroll_jenis();

        $role_resources_ids        = $this->Core_model->user_role_resource();

        if (in_array('0911', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/attendance_reguler_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // daily attendance list > timesheet
    public function attendance_reguler_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_reguler_list", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $attendance_date    = $this->input->get("attendance_date");
        $jenis_gaji         = $this->input->get("location_id");
        $company_id         = $this->input->get("company_id");

        $employee = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji, $company_id);
        // var_dump($employee);return;
        $system   = $this->Core_model->read_setting_info(1);

        $data = array();

        $no = 1;
        $dataInsert = [];
        
        foreach ($employee->result() as $r) {
            
            $sql1 = "DELETE FROM xin_attendance_time WHERE 1=1
                            AND employee_id ='" . $r->user_id . "' AND  attendance_date = '" . $attendance_date . "'  ";

            $query1   = $this->db->query($sql1);
           
            $comp_name = $r->company_name;
            $designation_name = $r->designation_name;
            // user full name
            $full_name = $r->first_name . ' ' . $r->last_name;

            $total_work = 0;
            $early_leaving = 0;
            $time_late = 0;
            $overtime = 0;
            $clock_in = '00:00:00';
            $clock_out = '00:00:00';
            $flag = 'H';
            // return;
            // =========================================================================================================
            // CEK HARI LIBUR
            // =========================================================================================================
            $check_hari_libur = $this->check_hari_libur($r->company_id,$attendance_date);
            if ($r->flag == 1) {

                $attendance_status                = 'Free';
                $attendance_simbol         = 'F';
                $attendance_keterangan = 'Bebas Tanpa Absensi Mesin Finger';
            }else if ($check_hari_libur['status'] == true) {
                $attendance_status = $check_hari_libur['attendance_status'];
                $attendance_simbol = $check_hari_libur['attendance_simbol'];
                $attendance_keterangan = $check_hari_libur['attendance_keterangan'];
                $flag = 'L';
                $attendance_jadwal = 'Libur';
            }else{
                // cek apakah karyawan sudah mulai bekerja
                if ($r->date_of_joining >= $attendance_date) {
                    $attendance_jadwal = 'Belum Masuk';
                    $attendance_status = 'Belum Masuk';
                    $attendance_simbol = 'BM';
                    $attendance_keterangan = 'Belum Mulai Kerja';
                } else {
                    $get_day = strtotime($attendance_date);
                    $day = date('l', $get_day);
                    // get office time
                    $office_time = $this->get_office_time_in_out($attendance_date,$r->office_shift_id,'REGULAR');
                    $in_time = $office_time['in_time'];
                    $out_time = $office_time['out_time'];
                    $attendance_jadwal = $in_time . ' s/d ' . $out_time;

                    //cek libur kantor
                    $check_hari_libur_kantor = $this->check_hari_libur_kantor($r->user_id,$attendance_date);
                    if ($check_hari_libur['status']== true) {
                        # code...
                        $attendance_status = $check_hari_libur_kantor['attendance_status'];
                        $attendance_simbol = $check_hari_libur_kantor['attendance_simbol'];
                        $attendance_keterangan = $check_hari_libur_kantor['attendance_keterangan'];
                    }else{
                        //cek cuti
                        $check_cuti = $this->check_cuti_karyawan($r->user_id,$attendance_date);
                        
                        if ($check_cuti['status'] == true) {
                            $attendance_status = $check_cuti['attendance_status'];
                            $attendance_simbol = $check_cuti['attendance_simbol'];
                            $attendance_keterangan = $check_cuti['attendance_keterangan'];
                        }else{
                             // cek dinas
                            $check_dinas = $this->check_dinas_karyawan($r->user_id,$attendance_date);
                            if ($check_dinas['status'] == true) {
                                $attendance_status = $check_dinas['attendance_status'];
                                $attendance_simbol = $check_dinas['attendance_simbol'];
                                $attendance_keterangan = $check_dinas['attendance_keterangan'];
                            }else{
                                // cek sakit / izin
                                $check_sakit_izin = $this->check_sakit_izin_karyawan($r->user_id,$attendance_date);
                                // get data check in 

                                $attendance_in = $this->Timesheet_model->attendance_first_in_new($r->employee_pin, $attendance_date);
                                // var_dump($r->employee_pin);
                                // var_dump($attendance_in);
                                // return;
                                // check clock out time
                                // get data from adms -> view absen keluar
                                $attendance_out = $this->Timesheet_model->attendance_first_out_new($r->employee_pin, $attendance_date);
                                //cek apakah dia sudah checlok pulang
                                if ($check_sakit_izin['status'] == true) {
                                    $attendance_status = $check_sakit_izin['attendance_status'];
                                    $attendance_simbol = $check_sakit_izin['attendance_simbol'];
                                    $attendance_keterangan = $check_sakit_izin['attendance_keterangan'];
                                    // cek apakah dia masuk/checklock
                                    if (!empty($attendance_in)) {
                                        $clock_in = new DateTime($attendance_in[0]->clock_in);
                                        
                                        if (!empty($attendance_out)) {
                                            $clock_out = new DateTime($attendance_out[0]->clock_out);
                                            $total_work_cin  =  new DateTime($clock_in);
                                            $total_work_cout =  new DateTime($clock_out);

                                            $interval_cin = $total_work_cout->diff($total_work_cin);
                                            $hours_in   = $interval_cin->format('%h');
                                            $minutes_in = $interval_cin->format('%i');
                                            $total_work = $hours_in * 60 + $minutes_in;

                                            $time_late = $this->check_terlambat($attendance_date,$in_time,$clock_in);
                                            $early_leaving = $this->check_pulang_cepat($attendance_date,$out_time,$clock_out);
                                        }else{
                                            // status absen
                                            $attendance_status     = $this->lang->line('xin_absent');
                                            $attendance_simbol         = $this->lang->line('xin_absent_simbol');
                                            $attendance_keterangan = $this->lang->line('xin_absent_ket');
                                        }
                                    }
                                }else{
                                    //  cek apakah sudah checklock masuk / pulang
                                    if (empty($attendance_in) || empty($attendance_out)) {
                                        # code...
                                        if (!empty($attendance_in)) {
                                            $clock_in = $attendance_in[0]->clock_in;
                                            $time_late = $this->check_terlambat($attendance_date,$in_time,$clock_in);
                                        }
                                        if (!empty($attendance_out)) {
                                            $clock_out = $attendance_out[0]->clock_out;
                                            $early_leaving = $this->check_pulang_cepat($attendance_date,$out_time,$clock_out);
                                            $overtime = $this->check_over_time_checklock($attendance_date,$out_time,$clock_out);
                                        }
                                        $attendance_status     = $this->lang->line('xin_absent');
                                        $attendance_simbol         = $this->lang->line('xin_absent_simbol');
                                        $attendance_keterangan = $this->lang->line('xin_absent_ket');
                                    }else{
                                        $clock_in = $attendance_in[0]->clock_in;
                                        $clock_out = $attendance_out[0]->clock_out;
                                        // var_dump($clock_in);return;
                                        $total_work_cin  =  new DateTime($clock_in);
                                        $total_work_cout =  new DateTime($clock_out);

                                        $interval_cin = $total_work_cout->diff($total_work_cin);
                                        $hours_in   = $interval_cin->format('%h');
                                        $minutes_in = $interval_cin->format('%i');
                                        $total_work = $hours_in * 60 + $minutes_in;

                                        $time_late = $this->check_terlambat($attendance_date,$in_time,$clock_in);
                                        $early_leaving = $this->check_pulang_cepat($attendance_date,$out_time,$clock_out);
                                        $overtime = $this->check_over_time_checklock($attendance_date,$out_time,$clock_out);
                                        $check_lembur = $this->check_lembur($r->user_id,$attendance_date);
                                        if ($check_lembur['status'] == true) {
                                            $attendance_status = $check_lembur['attendance_status'];
                                            $attendance_simbol = $check_lembur['attendance_simbol'];
                                            $attendance_keterangan = $check_lembur['attendance_keterangan'];
                                        }else{
                                            $attendance_status     = $attendance_in[0]->attendance_status;
                                            $attendance_simbol     = 'H';
                                            $attendance_keterangan = 'Masuk';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $dataInsert[] = [
                'employee_id' =>$r->user_id,
                'employee_pin' =>$r->employee_pin,
                'company_id' =>$r->company_id,
                'location_id' => $r->location_id,
                'date_of_joining' => $r->date_of_joining,
                'jenis_gaji' => $jenis_gaji,
                'jenis_kerja' => 'R',
                'attendance_jadwal' =>$attendance_jadwal,
                'flag' =>$flag,
                'attendance_date' =>$attendance_date,
                'clock_in' =>$clock_in,
                'clock_out' =>$clock_out,
                'time_late' =>$time_late,
                'early_leaving' =>$early_leaving,
                'overtime' =>$overtime,
                'total_work' =>$total_work,
                'attendance_status' =>$attendance_status,
                'attendance_status_simbol' =>$attendance_simbol,
                'attendance_keterangan' =>$attendance_keterangan,
                'rekap_date' => date('Y-m-d H:i:s')
            ];


            if ($clock_in == '00:00:00') {
                $jam_masuk = '-';
            } else {
                $jam_masuk = $clock_in;
            }

            if ($clock_out == '00:00:00') {
                $jam_pulang = '-';
            } else {
                $jam_pulang = $clock_out;
            }

            $d_date = $this->Core_model->set_date_format($attendance_date);

            $data[] = array(
                $no,
                strtoupper($full_name),
                date("d-m-Y", strtotime($r->date_of_joining)),
                substr(strtoupper($designation_name), 0, 30),
                $comp_name,
                $attendance_jadwal,
                $d_date,
                $attendance_status,
                $jam_masuk,
                $jam_pulang,
                $time_late,
                $early_leaving,
                $overtime,
                $total_work,
                $attendance_keterangan
            );
            $no++;
            // }
        }
        
        $this->db->insert_batch('xin_attendance_time', $dataInsert);

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // =============================================================================
    // 0920 TARIK ABSENSI SHIFT
    // =============================================================================

    // daily attendance > timesheet
    public function attendance_shift()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Tarik Absensi Shift | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-hand-o-up"></i>';
        $data['desc']        = '<span><b>INFORMASI : </b> Proses Tarik Absensi Setiap Hari ini dilakukan setelah Proses Pengajuan di Input semua </span>';
        $data['breadcrumbs'] = 'Tarik Absensi Shift (Per Hari / Tanggal)';
        $data['path_url']    = 'attendance_shift';

        $data['get_all_companies']    = $this->Company_model->get_company();
        $data['all_office_shifts'] = $this->Location_model->all_payroll_jenis();

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0921', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/attendance_shift_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    // daily attendance list > timesheet
    public function attendance_shift_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_reguler_list", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $attendance_date    = $this->input->get("attendance_date");
        $jenis_gaji         = $this->input->get("location_id");
        $company_id         = $this->input->get("company_id");

        $employee = $this->Employees_model->get_attendance_jenis_gaji_employees_shift_load($jenis_gaji, $company_id);
        // var_dump($employee);return;
        $system   = $this->Core_model->read_setting_info(1);

        $data = array();

        $no = 1;
        $dataInsert = [];
        foreach ($employee->result() as $r) {

            $sql1 = "DELETE FROM xin_attendance_time WHERE 1=1
                            AND employee_id ='" . $r->user_id . "' AND  attendance_date = '" . $attendance_date . "'  ";

            $query1   = $this->db->query($sql1);

            $comp_name = $r->company_name;
            $designation_name = $r->designation_name;
            // user full name
            $full_name = $r->first_name . ' ' . $r->last_name;

            $total_work = 0;
            $early_leaving = 0;
            $time_late = 0;
            $overtime = 0;
            $clock_in = '00:00:00';
            $clock_out = '00:00:00';
            $flag = 'H';

            // =========================================================================================================
            // CEK HARI LIBUR
            // =========================================================================================================
            $check_hari_libur = $this->check_hari_libur($r->company_id,$attendance_date);
            if ($r->flag == 1) {

                $attendance_status                = 'Free';
                $attendance_simbol         = 'F';
                $attendance_keterangan = 'Bebas Tanpa Absensi Mesin Finger';
            } elseif ($check_hari_libur['status'] == true) {
                $attendance_status = $check_hari_libur['attendance_status'];
                $attendance_simbol = $check_hari_libur['attendance_simbol'];
                $attendance_keterangan = $check_hari_libur['attendance_keterangan'];
                $flag = 'L';
                $attendance_jadwal = 'Libur';
            }else{
                // cek apakah karyawan sudah mulai bekerja
                if ($r->date_of_joining >= $attendance_date) {
                    $attendance_jadwal = 'Belum Masuk';
                    $attendance_status = 'Belum Masuk';
                    $attendance_simbol = 'BM';
                    $attendance_keterangan = 'Belum Mulai Kerja';
                } else {
                    $get_day = strtotime($attendance_date);
                    $day = date('l', $get_day);
                    // get office time
                    $office_time = $this->get_office_time_in_out($attendance_date,$r->office_shift_id,'SHIFT');
                    $in_time = $office_time['in_time'];
                    $out_time = $office_time['out_time'];
                    $attendance_jadwal = $in_time . ' s/d ' . $out_time;

                    //cek libur kantor
                    $check_hari_libur_kantor = $this->check_hari_libur_kantor($r->user_id,$attendance_date);
                    if ($check_hari_libur['status']== true) {
                        # code...
                        $attendance_status = $check_hari_libur_kantor['attendance_status'];
                        $attendance_simbol = $check_hari_libur_kantor['attendance_simbol'];
                        $attendance_keterangan = $check_hari_libur_kantor['attendance_keterangan'];
                    }else{
                        //cek cuti
                        $check_cuti = $this->check_cuti_karyawan($r->user_id,$attendance_date);
                        
                        if ($check_cuti['status'] == true) {
                            $attendance_status = $check_cuti['attendance_status'];
                            $attendance_simbol = $check_cuti['attendance_simbol'];
                            $attendance_keterangan = $check_cuti['attendance_keterangan'];
                        }else{
                            // cek dinas
                            $check_dinas = $this->check_dinas_karyawan($r->user_id,$attendance_date);
                            if ($check_dinas['status'] == true) {
                                $attendance_status = $check_dinas['attendance_status'];
                                $attendance_simbol = $check_dinas['attendance_simbol'];
                                $attendance_keterangan = $check_dinas['attendance_keterangan'];
                            }else{
                                // cek sakit / izin
                                $check_sakit_izin = $this->check_sakit_izin_karyawan($r->user_id,$attendance_date);
                                // get data check in 
                                // get data from adms -> view absen masuk 
                                $attendance_in = $this->Timesheet_model->attendance_first_in_new($r->employee_pin, $attendance_date);
                                // var_dump($attendance_in);return;
                                // check clock out time
                                // get data from adms -> view absen keluar
                                $attendance_out = $this->Timesheet_model->attendance_first_out_new($r->employee_pin, $attendance_date);
                                //cek apakah dia sudah checlok pulang
                                // var_dump($attendance_out);return;
                                if ($check_sakit_izin['status'] == true) {
                                    $attendance_status = $check_sakit_izin['attendance_status'];
                                    $attendance_simbol = $check_sakit_izin['attendance_simbol'];
                                    $attendance_keterangan = $check_sakit_izin['attendance_keterangan'];
                                    // cek apakah dia masuk/checklock
                                    if (!empty($attendance_in)) {
                                        $clock_in = new DateTime($attendance_in[0]->clock_in);

                                        if (!empty($attendance_out)) {
                                            $clock_out = new DateTime($attendance_out[0]->clock_out);
                                            $total_work_cin  =  new DateTime($clock_in);
                                            $total_work_cout =  new DateTime($clock_out);

                                            $interval_cin = $total_work_cout->diff($total_work_cin);
                                            $hours_in   = $interval_cin->format('%h');
                                            $minutes_in = $interval_cin->format('%i');
                                            $total_work = $hours_in * 60 + $minutes_in;

                                            $time_late = $this->check_terlambat($attendance_date,$in_time,$clock_in);
                                            $early_leaving = $this->check_pulang_cepat($attendance_date,$out_time,$clock_out);
                                        }else{
                                            // status absen
                                            $attendance_status     = $this->lang->line('xin_absent');
                                            $attendance_simbol         = $this->lang->line('xin_absent_simbol');
                                            $attendance_keterangan = $this->lang->line('xin_absent_ket');
                                        }
                                    }
                                }else{
                                    //  cek apakah sudah checklock masuk / pulang
                                    if (empty($attendance_in) || empty($attendance_out)) {
                                        # code...
                                        if (!empty($attendance_in)) {
                                            // $clock_in = new DateTime($attendance_in[0]->clock_in);
                                            $clock_in = $attendance_in[0]->clock_in;
                                            $time_late = $this->check_terlambat($attendance_date,$in_time,$clock_in);
                                        }
                                        if (!empty($attendance_out)) {
                                            // $clock_out = new DateTime($attendance_out[0]->clock_out);
                                            $clock_out = $attendance_out[0]->clock_out;
                                            $early_leaving = $this->check_pulang_cepat($attendance_date,$out_time,$clock_out);
                                            $overtime = $this->check_over_time_checklock($attendance_date,$out_time,$clock_out);
                                        }
                                        $attendance_status     = $this->lang->line('xin_absent');
                                        $attendance_simbol         = $this->lang->line('xin_absent_simbol');
                                        $attendance_keterangan = $this->lang->line('xin_absent_ket');
                                    }else{
                                        $clock_in = $attendance_in[0]->clock_in;
                                        $clock_out = $attendance_out[0]->clock_out;
                                        // var_dump($clock_out);return;
                                        $total_work_cin  =  new DateTime($clock_in);
                                        $total_work_cout =  new DateTime($clock_out);

                                        $interval_cin = $total_work_cout->diff($total_work_cin);
                                        $hours_in   = $interval_cin->format('%h');
                                        $minutes_in = $interval_cin->format('%i');
                                        $total_work = $hours_in * 60 + $minutes_in;

                                        $time_late = $this->check_terlambat($attendance_date,$in_time,$clock_in);
                                        $early_leaving = $this->check_pulang_cepat($attendance_date,$out_time,$clock_out);
                                        $overtime = $this->check_over_time_checklock($attendance_date,$out_time,$clock_out);
                                        $check_lembur = $this->check_lembur($r->user_id,$attendance_date);
                                        if ($check_lembur['status'] == true) {
                                            $attendance_status = $check_lembur['attendance_status'];
                                            $attendance_simbol = $check_lembur['attendance_simbol'];
                                            $attendance_keterangan = $check_lembur['attendance_keterangan'];
                                        }else{
                                            $attendance_status     = $attendance_in[0]->attendance_status;
                                            $attendance_simbol     = 'H';
                                            $attendance_keterangan = 'Masuk';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $dataInsert[] = [
                'employee_id' =>$r->user_id,
                'employee_pin' =>$r->employee_pin,
                'company_id' =>$r->company_id,
                'location_id' => $r->location_id,
                'date_of_joining' => $r->date_of_joining,
                'jenis_gaji' => $jenis_gaji,
                'jenis_kerja' => 'S',
                'attendance_jadwal' =>$attendance_jadwal,
                'flag' =>$flag,
                'attendance_date' =>$attendance_date,
                'clock_in' =>$clock_in,
                'clock_out' =>$clock_out,
                'time_late' =>$time_late,
                'early_leaving' =>$early_leaving,
                'overtime' =>$overtime,
                'total_work' =>$total_work,
                'attendance_status' =>$attendance_status,
                'attendance_status_simbol' =>$attendance_simbol,
                'attendance_keterangan' =>$attendance_keterangan,
                'rekap_date' => date('Y-m-d H:i:s')
            ];


            if ($clock_in == '00:00:00') {
                $jam_masuk = '-';
            } else {
                $jam_masuk = $clock_in;
            }

            if ($clock_out == '00:00:00') {
                $jam_pulang = '-';
            } else {
                $jam_pulang = $clock_out;
            }

            $d_date = $this->Core_model->set_date_format($attendance_date);

            $data[] = array(
                $no,
                strtoupper($full_name),
                date("d-m-Y", strtotime($r->date_of_joining)),
                substr(strtoupper($designation_name), 0, 30),
                $comp_name,
                $attendance_jadwal,
                $d_date,
                $attendance_status,
                $jam_masuk,
                $jam_pulang,
                $time_late,
                $early_leaving,
                $overtime,
                $total_work,
                $attendance_keterangan
            );
            $no++;
            // }
        }
        
        $this->db->insert_batch('xin_attendance_time', $dataInsert);

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    // =============================================================================
    // 0930 REKAP KEHADIRAN
    // =============================================================================

    // ****************************************************************************************************
    // Bulanan
    // ****************************************************************************************************

    public function attendance_rekap_bulanan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Rekap Absensi Bulanan | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-magic"></i>';
        $data['desc']        = 'PROSES : Proses Rekap Absensi Bulanan ini dilakukan setelah dilakukannya Proses Tarik Absensi Harian dan sebelum Proses Penggajian';
        $data['breadcrumbs'] = 'Rekap Absensi Bulanan ';
        $data['path_url']    = 'rekap_kehadiran';

        // $data['all_office_shifts']    = $this->Location_model->all_payroll_jenis();
        $data['get_all_companies']  = $this->Company_model->get_company();
        $data['all_office_pola']    = $this->Location_model->all_payroll_pola();
        $data['all_bulan_gaji']     = $this->Core_model->all_bulan_status_payroll();

        $role_resources_ids = $this->Core_model->user_role_resource();

        if (in_array('0930', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/attendance_rekap_bulanan_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function attendance_rekap_bulanan_proses()
    {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => $this->security->get_csrf_hash());

        if ($this->input->post('add_type') == 'rekap') {

            // $jenis_gaji = $this->input->post("jenis_gaji");
            $company_id = $this->input->post("company_id");
            $pola_kerja = $this->input->post("pola_kerja");
            $month_year = $this->input->post("month_year");

            $session_id     = $this->session->userdata('user_id');
            $user_create    = $session_id['user_id'];

            $tampilkan_karyawan = array();
            if ($company_id != 0) {
                $cek_karyawan = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_recap_bulanan($pola_kerja, $company_id);
                $tampilkan_karyawan = $cek_karyawan->result();
            } else {
                $Return['error'] = $this->lang->line('xin_record_not_found');
            }

            // all employee id
            $emp_ids = array();
            foreach ($tampilkan_karyawan as $emp) {
                $emp_ids[] = $emp->user_id;
            }

            // DELETE ALL RECAP DATA
            $this->db->where(array(
                'month_year' => $month_year,
                'office_id' => $pola_kerja,
                'company_id' => $company_id,
                'wages_type' => 1, // bulanan
            ))->delete('xin_attendance_time_rekap');

            // ==================================================================================================================
            // Tanggal Penggajian
            // ==================================================================================================================
            $tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
            if (!is_null($tanggal)) {
                $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
                $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

                $start_date    = new DateTime($tanggal[0]->start_date);
                $end_date      = new DateTime($tanggal[0]->end_date);
                $interval_date = $end_date->diff($start_date);

                $bulan   = $tanggal[0]->bulan;
            } else {
                $start_att = '';
                $end_att = '';

                $start_date    = '';
                $end_date      = '';
                $interval_date = '';

                $bulan   = '';
            }

            $tanggal1       = date("Y-m-d", strtotime($start_att));
            $tanggal2       = date("Y-m-d", strtotime($end_att));
            $xin_tanggal    = $this->Timesheet_model->get_xin_tanggal($month_year);

            // CREATE CALENDAR DATA IF NOT EXISTS
            if (count($xin_tanggal) === 0) {
                $create_calendar = array();
                $interval = DateInterval::createFromDateString('1 day');
                $period = new \DatePeriod($start_date, $interval, $end_date);

                foreach ($period as $dt) {
                    $create_calendar[] = [
                        'bulan' => $month_year,
                        'tanggal' => $dt->format("Y-m-d"),
                    ];
                }

                $create_calendar[] = [
                    'bulan' => $month_year,
                    'tanggal' => $end_date->format("Y-m-d"),
                ];

                $this->db->where('bulan', $month_year)->delete('xin_payroll_calendar');
                $this->db->insert_batch('xin_payroll_calendar', $create_calendar);

                $xin_tanggal = $this->Timesheet_model->get_xin_tanggal($month_year);
            }

            // all period date
            $attendance_dates = array();
            foreach ($xin_tanggal as $date) {
                $attendance_dates[] = $date->tanggal;
            }

            // get all employee attendance symbol
            $get_attendance_data = $this->Timesheet_model->cek_multi_status_kehadiran($emp_ids, $attendance_dates);
            $attendance_data = array();
            foreach ($get_attendance_data as $ad) {
                if (!isset($attendance_data[$ad->employee_id])) {
                    $attendance_data[$ad->employee_id] = array();
                }

                $attendance_data[$ad->employee_id][$ad->attendance_date] = $ad->attendance_status_simbol;
            }

            // count attendance all employee
            $symbol = array('L', 'LK', 'H', 'S', 'I', 'C', 'A', 'D', 'O');
            $get_all_total_attendance = $this->Timesheet_model->hitung_multi_jumlah_status_kehadiran($emp_ids, $tanggal1, $tanggal2, $symbol);
            $all_total_attendance = array();
            foreach ($get_all_total_attendance as $gata) {
                if (!isset($all_total_attendance[$gata->employee_id])) {
                    $all_total_attendance[$gata->employee_id] = array();
                }

                $all_total_attendance[$gata->employee_id][$gata->attendance_status_simbol] = $gata->jumlah;
            }

            // count time late all employee
            $get_all_late = $this->Timesheet_model->hitung_jumlah_terlambat_kehadiran($emp_ids, $tanggal1, $tanggal2);
            $all_late = array();
            foreach ($get_all_late as $gal) {
                $all_late[$gal->employee_id] = $gal->jumlah;
            }

            $data = array();
            foreach ($tampilkan_karyawan as $r) {

                // ==================================================================================================================
                // Rekap Kehadiran
                // ==================================================================================================================
                foreach ($xin_tanggal as $t) {
                    $attendance_date = $t->tanggal;

                    if (isset($attendance_data[$r->user_id]) && isset($attendance_data[$r->user_id][$attendance_date])) {
                        $status = $attendance_data[$r->user_id][$attendance_date];
                        if ($status == 'H') {
                            if ($r->date_of_joining > $attendance_date) {
                                $tgl = 'BM';
                            } else {
                                $tgl = '.';
                            }
                        } else {
                            if ($r->flag == 0) {
                                $tgl = $status;
                            } else if ($r->flag == 1) {
                                if ($r->date_of_joining > $attendance_date) {
                                    $tgl = 'BM';
                                } else if ($status == 'L') {
                                    $tgl = 'L';
                                } else if ($status == 'LK') {
                                    $tgl = 'LK';
                                } else {
                                    $tgl = '.';
                                }
                            }
                        }
                    } else {
                        $tgl = '?';
                    }

                    ${"T_{$t->tgl}"} = $tgl;
                }

                // ==================================================================================================================
                // Rekap Pengajuan
                // ==================================================================================================================
                $get_total_attendance = isset($all_total_attendance[$r->user_id]) ? $all_total_attendance[$r->user_id] : array();
                $total_attendance = array();
                foreach ($get_total_attendance as $symbol => $jumlah) {
                    $total_attendance[$symbol] = $jumlah;
                }

                // -------------------------------------------------------------------------------------------------------------
                // LIBUR
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_libur = isset($total_attendance['L']) ? $total_attendance['L'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // LIBUR KANTOR
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_libur_kantor = isset($total_attendance['LK']) ? $total_attendance['LK'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // HADIR
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_hadir = isset($total_attendance['H']) ? $total_attendance['H'] : 0;
                if ($r->flag == 0) {
                    $jumlah_hadir = $jumlah_hadir;
                } else {
                    if ($r->date_of_joining > $tanggal1) {
                        $jumlah_hadir = $interval_date->d - $jumlah_libur - $jumlah_hadir - $jumlah_libur_kantor;
                    } else {
                        $jumlah_hadir = $interval_date->d - $jumlah_libur - $jumlah_libur_kantor;
                    }
                }

                // -------------------------------------------------------------------------------------------------------------
                // SAKIT
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_sakit = isset($total_attendance['S']) ? $total_attendance['S'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // IZIN
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_izin = isset($total_attendance['I']) ? $total_attendance['I'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // CUTI
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_cuti = isset($total_attendance['C']) ? $total_attendance['C'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // ALPA
                // -------------------------------------------------------------------------------------------------------------
                if ($r->flag == 0) {
                    $jumlah_alpa = isset($total_attendance['A']) ? $total_attendance['A'] : 0;
                } else {
                    $jumlah_alpa = 0;
                }

                // -------------------------------------------------------------------------------------------------------------
                // DINAS
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_dinas = isset($total_attendance['D']) ? $total_attendance['D'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // LEMBUR
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_lembur = isset($total_attendance['O']) ? $total_attendance['O'] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // TERLAMBAT
                // -------------------------------------------------------------------------------------------------------------\
                $jumlah_terlambat = isset($all_late[$r->user_id]) ? $all_late[$r->user_id] : 0;

                // -------------------------------------------------------------------------------------------------------------
                // MENIT
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_terlambat_menit = $jumlah_terlambat;

                // -------------------------------------------------------------------------------------------------------------
                // JAM
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_terlambat_jam = round($jumlah_terlambat / 60, 2);

                // -------------------------------------------------------------------------------------------------------------
                // TOTAL
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_total = 0;
                $jumlah_total = $jumlah_hadir + $jumlah_libur + $jumlah_libur_kantor + $jumlah_sakit + $jumlah_izin + $jumlah_cuti + $jumlah_alpa + $jumlah_dinas + $jumlah_lembur;

                // ==================================================================================================================
                // Simpan Rekap
                // ==================================================================================================================

                // $data_payroll = array(
                // 	'is_payroll' => 1
                // );

                // $this->Timesheet_model->update_bulan_gaji($data_payroll,$month_year);

                $attendance_status = array();
                for ($i = 1; $i <= 31; $i++) {
                    $name = "T_{$i}";
                    $attendance_status["tanggal_{$i}"] = isset($$name) ? $$name : NULL;
                }

                $_data = array(
                    'employee_id'     => $r->user_id,
                    'wages_type'      => $r->wages_type,
                    'company_id'      => $r->company_id,
                    'office_id'       => $r->office_id,
                    'is_active'       => $r->is_active,
                    'date_of_joining' => $r->date_of_joining,
                    'department_id'   => $r->department_id,
                    'designation_id'  => $r->designation_id,
                    'month_year'      => $month_year,
                    'bulan'           => $bulan,
                    'libur'           => $jumlah_libur,
                    'libur_kantor'    => $jumlah_libur_kantor,
                    'aktif'           => $jumlah_hadir + $jumlah_lembur,
                    'sakit'           => $jumlah_sakit,
                    'izin'            => $jumlah_izin,
                    'cuti'            => $jumlah_cuti,
                    'alpa'            => $jumlah_alpa,
                    'dinas'           => $jumlah_dinas,
                    'terlambat_menit' => $jumlah_terlambat_menit,
                    'terlambat_jam'   => $jumlah_terlambat_jam,
                    'total'           => $jumlah_total,
                    'create_date'     => date('Y-m-d h:i:s'),
                    'create_by'       => $user_create,
                );

                $data[] = array_merge($_data, $attendance_status);

                // echo "<pre>";
                // print_r($result);
                // echo "</pre>";
                // die();
            }

            $result = $this->Timesheet_model->add_employee_attendance_rekap_reguler($data);
            if ($result) {
                $Return['result'] = 'Rekap Kehadiran Bulanan Berhasil Diproses';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            return json_response($Return);
        }
    }

    public function attendance_rekap_bulanan_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_rekap_bulanan_list", $data);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $company_id         = $this->input->get("company_id");
        // $jenis_gaji         = $this->input->get("jenis_gaji");
        $pola_kerja           = $this->input->get("pola_kerja");
        $month_year           = $this->input->get("month_year");

        // ===============================================================================================================
        // Tampilkan
        // ===============================================================================================================
        $employee           = $this->Employees_model->get_rekap_kehadiran_bulanan($company_id, $month_year, $pola_kerja);
        $xin_tanggal        = $this->Timesheet_model->read_tanggal_information($month_year);

        $start_date = new DateTime($xin_tanggal[0]->start_date);
        $start = (int) $start_date->format('j');

        $data = array();
        $no = 1;

        foreach ($employee->result() as $r) {
            // Jumlah hari 31 dlm sebulan
            if ($r->bulan == '01' || $r->bulan == '02' || $r->bulan == '04' || $r->bulan == '06' || $r->bulan == '08' || $r->bulan == '09' || $r->bulan == '11') { // 31

                $info_T29 = $r->T29;
                $info_T30 = $r->T30;
                $info_T31 = $r->T31;
                // Jumlah hari 28 dlm sebulan
            } else 	if ($r->bulan == '03') { // 28

                $info_T29 = '';
                $info_T30 = '';
                $info_T31 = '';
                // Jumlah hari 30 dlm sebulan
            } else 	if ($r->bulan == '05' || $r->bulan == '07' || $r->bulan == '10' || $r->bulan == '12') {

                $info_T29 = $r->T29;
                $info_T30 = $r->T30;
                $info_T31 = '';
                // Lainnya
            } else {

                $info_T29 = '';
                $info_T30 = '';
                $info_T31 = '';
            }

            $attendances = array();
            foreach (range(1, 31) as $i) {
                $d = str_pad($start, 2, '0', STR_PAD_LEFT);
                $attendances[] = in_array($d, array(29, 30, 31)) ? ${"info_T{$d}"} : $r->{"T{$d}"};
                $start = $start == 31 ? $start = 1 : $start + 1;
            }

            $data[] = array_merge(
                array(
                    $no,
                    strtoupper($r->full_name) . '' . $r->bulan,
                ),
                $attendances,
                array(
                    $r->libur,
                    $r->libur_kantor,
                    $r->aktif,
                    $r->sakit,
                    $r->izin,
                    $r->cuti,
                    $r->alpa,
                    $r->dinas,
                    $r->terlambat_menit,
                    $r->terlambat_jam,
                    $r->total
                )
            );

            $no++;
        }

        $output = array(
            "draw"            => $draw,
            "recordsTotal"    => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    // ****************************************************************************************************
    // Harian
    // ****************************************************************************************************

    public function attendance_rekap_harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Rekap Absensi Harian | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-magic"></i>';
        $data['desc']        = 'PROSES : Proses Rekap Absensi Harian ini dilakukan setelah dilakukannya Proses Tarik Absensi Harian dan sebelum Proses Penggajian';
        $data['breadcrumbs'] = 'Rekap Absensi Harian ';
        $data['path_url']    = 'rekap_kehadiran_harian';

        $data['get_all_companies']    = $this->Company_model->get_company();
        $data['all_office_shifts']    = $this->Location_model->all_payroll_jenis();
        $data['all_office_pola']    = $this->Location_model->all_payroll_pola();
        $data['all_bulan_gaji']       = $this->Core_model->all_bulan_gaji_close();

        $role_resources_ids        = $this->Core_model->user_role_resource();

        if (in_array('0950', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/attendance_rekap_harian_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function attendance_rekap_harian_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_rekap_harian_list", $data);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $company_id         = $this->input->get("company_id");
        $jenis_gaji         = 2;
        $pola_kerja         = $this->input->get("pola_kerja");
        $period_id          = $this->input->get("period_id");

        // ===============================================================================================================
        // Tampilkan
        // ===============================================================================================================

        $tanggal = $this->Timesheet_model->read_periode_bulan($period_id);
        if (!is_null($tanggal)) {
            $start_date     = $tanggal[0]->start_date;
            $end_date       = $tanggal[0]->end_date;
        } else {
            $start_date    = '';
            $end_date      = '';
        }

        $employees = $this->Timesheet_model->get_xin_employees_harian_rekap($company_id, $start_date, $end_date, TRUE, $pola_kerja);
        $data = array();
        $no = 1;

        foreach ($employees as $r) {
            $data[] = array(
                $no,
                strtoupper(implode(' ', array($r->first_name, $r->last_name))),
                $r->libur,
                $r->libur_kantor,
                $r->aktif,
                $r->sakit,
                $r->izin,
                $r->cuti,
                $r->alpa,
                $r->dinas,
                $r->terlambat_menit + 0,
                $r->terlambat_jam,
                $r->total
            );

            $no++;
        }

        $output = array(
            "draw"            => $draw,
            "recordsTotal"    => count($employees),
            "recordsFiltered" => count($employees),
            "data" => $data
        );

        return json_response($output);
    }

    public function attendance_rekap_harian_proses()
    {
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('add_type') == 'rekap') {

            $company_id         = $this->input->post("company_id");
            $pola_kerja         = $this->input->post("pola_kerja");

            $month_year         = $this->input->post("month_year");
            $periode_id           = $this->input->post("periode_id");

            $tanggal = $this->Timesheet_model->read_periode_bulan($periode_id);
            if (!is_null($tanggal)) {

                $tanggal1     = $tanggal[0]->start_date;
                $tanggal2       = $tanggal[0]->end_date;

                $start_date    = new DateTime($tanggal[0]->start_date);
                $end_date      = new DateTime($tanggal[0]->end_date);
                $interval_date = $end_date->diff($start_date);
            } else {
                $tanggal1     = '';
                $tanggal2       = '';

                $start_date    = '';
                $end_date      = '';
                $interval_date = '';
            }

            // echo "<pre>";

            // print_r( $company_id );
            // print_r( $pola_kerja );
            // print_r( $month_year );
            // print_r( $periode_id );

            $sql1 = "DELETE FROM xin_attendance_time_rekap_harian
                             WHERE 1=1
                             AND  company_id = '" . $company_id . "'
                             AND  office_id  = '" . $pola_kerja . "'
                             AND  start_date = '" . $tanggal1 . "'
                             AND  end_date   = '" . $tanggal2 . "'  ";

            // print_r($sql1);
            // exit();

            $query1   = $this->db->query($sql1);

            if ($company_id != 0) {
                $cek_karyawan = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_recap_harian($pola_kerja, $company_id);
                $tampilkan_karyawan = $cek_karyawan->result();
            } else {
                $Return['error'] = $this->lang->line('xin_record_not_found');
            }

            // 	print_r( $this->db->last_query() );


            // echo "</pre>";
            // die();

            foreach ($tampilkan_karyawan as $r) {



                // ==================================================================================================================
                // Rekap Pengajuan
                // ==================================================================================================================

                // -------------------------------------------------------------------------------------------------------------
                // LIBUR
                // -------------------------------------------------------------------------------------------------------------
                $cek_libur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'L');
                $jumlah_libur = $cek_libur ? $cek_libur[0]->jumlah : 0;


                // -------------------------------------------------------------------------------------------------------------
                // LIBUR KANTOR
                // -------------------------------------------------------------------------------------------------------------

                $cek_libur_kantor = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'LK');
                $jumlah_libur_kantor = $cek_libur_kantor ? $cek_libur_kantor[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // HADIR
                // -------------------------------------------------------------------------------------------------------------
                $cek_hadir = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'H');
                if ($r->flag == 0) {
                    $jumlah_hadir = $cek_hadir ? $cek_hadir[0]->jumlah : 0;
                } else {

                    if ($r->date_of_joining > $tanggal1) {
                        $jumlah_hadir = $interval_date->d - $jumlah_libur - ($cek_hadir ? $cek_hadir[0]->jumlah : 0) - $jumlah_libur_kantor;
                    } else {
                        $jumlah_hadir = $interval_date->d - $jumlah_libur - $jumlah_libur_kantor;
                    }
                }

                // -------------------------------------------------------------------------------------------------------------
                // SAKIT
                // -------------------------------------------------------------------------------------------------------------
                $cek_sakit = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'S');
                $jumlah_sakit = $cek_sakit ? $cek_sakit[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // IZIN
                // -------------------------------------------------------------------------------------------------------------
                $cek_izin = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'I');
                $jumlah_izin = $cek_izin ? $cek_izin[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // CUTI
                // -------------------------------------------------------------------------------------------------------------
                $cek_cuti = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'C');
                $jumlah_cuti = $cek_cuti ? $cek_cuti[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // ALPA
                // -------------------------------------------------------------------------------------------------------------

                $cek_alpa = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'A');

                if ($r->flag == 0) {
                    $jumlah_alpa = $cek_alpa ? $cek_alpa[0]->jumlah : 0;
                } else {
                    $jumlah_alpa = 0;
                }

                // -------------------------------------------------------------------------------------------------------------
                // DINAS
                // -------------------------------------------------------------------------------------------------------------
                $cek_dinas = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'D');
                $jumlah_dinas = $cek_dinas ? $cek_dinas[0]->jumlah: 0;

                // -------------------------------------------------------------------------------------------------------------
                // LEMBUR
                // -------------------------------------------------------------------------------------------------------------
                $cek_lembur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'O');
                $jumlah_lembur = $cek_lembur ? $cek_lembur[0]->jumlah: 0;

                // -------------------------------------------------------------------------------------------------------------
                // TERLAMBAT
                // -------------------------------------------------------------------------------------------------------------
                $cek_terlambat = $this->Timesheet_model->hitung_jumlah_terlambat_kehadiran($r->user_id, $tanggal1, $tanggal2);
                $jumlah_terlambat = $cek_terlambat ? $cek_terlambat[0]->jumlah: 0;

                // -------------------------------------------------------------------------------------------------------------
                // MENIT
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_terlambat_menit = $jumlah_terlambat;
                // -------------------------------------------------------------------------------------------------------------
                // JAM
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_terlambat_jam = round($jumlah_terlambat / 60, 2);

                // -------------------------------------------------------------------------------------------------------------
                // TOTAL
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_total = 0;

                $jumlah_total = $jumlah_hadir + $jumlah_libur + $jumlah_libur_kantor + $jumlah_sakit + $jumlah_izin + $jumlah_cuti + $jumlah_alpa + $jumlah_dinas + $jumlah_lembur;

                // ==================================================================================================================
                // Simpan Rekap
                // ==================================================================================================================

                // $data_payroll = array(
                // 	'is_payroll' => 1
                // );

                // $this->Timesheet_model->update_bulan_gaji($data_payroll,$month_year);

                $session_id = $this->session->userdata('user_id');
                $user_create = $session_id['user_id'];


                $data = array(

                    'employee_id'     => $r->user_id,
                    'wages_type'      => $r->wages_type,
                    'company_id'      => $r->company_id,
                    'office_id'          => $r->office_id,
                    'is_active'       => $r->is_active,
                    'date_of_joining' => $r->date_of_joining,
                    'department_id'   => $r->department_id,
                    'designation_id'  => $r->designation_id,
                    'flag'            => $r->flag,
                    'start_date'      => $tanggal1,
                    'end_date'        => $tanggal2,

                    'durasi'          => $interval_date->d + 1,

                    'libur'           => $jumlah_libur,
                    'libur_kantor'    => $jumlah_libur_kantor,
                    'aktif'           => $jumlah_hadir,
                    'sakit'           => $jumlah_sakit,
                    'izin'            => $jumlah_izin,
                    'cuti'            => $jumlah_cuti,
                    'alpa'            => $jumlah_alpa,
                    'dinas'           => $jumlah_dinas,
                    'terlambat_menit' => $jumlah_terlambat_menit,
                    'terlambat_jam'   => $jumlah_terlambat_jam,
                    'total'           => $jumlah_total,

                    'create_date'     => date('Y-m-d h:i:s'),
                    'create_by'       => $user_create,

                );

                $result = $this->Timesheet_model->add_employee_attendance_rekap_harian_reguler($data);

                // echo "<pre>";
                // print_r( $result );
                // echo "</pre>";
                // die();

                if ($result) {

                    $Return['result'] = 'Rekap Kehadiran Harian Berhasil Diproses';
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
            }

            $this->output($Return);
            exit;
        }
    }

    // ****************************************************************************************************
    // Borongan
    // ****************************************************************************************************

    public function attendance_rekap_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Rekap Absensi Borongan | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-magic"></i>';
        $data['desc']        = 'PROSES : Proses Rekap Absensi borongan ini dilakukan setelah dilakukannya Proses Tarik Absensi Borongan dan sebelum Proses Penggajian';
        $data['breadcrumbs'] = 'Rekap Absensi Borongan ';
        $data['path_url']    = 'rekap_kehadiran_borongan';

        $data['get_all_companies']    = $this->Company_model->get_company();
        $data['all_office_shifts']    = $this->Location_model->all_payroll_jenis();
        $data['all_office_pola']    = $this->Location_model->all_payroll_pola();
        $data['all_bulan_gaji']       = $this->Core_model->all_bulan_gaji_close();

        $role_resources_ids        = $this->Core_model->user_role_resource();

        if (in_array('0950', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/attendance_rekap_borongan_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function attendance_rekap_borongan_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_rekap_borongan_list", $data);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $company_id         = $this->input->get("company_id");
        $jenis_gaji         = 2;
        $pola_kerja         = $this->input->get("pola_kerja");
        $period_id          = $this->input->get("period_id");

        // ===============================================================================================================
        // Tampilkan
        // ===============================================================================================================

        $tanggal = $this->Timesheet_model->read_periode_bulan($period_id);
        if (!is_null($tanggal)) {
            $start_date     = $tanggal[0]->start_date;
            $end_date       = $tanggal[0]->end_date;
        } else {
            $start_date    = '';
            $end_date      = '';
        }

        $employees = $this->Timesheet_model->get_employees_borongan_rekap($company_id, $start_date, $end_date, TRUE, $pola_kerja);
        $data = array();
        $no = 1;

        foreach ($employees as $r) {
            $data[] = array(
                $no,
                strtoupper(implode(' ', array($r->first_name, $r->last_name))),
                $r->libur,
                $r->libur_kantor,
                $r->aktif,
                $r->sakit,
                $r->izin,
                $r->cuti,
                $r->alpa,
                $r->dinas,
                $r->terlambat_menit + 0,
                $r->terlambat_jam,
                $r->total
            );

            $no++;
        }

        $output = array(
            "draw"            => $draw,
            "recordsTotal"    => count($employees),
            "recordsFiltered" => count($employees),
            "data" => $data
        );

        return json_response($output);
    }

    public function attendance_rekap_borongan_proses()
    {
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('add_type') == 'rekap') {

            $company_id     = $this->input->post("company_id");
            $pola_kerja     = $this->input->post("pola_kerja");
            $month_year     = $this->input->post("month_year");
            $periode_id     = $this->input->post("periode_id");

            $tanggal = $this->Timesheet_model->read_periode_bulan($periode_id);
            if (!is_null($tanggal)) {
                $tanggal1     = $tanggal[0]->start_date;
                $tanggal2       = $tanggal[0]->end_date;

                $start_date    = new DateTime($tanggal[0]->start_date);
                $end_date      = new DateTime($tanggal[0]->end_date);
                $interval_date = $end_date->diff($start_date);
            } else {
                $tanggal1     = '';
                $tanggal2       = '';

                $start_date    = '';
                $end_date      = '';
                $interval_date = '';
            }
            $sql1 = "DELETE FROM xin_attendance_time_rekap_borongan
                             WHERE 1=1
                             AND  company_id = '" . $company_id . "'
                             AND  office_id  = '" . $pola_kerja . "'
                             AND  start_date = '" . $tanggal1 . "'
                             AND  end_date   = '" . $tanggal2 . "'  ";

            // print_r($sql1);
            // exit();

            $query1   = $this->db->query($sql1);

            if ($company_id != 0) {
                $cek_karyawan = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_recap_borongan($pola_kerja, $company_id);
                $tampilkan_karyawan = $cek_karyawan->result();
            } else {
                $Return['error'] = $this->lang->line('xin_record_not_found');
            }

            // 	print_r( $this->db->last_query() );

            // echo "</pre>";
            // die();

            foreach ($tampilkan_karyawan as $r) {
                // ==================================================================================================================
                // Rekap Pengajuan
                // ==================================================================================================================

                // -------------------------------------------------------------------------------------------------------------
                // LIBUR
                // -------------------------------------------------------------------------------------------------------------
                $cek_libur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'L');
                $jumlah_libur = $cek_libur ? $cek_libur[0]->jumlah : 0;


                // -------------------------------------------------------------------------------------------------------------
                // LIBUR KANTOR
                // -------------------------------------------------------------------------------------------------------------

                $cek_libur_kantor = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'LK');
                $jumlah_libur_kantor = $cek_libur_kantor ? $cek_libur_kantor[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // HADIR
                // -------------------------------------------------------------------------------------------------------------
                $cek_hadir = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'H');
                if ($r->flag == 0) {
                    $jumlah_hadir = $cek_hadir ? $cek_hadir[0]->jumlah : 0;
                } else {

                    if ($r->date_of_joining > $tanggal1) {
                        $jumlah_hadir = $interval_date->d - $jumlah_libur - ($cek_hadir ? $cek_hadir[0]->jumlah : 0) - $jumlah_libur_kantor;
                    } else {
                        $jumlah_hadir = $interval_date->d - $jumlah_libur - $jumlah_libur_kantor;
                    }
                }

                // -------------------------------------------------------------------------------------------------------------
                // SAKIT
                // -------------------------------------------------------------------------------------------------------------
                $cek_sakit = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'S');
                $jumlah_sakit = $cek_sakit ? $cek_sakit[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // IZIN
                // -------------------------------------------------------------------------------------------------------------
                $cek_izin = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'I');
                $jumlah_izin = $cek_izin ? $cek_izin[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // CUTI
                // -------------------------------------------------------------------------------------------------------------
                $cek_cuti = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'C');
                $jumlah_cuti = $cek_cuti ? $cek_cuti[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // ALPA
                // -------------------------------------------------------------------------------------------------------------

                $cek_alpa = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'A');

                if ($r->flag == 0) {
                    $jumlah_alpa = $cek_alpa ? $cek_alpa[0]->jumlah : 0;
                } else {
                    $jumlah_alpa = 0;
                }

                // -------------------------------------------------------------------------------------------------------------
                // DINAS
                // -------------------------------------------------------------------------------------------------------------
                $cek_dinas = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'D');
                $jumlah_dinas = $cek_dinas ? $cek_dinas[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // LEMBUR
                // -------------------------------------------------------------------------------------------------------------
                $cek_lembur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id, $tanggal1, $tanggal2, 'O');
                $jumlah_lembur = $cek_lembur ? $cek_lembur[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // TERLAMBAT
                // -------------------------------------------------------------------------------------------------------------
                $cek_terlambat = $this->Timesheet_model->hitung_jumlah_terlambat_kehadiran($r->user_id, $tanggal1, $tanggal2);
                $jumlah_terlambat = $cek_terlambat ? $cek_terlambat[0]->jumlah : 0;

                // -------------------------------------------------------------------------------------------------------------
                // MENIT
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_terlambat_menit = $jumlah_terlambat;
                // -------------------------------------------------------------------------------------------------------------
                // JAM
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_terlambat_jam = round($jumlah_terlambat / 60, 2);

                // -------------------------------------------------------------------------------------------------------------
                // TOTAL
                // -------------------------------------------------------------------------------------------------------------
                $jumlah_total = 0;

                $jumlah_total = $jumlah_hadir + $jumlah_libur + $jumlah_libur_kantor + $jumlah_sakit + $jumlah_izin + $jumlah_cuti + $jumlah_alpa + $jumlah_dinas + $jumlah_lembur;

                // ==================================================================================================================
                // Simpan Rekap
                // ==================================================================================================================

                // $data_payroll = array(
                // 	'is_payroll' => 1
                // );

                // $this->Timesheet_model->update_bulan_gaji($data_payroll,$month_year);

                $session_id = $this->session->userdata('user_id');
                $user_create = $session_id['user_id'];

                $data = array(

                    'employee_id'     => $r->user_id,
                    'wages_type'      => $r->wages_type,
                    'company_id'      => $r->company_id,
                    'office_id'       => $r->office_id,
                    'is_active'       => $r->is_active,
                    'date_of_joining' => $r->date_of_joining,
                    'department_id'   => $r->department_id,
                    'designation_id'  => $r->designation_id,
                    'flag'            => $r->flag,
                    'start_date'      => $tanggal1,
                    'end_date'        => $tanggal2,

                    'durasi'          => $interval_date->d + 1,

                    'libur'           => $jumlah_libur,
                    'libur_kantor'    => $jumlah_libur_kantor,
                    'aktif'           => $jumlah_hadir,
                    'sakit'           => $jumlah_sakit,
                    'izin'            => $jumlah_izin,
                    'cuti'            => $jumlah_cuti,
                    'alpa'            => $jumlah_alpa,
                    'dinas'           => $jumlah_dinas,
                    'terlambat_menit' => $jumlah_terlambat_menit,
                    'terlambat_jam'   => $jumlah_terlambat_jam,
                    'total'           => $jumlah_total,

                    'create_date'     => date('Y-m-d h:i:s'),
                    'create_by'       => $user_create,

                );

                $result = $this->Timesheet_model->add_employee_attendance_rekap_borongan_reguler($data);

                // echo "<pre>";
                // print_r( $result );
                // echo "</pre>";
                // die();

                if ($result) {

                    $Return['result'] = 'Rekap Kehadiran Borongan Berhasil Diproses';
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
            }

            $this->output($Return);
            exit;
        }
    }

    // =============================================================================
    // 0930 REKAP LEMBUR
    // =============================================================================

    // daily attendance > timesheet
    public function lembur_rekap()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Rekap Lembur | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-magic"></i>';
        $data['desc']        = 'PROSES : Proses Rekap Lembur';
        $data['breadcrumbs'] = 'Rekap Lembur ';
        $data['path_url']    = 'rekap_lembur';

        $data['get_all_companies']    = $this->Company_model->get_company();
        $data['all_office_shifts']    = $this->Location_model->all_payroll_jenis();
        $data['all_office_pola']    = $this->Location_model->all_payroll_pola();
        $data['all_bulan_gaji']       = $this->Core_model->all_bulan_gaji_close(TRUE);

        $role_resources_ids        = $this->Core_model->user_role_resource();

        if (in_array('0940', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/lembur_rekap_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // daily attendance list > timesheet
    public function lembur_rekap_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/lembur_rekap_list", $data);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $company_id         = $this->input->get("company_id");
        $jenis_gaji         = $this->input->get("jenis_gaji");
        $month_year         = $this->input->get("month_year");
        $period_id          = $this->input->get("period_id");

        // ===============================================================================================================
        // Tampilkan
        // ===============================================================================================================
        $employee           = $this->Employees_model->get_rekap_lembur($company_id, $jenis_gaji, $month_year);

        /**
         * gaji harian dan borongan
         * ambil tanggal berdasarkan periode
         */
        if ($jenis_gaji == 2 || $jenis_gaji == 3) {
            $xin_tanggal = $this->Timesheet_model->read_periode_bulan($period_id);
        } else {
            $xin_tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
        }

        $start_date = new DateTime($xin_tanggal[0]->start_date);
        $end_date   = new DateTime($xin_tanggal[0]->end_date);

        $data = array();
        $no = 1;
        foreach ($employee->result() as $r) {
            $_start     = (int) $start_date->format('j');

            if ($r->bulan == '01' || $r->bulan == '02' || $r->bulan == '04' || $r->bulan == '06' || $r->bulan == '08' || $r->bulan == '09' || $r->bulan == '11') { // 31

                $info_T29 = $r->T29;
                $info_T30 = $r->T30;
                $info_T31 = $r->T31;
                // Jumlah hari 28 dlm sebulan
            } else 	if ($r->bulan == '03') { // 28

                $info_T29 = '';
                $info_T30 = '';
                $info_T31 = '';
                // Jumlah hari 30 dlm sebulan
            } else 	if ($r->bulan == '05' || $r->bulan == '07' || $r->bulan == '10' || $r->bulan == '12') {

                $info_T29 = $r->T29;
                $info_T30 = $r->T30;
                $info_T31 = '';
                // Lainnya
            } else {

                $info_T29 = '';
                $info_T30 = '';
                $info_T31 = '';
            }

            $attendances = array();
            $max = date_diff($start_date, $end_date);
            $max = ($max->format('%a') + 1);
            $total_hour = 0;
            foreach (range(1, $max) as $i) {
                $d = str_pad($_start, 2, '0', STR_PAD_LEFT);
                $time = in_array($d, array(29, 30, 31)) ? ${"info_T{$d}"} : $r->{"T{$d}"};
                $attendances[] = $time;
                $total_hour += $time;
                $_start = $_start == 31 ? $_start = 1 : $_start + 1;
            }

            // hitung gaji manual untuk tampilan per periode
            $salary_mean = ($r->biaya_jam_1 && $r->jam_1) ? $r->biaya_jam_1 / $r->jam_1 : 0;
            $salary_total = $total_hour * $salary_mean;

            if ($jenis_gaji == 2 || $jenis_gaji == 3) {
                $r->total_jam_lembur = $r->jam_1 = $total_hour;
                $r->total_biaya_lembur = $r->biaya_jam_1 = $salary_total;
            }

            $data[] = array_merge(
                array(
                    $no,
                    strtoupper($r->full_name),
                ),
                $attendances,
                array(
                    $r->total_jam_lembur,
                    $r->jam_1,
                    $r->jam_1_selanjutnya,
                    number_format($r->biaya_jam_1, 0, ',', '.'),
                    number_format($r->biaya_jam_1_selanjutnya, 0, ',', '.'),
                    number_format($r->total_biaya_lembur, 0, ',', '.')
                )
            );
            $no++;
        }

        $output = array(
            "draw"              => $draw,
            "recordsTotal"      => $employee->num_rows(),
            "recordsFiltered"   => $employee->num_rows(),
            "data"              => $data,
        );

        $output['dates_label'] = array();

        while ($start_date <= $end_date) {
            $output['dates_label'][] = $start_date->format('j');
            $start_date->modify('+1 day');
        }

        return json_response($output);
    }

    public function lembur_rekap_proses()
    {

        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('add_type') == 'rekap') {

            $company_id         = $this->input->post("company_id");
            $jenis_gaji         = $this->input->post("jenis_gaji");
            $month_year           = $this->input->post("month_year");

            // echo "<pre>";
            // print_r( $company_id );
            // print_r( $jenis_gaji );

            if ($company_id != 0) {
                $cek_karyawan = $this->Employees_model->get_lembur_jenis_gaji_employees_reguler_recap($jenis_gaji, $company_id);
                $tampilkan_karyawan = $cek_karyawan->result();
            } else {
                $Return['error'] = $this->lang->line('xin_record_not_found');
            }

            $sql2 = "DELETE FROM xin_attendance_time_rekap_lembur_log WHERE 1=1 AND company_id ='" . $company_id . "' AND wages_type ='" . $jenis_gaji . "' AND  month_year = '" . $month_year . "'  ";
            // print_r($sql2);
            // exit();
            $query2   = $this->db->query($sql2);


            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];

            // Simpan Log
            $data_log = array(
                'company_id'      => $company_id,
                'wages_type'      => $jenis_gaji,
                'month_year'      => $month_year,
                'create_date'     => date('Y-m-d h:i:s'),
                'create_by'       => $user_create
            );

            $this->Timesheet_model->add_employee_lembur_rekap_reguler_log($data_log);

            // ==================================================================================================================
            // Tanggal
            // ==================================================================================================================

            $tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
            if (!is_null($tanggal)) {
                $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
                $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

                $start_date    = new DateTime($tanggal[0]->start_date);
                $end_date      = new DateTime($tanggal[0]->end_date);
                $interval_date = $end_date->diff($start_date);

                $bulan   = $tanggal[0]->bulan;
            } else {
                $start_att = '';
                $end_att = '';

                $start_date    = '';
                $end_date      = '';
                $interval_date = '';

                $bulan   = '';
            }

            $tanggal1 = date("Y-m-d", strtotime($start_att));
            $tanggal2 = date("Y-m-d", strtotime($end_att));

            // $xin_tanggal   = $this->Timesheet_model->get_xin_tanggal($month_year);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($start_date, $interval, $end_date);

            foreach ($tampilkan_karyawan as $r) {

                $sql1 = "DELETE FROM xin_attendance_time_rekap_lembur WHERE 1=1 AND employee_id ='" . $r->user_id . "' AND  month_year = '" . $month_year . "'  ";
                // print_r($sql1);
                // exit();
                $query1   = $this->db->query($sql1);

                // ==================================================================================================================
                // Rekap Kehadiran
                // ==================================================================================================================
                $data_dates = array();
                foreach ($period as $dt) {
                    $day                = $dt->format("j"); // Day of the month without leading zeros
                    $attendance_date    = $dt->format("Y-m-d");
                    $cek_status         = $this->Timesheet_model->cek_status_kehadiran_lembur($r->user_id, $attendance_date);

                    if (!is_null($cek_status)) {

                        $cek_lembur = $this->Timesheet_model->cek_jumlah_lembur($r->user_id, $attendance_date);
                        if (!is_null($cek_lembur)) {

                            $tgl = $cek_lembur[0]->jumlah;
                        } else {
                            $tgl = '';
                        }
                    } else {
                        $tgl = '';
                    }

                    $data_dates["tanggal_{$day}"] = $tgl;
                }

                // ==================================================================================================================
                // Rekap Lembur
                // ==================================================================================================================

                $cek_lembur = $this->Timesheet_model->hitung_jumlah_jam_lembur($r->user_id, $tanggal1, $tanggal2);
                $jumlah_lembur = $cek_lembur[0]->jumlah;

                //$jumlah_lembur_total = $cek_lembur[0]->jumlah;

                if ($jumlah_lembur == 0) {

                    $jumlah_jam_lembur_1   = 0;
                    $jumlah_jam_lembur_2   = 0;

                    $jumlah_biaya_lembur_1 = 0;
                    $jumlah_biaya_lembur_2 = 0;

                    $jumlah_jam_lembur     = 0;
                    $jumlah_biaya_lembur   = 0;
                } else if ($jumlah_lembur > 0) {

                    // jam
                    $cek_lembur_jam_1      = $this->Timesheet_model->hitung_jumlah_jam_lembur_1($r->user_id, $tanggal1, $tanggal2);
                    $jumlah_jam_lembur_1   = $cek_lembur_jam_1[0]->jumlah;


                    $cek_lembur_jam_2      = $this->Timesheet_model->hitung_jumlah_jam_lembur_2($r->user_id, $tanggal1, $tanggal2);
                    $jumlah_jam_lembur_2   = $cek_lembur_jam_2[0]->jumlah;


                    $cek_lembur_jam_total     = $this->Timesheet_model->hitung_jumlah_jam_lembur($r->user_id, $tanggal1, $tanggal2);
                    $jumlah_jam_lembur   = $cek_lembur_jam_total[0]->jumlah;

                    // biaya
                    $cek_lembur_biaya_1    = $this->Timesheet_model->hitung_jumlah_biaya_lembur_1($r->user_id, $tanggal1, $tanggal2);
                    $biaya_lembur_1        = $cek_lembur_biaya_1[0]->jumlah;

                    if ($biaya_lembur_1 == 0) {
                        $jumlah_biaya_lembur_1 = 0;
                    } else {
                        $jumlah_biaya_lembur_1 = $biaya_lembur_1;
                    }

                    $cek_lembur_biaya_2    = $this->Timesheet_model->hitung_jumlah_biaya_lembur_2($r->user_id, $tanggal1, $tanggal2);
                    $biaya_lembur_2        = $cek_lembur_biaya_2[0]->jumlah;

                    if ($biaya_lembur_2 == 0) {
                        $jumlah_biaya_lembur_2 = 0;
                    } else {
                        $jumlah_biaya_lembur_2 = $biaya_lembur_2;
                    }

                    // jumlah
                    $jumlah_biaya_lembur   = $jumlah_biaya_lembur_1 + $jumlah_biaya_lembur_2;
                }

                // ==================================================================================================================
                // Simpan Rekap
                // ==================================================================================================================
                $data_simpan = array(
                    'employee_id'     => $r->user_id,
                    'wages_type'      => $r->wages_type,
                    'company_id'      => $r->company_id,
                    'office_id'          => $r->office_id,
                    'is_active'       => $r->is_active,
                    'date_of_joining' => $r->date_of_joining,
                    'department_id'   => $r->department_id,
                    'designation_id'  => $r->designation_id,
                    'month_year'      => $month_year,
                    'bulan'           => $bulan,

                    'total_jam_lembur'          => $jumlah_jam_lembur,
                    'jam_1'                     => $jumlah_jam_lembur_1,
                    'jam_1_selanjutnya'         => $jumlah_jam_lembur_2,
                    'biaya_jam_1'               => $jumlah_biaya_lembur_1,
                    'biaya_jam_1_selanjutnya'   => $jumlah_biaya_lembur_2,
                    'total_biaya_lembur'        => $jumlah_biaya_lembur,

                    'create_date'     => date('Y-m-d h:i:s'),
                    'create_by'       => $user_create
                );

                $data_simpan = array_merge($data_simpan, $data_dates);
                $result = $this->Timesheet_model->add_employee_lembur_rekap_reguler($data_simpan);

                // echo "<pre>";
                // print_r($result);
                // echo "</pre>";
                // die();

                if ($result) {
                    $Return['result'] = 'Rekap Lembur Berhasil Diproses';
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
            }

            $this->output($Return);
            exit;
        }
    }

    // =============================================================================
    // 0940 TARIK ABSENSI REGULER
    // =============================================================================

    // daily attendance > timesheet
    public function attendance_gramasi()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Tarik Grmasi Harian | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-hand-o-up"></i>';
        $data['desc']        = '<span><b>INFORMASI : </b> Proses Tarik Gramasi Setiap Hari ini dilakukan setelah Proses Pengajuan di Input semua </span>';
        $data['breadcrumbs'] = 'Tarik Gramasi (Per Hari / Tanggal)';
        $data['path_url']    = 'attendance_gramasi';

        $data['get_all_companies']    = $this->Company_model->get_company();
        $data['all_office_shifts'] = $this->Location_model->all_payroll_jenis();

        $role_resources_ids        = $this->Core_model->user_role_resource();

        if (in_array('0941', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/timesheet/attendance_gramasi_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/dashboard/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function attendance_gramasi_list_load()
    {

        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_gramasi_list", $data);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $attendance_date    = $this->input->get("attendance_date");
        $jenis_gaji         = $this->input->get("location_id");
        $company_id         = $this->input->get("company_id");


        $employee = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji, $company_id);

        $system   = $this->Core_model->read_setting_info(1);

        $data = array();

        $no = 1;

        foreach ($employee->result() as $r) {

            // get company
            $company = $this->Core_model->read_company_info($r->company_id);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '';
            }
            // get posisi
            $designation = $this->Designation_model->read_designation_information($r->designation_id);

            if (!is_null($designation)) {
                $designation_name = $designation[0]->designation_name;
            } else {
                $designation_name = '';
            }

            // user full name
            $full_name = $r->first_name . ' ' . $r->last_name;

            // get office shift for employee
            $get_day = strtotime($attendance_date);
            $day = date('l', $get_day);

            $office_reguler = $this->Timesheet_model->read_office_jadwal_information_reguler($r->office_shift_id);
           
            if (!is_null($office_reguler)) {
                $monday_in_time      = $office_reguler[0]->monday_in_time;
                $monday_out_time     = $office_reguler[0]->monday_out_time;

                $tuesday_in_time     = $office_reguler[0]->tuesday_in_time;
                $tuesday_out_time    = $office_reguler[0]->tuesday_out_time;

                $wednesday_in_time   = $office_reguler[0]->wednesday_in_time;
                $wednesday_out_time  = $office_reguler[0]->wednesday_out_time;

                $thursday_in_time    = $office_reguler[0]->thursday_in_time;
                $thursday_out_time   = $office_reguler[0]->thursday_out_time;

                $friday_in_time      = $office_reguler[0]->friday_in_time;
                $friday_out_time     = $office_reguler[0]->friday_out_time;

                $saturday_in_time    = $office_reguler[0]->saturday_in_time;
                $saturday_out_time   = $office_reguler[0]->saturday_out_time;

                $sunday_in_time      = $office_reguler[0]->sunday_in_time;
                $sunday_out_time     = $office_reguler[0]->sunday_out_time;
            } else {

                $monday_in_time      = '';
                $tuesday_in_time     = '';
                $wednesday_in_time   = '';
                $thursday_in_time    = '';
                $friday_in_time      = '';
                $saturday_in_time    = '';
                $sunday_in_time      = '';
                $monday_out_time     = '';
                $tuesday_out_time    = '';
                $wednesday_out_time  = '';
                $thursday_out_time   = '';
                $friday_out_time     = '';
                $saturday_out_time   = '';
                $sunday_out_time     = '';
            }

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            // get clock in/clock out of each employee
            if ($day == 'Monday') {
                if ($monday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time =  $monday_in_time;
                    $out_time = $monday_out_time;
                }
            } else if ($day == 'Tuesday') {
                if ($tuesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $tuesday_in_time;
                    $out_time = $tuesday_out_time;
                }
            } else if ($day == 'Wednesday') {
                if ($wednesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $wednesday_in_time;
                    $out_time = $wednesday_out_time;
                }
            } else if ($day == 'Thursday') {
                if ($thursday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $thursday_in_time;
                    $out_time = $thursday_out_time;
                }
            } else if ($day == 'Friday') {
                if ($friday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $friday_in_time;
                    $out_time = $friday_out_time;
                }
            } else if ($day == 'Saturday') {
                if ($saturday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $saturday_in_time;
                    $out_time = $saturday_out_time;
                }
            } else if ($day == 'Sunday') {
                if ($sunday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $sunday_in_time;
                    $out_time = $sunday_out_time;
                }
            }



            // check if clock-in for date
            $attendance_status = '';


            // ==============================================================================================================
            // CEK MASUK
            // =============================================================================================================

            $check = $this->Timesheet_model->attendance_first_in_check_new($r->employee_pin, $attendance_date);

            if ($check->num_rows() > 0) {

                // check clock in time
                $attendance = $this->Timesheet_model->attendance_first_in_new($r->employee_pin, $attendance_date);

                // clock in
                $clock_in = new DateTime($attendance[0]->clock_in);
                $clock_in2 = $clock_in->format('H:i:s');


                $office_time =  new DateTime($in_time . ' ' . $attendance_date);

                // HITUNG TERLAMBAT
                $office_time_new   = strtotime($in_time . ' ' . $attendance_date);
                $clock_in_time_new = strtotime($attendance[0]->clock_in);

                if ($clock_in_time_new == '') {
                    $total_time_l = '0';
                } else if ($clock_in_time_new <= $office_time_new) {
                    $total_time_l = '0';
                } else if ($clock_in_time_new > $office_time_new) {
                    $interval_late = $clock_in->diff($office_time);
                    $hours_l   = $interval_late->format('%h');
                    $minutes_l = $interval_late->format('%i');
                    $total_time_l = $hours_l * 60 + $minutes_l;
                } else {
                    $total_time_l = '0';
                }

                // total hours work/ed
                $total_hrs        = $this->Timesheet_model->total_hours_worked_attendance($r->user_id, $attendance_date);
                $hrs_old_int1     = '';
                $Total            = '';
                $Trest            = '';
                $total_time_rs    = '';
                $hrs_old_int_res1 = '';
                foreach ($total_hrs->result() as $hour_work) {
                    // total work
                    $timee = $hour_work->total_work . ':00';
                    $str_time = $timee;

                    $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

                    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                    $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                    $hrs_old_int1 = $hrs_old_seconds;

                    $Total = gmdate("H:i", $hrs_old_int1);
                }
                if ($Total == '') {
                    $total_work = '0';
                } else {
                    $total_work = $Total;
                }

                // =========================================================================================================
                // HARI LIBUR
                // =========================================================================================================
                $sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%" . $r->company_id . "%' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_libur);
                // echo "</pre>";
                // die;
                $query_check_libur = $this->db->query($sql_check_libur);
                if ($query_check_libur->num_rows() > 0) {
                    foreach ($query_check_libur->result() as $row_check_libur) :
                        $status_libur              = $this->lang->line('xin_on_holiday');
                        $status_libur_keterangan   = "Libur : " . $row_check_libur->event_name;
                    endforeach;
                } else {
                    $status_libur              = '-';
                    $status_libur_keterangan   = '-';
                }

                // =========================================================================================================
                // CUTI
                // =========================================================================================================
                $sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_cuti);
                // echo "</pre>";
                // die;
                $query_check_cuti = $this->db->query($sql_check_cuti);
                if ($query_check_cuti->num_rows() > 0) {
                    foreach ($query_check_cuti->result() as $row_check_cuti) :
                        $status_cuti              = $this->lang->line('xin_on_leave');
                        $status_cuti_keterangan   = "Cuti : " . $row_check_cuti->reason;
                    endforeach;
                } else {
                    $status_cuti              = '-';
                    $status_cuti_keterangan   = '-';
                }

                // =========================================================================================================
                // SAKIT
                // =========================================================================================================
                $sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_sakit);
                // echo "</pre>";
                // die;
                $query_check_sakit = $this->db->query($sql_check_sakit);
                if ($query_check_sakit->num_rows() > 0) {
                    foreach ($query_check_sakit->result() as $row_check_sakit) :
                        $status_sakit = $this->lang->line('xin_on_sick');
                        $status_sakit_keterangan   = "Sakit : " . $row_check_sakit->reason;
                    endforeach;
                } else {
                    $status_sakit     = '-';
                    $status_sakit_keterangan   = '-';
                }

                // =========================================================================================================
                // IZIN
                // =========================================================================================================
                $sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_izin = $this->db->query($sql_check_izin);
                if ($query_check_izin->num_rows() > 0) {
                    foreach ($query_check_izin->result() as $row_check_izin) :
                        $status_izin              = $this->lang->line('xin_on_izin');
                        $status_izin_keterangan   = "Izin : " . $row_check_izin->reason;
                    endforeach;
                } else {
                    $status_izin     = '-';
                    $status_izin_keterangan   = '-';
                }

                // =========================================================================================================
                // DINAS
                // =========================================================================================================
                $sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='" . $r->user_id . "' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_dinas = $this->db->query($sql_check_dinas);
                if ($query_check_dinas->num_rows() > 0) {

                    foreach ($query_check_dinas->result() as $row_check_dinas) :

                        $status_dinas = $this->lang->line('xin_travels_simbol');
                        $status_dinas_keterangan       = "Dinas : " . $row_check_dinas->description;

                    endforeach;
                } else {
                    $status_dinas     = '-';
                    $status_dinas_keterangan = '-';
                }

                // =========================================================================================================
                // LEMBUR
                // =========================================================================================================
                $sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='" . $r->user_id . "' AND overtime_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_lembur = $this->db->query($sql_check_lembur);
                if ($query_check_lembur->num_rows() > 0) {

                    foreach ($query_check_lembur->result() as $row_check_lembur) :

                        $status_lembur = $this->lang->line('xin_overtime_simbol');
                        $status_lembur_keterangan       = "Lembur : " . $row_check_lembur->description;

                    endforeach;
                } else {
                    $status_lembur     = '-';
                    $status_lembur_keterangan = '-';
                }

                // =========================================================================================================
                // PERIKSA
                // =========================================================================================================

                if ($status_libur != '-') {
                    $status                = $this->lang->line('xin_on_holiday');
                    $status_simbol         = $this->lang->line('xin_on_libur_simbol');
                    $attendance_keterangan = $status_libur_keterangan;
                } else if ($status_cuti != '-') {
                    $status                = $this->lang->line('xin_on_leave');
                    $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                    $attendance_keterangan = $status_cuti_keterangan;
                } else if ($status_sakit != '-') {
                    $status                = $this->lang->line('xin_on_sick');
                    $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                    $attendance_keterangan = $status_sakit_keterangan;
                } else if ($status_izin != '-') {
                    $status                = $this->lang->line('xin_on_izin');
                    $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                    $attendance_keterangan = $status_izin_keterangan;
                } else if ($status_dinas != '-') {
                    $status                = $this->lang->line('xin_travels');
                    $status_simbol         = $this->lang->line('xin_travels_simbol');
                    $attendance_keterangan = $status_dinas_keterangan;
                } else if ($status_lembur != '-') {
                    $status                = $this->lang->line('xin_overtime');
                    $status_simbol         = $this->lang->line('xin_overtime_simbol');
                    $attendance_keterangan = $status_lembur_keterangan;
                } else {
                    $status                = $attendance[0]->attendance_status;
                    $status_simbol         = 'H';
                    $attendance_keterangan = 'Masuk';
                }
            } else {

                $clock_in2 = '00:00:00';
                $total_time_l = '0';
                $total_work = '0';


                // =========================================================================================================
                // HARI LIBUR
                // =========================================================================================================
                $sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%" . $r->company_id . "%' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_libur);
                // echo "</pre>";
                // die;
                $query_check_libur = $this->db->query($sql_check_libur);
                if ($query_check_libur->num_rows() > 0) {
                    foreach ($query_check_libur->result() as $row_check_libur) :
                        $status_libur              = $this->lang->line('xin_on_holiday');
                        $status_libur_keterangan   = "Libur : " . $row_check_libur->event_name;
                    endforeach;
                } else {
                    $status_libur              = '-';
                    $status_libur_keterangan   = '-';
                }

                // =========================================================================================================
                // CUTI
                // =========================================================================================================
                $sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_cuti);
                // echo "</pre>";
                // die;
                $query_check_cuti = $this->db->query($sql_check_cuti);
                if ($query_check_cuti->num_rows() > 0) {
                    foreach ($query_check_cuti->result() as $row_check_cuti) :
                        $status_cuti              = $this->lang->line('xin_on_leave');
                        $status_cuti_keterangan   = "Cuti : " . $row_check_cuti->reason;
                    endforeach;
                } else {
                    $status_cuti              = '-';
                    $status_cuti_keterangan   = '-';
                }
                // =========================================================================================================
                // SAKIT
                // =========================================================================================================
                $sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_sakit);
                // echo "</pre>";
                // die;
                $query_check_sakit = $this->db->query($sql_check_sakit);
                if ($query_check_sakit->num_rows() > 0) {
                    foreach ($query_check_sakit->result() as $row_check_sakit) :
                        $status_sakit = $this->lang->line('xin_on_sick');
                        $status_sakit_keterangan   = "Sakit : " . $row_check_sakit->reason;
                    endforeach;
                } else {
                    $status_sakit     = '-';
                    $status_sakit_keterangan   = '-';
                }
                // =========================================================================================================
                // IZIN
                // =========================================================================================================
                $sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_izin = $this->db->query($sql_check_izin);
                if ($query_check_izin->num_rows() > 0) {
                    foreach ($query_check_izin->result() as $row_check_izin) :
                        $status_izin              = $this->lang->line('xin_on_izin');
                        $status_izin_keterangan   = "Izin : " . $row_check_izin->reason;
                    endforeach;
                } else {
                    $status_izin     = '-';
                    $status_izin_keterangan   = '-';
                }
                // =========================================================================================================
                // DINAS
                // =========================================================================================================
                $sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='" . $r->user_id . "' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_dinas = $this->db->query($sql_check_dinas);
                if ($query_check_dinas->num_rows() > 0) {

                    foreach ($query_check_dinas->result() as $row_check_dinas) :

                        $status_dinas              = $this->lang->line('xin_travels_simbol');
                        $status_dinas_keterangan   = "Dinas : " . $row_check_dinas->description;

                    endforeach;
                } else {
                    $status_dinas     = '-';
                    $status_dinas_keterangan = '-';
                }
                // =========================================================================================================
                // LEMBUR
                // =========================================================================================================
                $sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='" . $r->user_id . "' AND overtime_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_lembur = $this->db->query($sql_check_lembur);
                if ($query_check_lembur->num_rows() > 0) {

                    foreach ($query_check_lembur->result() as $row_check_lembur) :

                        $status_lembur = $this->lang->line('xin_overtime_simbol');
                        $status_lembur_keterangan       = "Lembur : " . $row_check_lembur->description;

                    endforeach;
                } else {
                    $status_lembur     = '-';
                    $status_lembur_keterangan = '-';
                }
                // =========================================================================================================
                // PERIKSA
                // =========================================================================================================

                if ($monday_in_time == '' && $day == 'Monday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($tuesday_in_time == '' && $day == 'Tuesday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($wednesday_in_time == '' && $day == 'Wednesday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($thursday_in_time == '' && $day == 'Thursday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($friday_in_time == '' && $day == 'Friday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($saturday_in_time == '' && $day == 'Saturday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($sunday_in_time == '' && $day == 'Sunday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($status_libur != '-') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $status_libur_keterangan;
                } else if ($status_cuti != '-') {
                    // on leave
                    $status                = $this->lang->line('xin_on_leave');
                    $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                    $attendance_keterangan = $status_cuti_keterangan;
                } else if ($status_sakit != '-') {
                    $status                = $this->lang->line('xin_on_sick');
                    $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                    $attendance_keterangan = $status_sakit_keterangan;
                } else if ($status_izin != '-') {
                    $status                = $this->lang->line('xin_on_izin');
                    $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                    $attendance_keterangan = $status_izin_keterangan;
                } else if ($status_lembur != '-') {
                    $status                = $this->lang->line('xin_overtime');
                    $status_simbol         = $this->lang->line('xin_overtime_simbol');
                    $attendance_keterangan = $status_lembur_keterangan;
                } else if ($status_dinas != '-') {
                    $status                = $this->lang->line('xin_travels');
                    $status_simbol         = $this->lang->line('xin_travels_simbol');
                    $attendance_keterangan = $status_dinas_keterangan;
                } else {
                    $status                = $this->lang->line('xin_absent');
                    $status_simbol         = $this->lang->line('xin_absent_simbol');
                    $attendance_keterangan = $this->lang->line('xin_absent_ket');
                }
            }

            // ==============================================================================================================
            // CEK PULANG
            // =============================================================================================================
            // check if clock-out for date
            $check_out = $this->Timesheet_model->attendance_first_out_check_new($r->employee_pin, $attendance_date);

            if ($check_out->num_rows() == 1) {

                /* early time */
                $early_time =  new DateTime($out_time . ' ' . $attendance_date);

                // check clock in time
                $first_out = $this->Timesheet_model->attendance_first_out_new($r->employee_pin, $attendance_date);

                // clock out
                $clock_out = new DateTime($first_out[0]->clock_out);

                if ($first_out[0]->clock_out != '') {

                    $clock_out2 = $clock_out->format('H:i:s');

                    // PULANG CEPAT
                    $early_new_time     = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new = strtotime($first_out[0]->clock_out);

                    if ($early_new_time <= $clock_out_time_new) {

                        $total_time_e = '0';
                    } else {
                        $interval_lateo = $clock_out->diff($early_time);
                        $hours_e        = $interval_lateo->format('%h');
                        $minutes_e      = $interval_lateo->format('%i');
                        $total_time_e   = $hours_e * 60 + $minutes_e;
                    }

                    // OVERTIME
                    $over_time =  new DateTime($out_time . ' ' . $attendance_date);
                    $overtime2 = $over_time->format('H:i:s');

                    // over time
                    $over_time_new = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new1 = strtotime($first_out[0]->clock_out);

                    if ($clock_out_time_new1 <= $over_time_new) {
                        $overtime2 = '-';
                    } else {
                        $interval_lateov = $clock_out->diff($over_time);
                        $hours_ov   = $interval_lateov->format('%h');
                        $minutes_ov = $interval_lateov->format('%i');

                        $overtime2 = $hours_ov * 60 + $minutes_ov;
                    }
                } else {
                    $clock_out2   =  '00:00:00';
                    $total_time_e = '0';
                    $overtime2    = '0';
                }
            } else {
                $clock_out2   =  '00:00:00';
                $total_time_e = '0';
                $overtime2    = '0';
            }

            // attendance date
            $d_date = $this->Core_model->set_date_format($attendance_date);
            //
            $fclckIn = $clock_in2;
            $fclckOut = $clock_out2;

            $clock_in_a = $in_time . ' s/d ' . $out_time;

            if ($fclckIn == '-' || $fclckOut == '-') {

                $total_work = '0';
            } else {

                $total_work_cin  =  new DateTime($fclckIn);
                $total_work_cout =  new DateTime($fclckOut);

                $interval_cin = $total_work_cout->diff($total_work_cin);
                $hours_in   = $interval_cin->format('%h');
                $minutes_in = $interval_cin->format('%i');
                $total_work = $hours_in * 60 + $minutes_in;
            }


            if ($clock_in_a == '00:00:00 s/d 00:00:00') {

                $info_jam = 'Libur';
            } else {

                $info_jam = $clock_in_a;
            }

            $data[] = array(
                $no,
                strtoupper($full_name),
                substr(strtoupper($designation_name), 0, 30),
                $comp_name,
                $info_jam,
                $d_date,
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            );
            $no++;
            // }
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // daily attendance list > timesheet
    public function attendance_gramasi_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session       = $this->session->userdata('username');
        $user_info     = $this->Core_model->read_user_info($session['user_id']);

        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_gramasi_list", $data);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw               = intval($this->input->get("draw"));
        $start              = intval($this->input->get("start"));
        $length             = intval($this->input->get("length"));
        $role_resources_ids = $this->Core_model->user_role_resource();

        $attendance_date    = $this->input->get("attendance_date");
        $jenis_gaji         = $this->input->get("location_id");
        $company_id         = $this->input->get("company_id");



        $employee = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji, $company_id);

        $system   = $this->Core_model->read_setting_info(1);

        $data = array();

        $no = 1;

        foreach ($employee->result() as $r) {

            $sql1 = "DELETE FROM xin_attendance_time WHERE 1=1
                            AND employee_id ='" . $r->user_id . "' AND  attendance_date = '" . $attendance_date . "'  ";
            // print_r($sql1);
            // exit();
            $query1   = $this->db->query($sql1);

            // get company
            $company = $this->Core_model->read_company_info($r->company_id);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '';
            }
            // get posisi
            $designation = $this->Designation_model->read_designation_information($r->designation_id);

            if (!is_null($designation)) {
                $designation_name = $designation[0]->designation_name;
            } else {
                $designation_name = '';
            }

            // user full name
            $full_name = $r->first_name . ' ' . $r->last_name;

            // get office shift for employee
            $get_day = strtotime($attendance_date);
            $day = date('l', $get_day);

            $office_reguler = $this->Timesheet_model->read_office_jadwal_information_reguler($r->office_shift_id);

            if (!is_null($office_reguler)) {
                $monday_in_time      = $office_reguler[0]->monday_in_time;
                $monday_out_time     = $office_reguler[0]->monday_out_time;

                $tuesday_in_time     = $office_reguler[0]->tuesday_in_time;
                $tuesday_out_time    = $office_reguler[0]->tuesday_out_time;

                $wednesday_in_time   = $office_reguler[0]->wednesday_in_time;
                $wednesday_out_time  = $office_reguler[0]->wednesday_out_time;

                $thursday_in_time    = $office_reguler[0]->thursday_in_time;
                $thursday_out_time   = $office_reguler[0]->thursday_out_time;

                $friday_in_time      = $office_reguler[0]->friday_in_time;
                $friday_out_time     = $office_reguler[0]->friday_out_time;

                $saturday_in_time    = $office_reguler[0]->saturday_in_time;
                $saturday_out_time   = $office_reguler[0]->saturday_out_time;

                $sunday_in_time      = $office_reguler[0]->sunday_in_time;
                $sunday_out_time     = $office_reguler[0]->sunday_out_time;
            } else {

                $monday_in_time      = '';
                $tuesday_in_time     = '';
                $wednesday_in_time   = '';
                $thursday_in_time    = '';
                $friday_in_time      = '';
                $saturday_in_time    = '';
                $sunday_in_time      = '';
                $monday_out_time     = '';
                $tuesday_out_time    = '';
                $wednesday_out_time  = '';
                $thursday_out_time   = '';
                $friday_out_time     = '';
                $saturday_out_time   = '';
                $sunday_out_time     = '';
            }

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            // get clock in/clock out of each employee
            if ($day == 'Monday') {
                if ($monday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time =  $monday_in_time;
                    $out_time = $monday_out_time;
                }
            } else if ($day == 'Tuesday') {
                if ($tuesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $tuesday_in_time;
                    $out_time = $tuesday_out_time;
                }
            } else if ($day == 'Wednesday') {
                if ($wednesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $wednesday_in_time;
                    $out_time = $wednesday_out_time;
                }
            } else if ($day == 'Thursday') {
                if ($thursday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $thursday_in_time;
                    $out_time = $thursday_out_time;
                }
            } else if ($day == 'Friday') {
                if ($friday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $friday_in_time;
                    $out_time = $friday_out_time;
                }
            } else if ($day == 'Saturday') {
                if ($saturday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $saturday_in_time;
                    $out_time = $saturday_out_time;
                }
            } else if ($day == 'Sunday') {
                if ($sunday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $sunday_in_time;
                    $out_time = $sunday_out_time;
                }
            }

            // check if clock-in for date
            $attendance_status = '';


            // ==============================================================================================================
            // CEK MASUK
            // =============================================================================================================

            $check_masuk = $this->Timesheet_model->attendance_first_in_check_new($r->employee_pin, $attendance_date);

            if ($check_masuk->num_rows() > 0) {

                // check clock in time
                $attendance = $this->Timesheet_model->attendance_first_in_new($r->employee_pin, $attendance_date);

                // clock in
                $clock_in = new DateTime($attendance[0]->clock_in);
                $clock_in2 = $clock_in->format('H:i:s');


                $office_time =  new DateTime($in_time . ' ' . $attendance_date);

                // HITUNG TERLAMBAT
                $office_time_new   = strtotime($in_time . ' ' . $attendance_date);
                $clock_in_time_new = strtotime($attendance[0]->clock_in);

                if ($clock_in_time_new == '') {
                    $total_time_l = '0';
                } else if ($clock_in_time_new <= $office_time_new) {
                    $total_time_l = '0';
                } else if ($clock_in_time_new > $office_time_new) {
                    $interval_late = $clock_in->diff($office_time);
                    $hours_l   = $interval_late->format('%h');
                    $minutes_l = $interval_late->format('%i');
                    $total_time_l = $hours_l * 60 + $minutes_l;
                } else {
                    $total_time_l = '0';
                }

                // total hours work/ed
                $total_hrs        = $this->Timesheet_model->total_hours_worked_attendance($r->user_id, $attendance_date);
                $hrs_old_int1     = '';
                $Total            = '';
                $Trest            = '';
                $total_time_rs    = '';
                $hrs_old_int_res1 = '';

                foreach ($total_hrs->result() as $hour_work) {
                    // total work
                    $timee = $hour_work->total_work . ':00';
                    $str_time = $timee;

                    $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

                    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                    $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                    $hrs_old_int1 = $hrs_old_seconds;

                    $Total = gmdate("H:i", $hrs_old_int1);
                }

                if ($Total == '') {
                    $total_work = '0';
                } else {
                    $total_work = $Total;
                }

                // =========================================================================================================
                // HARI LIBUR
                // =========================================================================================================
                $sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%" . $r->company_id . "%' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_libur);
                // echo "</pre>";
                // die;
                $query_check_libur = $this->db->query($sql_check_libur);
                if ($query_check_libur->num_rows() > 0) {
                    foreach ($query_check_libur->result() as $row_check_libur) :
                        $status_libur              = $this->lang->line('xin_on_holiday');
                        $status_libur_keterangan   = "Libur : " . $row_check_libur->event_name;
                    endforeach;
                } else {
                    $status_libur              = '-';
                    $status_libur_keterangan   = '-';
                }

                // =========================================================================================================
                // CUTI
                // =========================================================================================================
                $sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_cuti);
                // echo "</pre>";
                // die;
                $query_check_cuti = $this->db->query($sql_check_cuti);
                if ($query_check_cuti->num_rows() > 0) {
                    foreach ($query_check_cuti->result() as $row_check_cuti) :
                        $status_cuti              = $this->lang->line('xin_on_leave');
                        $status_cuti_keterangan   = "Cuti : " . $row_check_cuti->reason;
                    endforeach;
                } else {
                    $status_cuti              = '-';
                    $status_cuti_keterangan   = '-';
                }

                // =========================================================================================================
                // SAKIT
                // =========================================================================================================
                $sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_sakit);
                // echo "</pre>";
                // die;
                $query_check_sakit = $this->db->query($sql_check_sakit);
                if ($query_check_sakit->num_rows() > 0) {
                    foreach ($query_check_sakit->result() as $row_check_sakit) :
                        $status_sakit = $this->lang->line('xin_on_sick');
                        $status_sakit_keterangan   = "Sakit : " . $row_check_sakit->reason;
                    endforeach;
                } else {
                    $status_sakit     = '-';
                    $status_sakit_keterangan   = '-';
                }

                // =========================================================================================================
                // IZIN
                // =========================================================================================================
                $sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_izin = $this->db->query($sql_check_izin);
                if ($query_check_izin->num_rows() > 0) {
                    foreach ($query_check_izin->result() as $row_check_izin) :
                        $status_izin              = $this->lang->line('xin_on_izin');
                        $status_izin_keterangan   = "Izin : " . $row_check_izin->reason;
                    endforeach;
                } else {
                    $status_izin              = '-';
                    $status_izin_keterangan   = '-';
                }

                // =========================================================================================================
                // LIBUR KANTOR
                // =========================================================================================================
                $sql_check_libur = "SELECT * FROM view_karyawan_libur WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_libur);
                // echo "</pre>";
                // die;
                $query_check_libur = $this->db->query($sql_check_libur);
                if ($query_check_libur->num_rows() > 0) {
                    foreach ($query_check_libur->result() as $row_check_libur) :
                        $status_libur_kantor              = $row_check_libur->type_name;
                        $status_libur_kantor_simbol       = $row_check_libur->type_code;
                        $status_libur_kantor_keterangan   = "Jenis Libur : " . $row_check_libur->reason;
                    endforeach;
                } else {
                    $status_libur_kantor              = '-';
                    $status_libur_kantor_simbol       = '-';
                    $status_libur_kantor_keterangan   = '-';
                }

                // =========================================================================================================
                // DINAS
                // =========================================================================================================
                $sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='" . $r->user_id . "' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_dinas = $this->db->query($sql_check_dinas);
                if ($query_check_dinas->num_rows() > 0) {

                    foreach ($query_check_dinas->result() as $row_check_dinas) :

                        $status_dinas = $this->lang->line('xin_travels_simbol');
                        $status_dinas_keterangan       = "Dinas : " . $row_check_dinas->description;

                    endforeach;
                } else {
                    $status_dinas     = '-';
                    $status_dinas_keterangan = '-';
                }

                // =========================================================================================================
                // LEMBUR
                // =========================================================================================================
                $sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='" . $r->user_id . "' AND overtime_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_lembur = $this->db->query($sql_check_lembur);
                if ($query_check_lembur->num_rows() > 0) {

                    foreach ($query_check_lembur->result() as $row_check_lembur) :

                        $status_lembur = $this->lang->line('xin_overtime_simbol');
                        $status_lembur_keterangan       = "Lembur : " . $row_check_lembur->description;

                    endforeach;
                } else {
                    $status_lembur     = '-';
                    $status_lembur_keterangan = '-';
                }

                // =========================================================================================================
                // PERIKSA
                // =========================================================================================================

                if ($status_libur != '-') {
                    $status                = $this->lang->line('xin_on_holiday');
                    $status_simbol         = $this->lang->line('xin_on_libur_simbol');
                    $attendance_keterangan = $status_libur_keterangan;
                } else if ($status_cuti != '-') {
                    $status                = $this->lang->line('xin_on_leave');
                    $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                    $attendance_keterangan = $status_cuti_keterangan;
                } else if ($status_sakit != '-') {
                    $status                = $this->lang->line('xin_on_sick');
                    $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                    $attendance_keterangan = $status_sakit_keterangan;
                } else if ($status_izin != '-') {
                    $status                = $this->lang->line('xin_on_izin');
                    $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                    $attendance_keterangan = $status_izin_keterangan;
                } else if ($status_libur != '-') {
                    $status                = $this->lang->line('xin_on_libur');
                    $status_simbol         = $this->lang->line('xin_on_libur_simbol');
                    $attendance_keterangan = $status_libur_keterangan;
                } else if ($status_dinas != '-') {
                    $status                = $this->lang->line('xin_travels');
                    $status_simbol         = $this->lang->line('xin_travels_simbol');
                    $attendance_keterangan = $status_dinas_keterangan;
                } else if ($status_lembur != '-') {
                    $status                = $this->lang->line('xin_overtime');
                    $status_simbol         = $this->lang->line('xin_overtime_simbol');
                    $attendance_keterangan = $status_lembur_keterangan;
                } else {
                    $status                = $attendance[0]->attendance_status;
                    $status_simbol         = 'H';
                    $attendance_keterangan = 'Masuk';
                }
            } else {

                $clock_in2 = '00:00:00';
                $total_time_l = '0';
                $total_work = '0';


                // =========================================================================================================
                // HARI LIBUR
                // =========================================================================================================
                $sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%" . $r->company_id . "%' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_libur);
                // echo "</pre>";
                // die;
                $query_check_libur = $this->db->query($sql_check_libur);
                if ($query_check_libur->num_rows() > 0) {
                    foreach ($query_check_libur->result() as $row_check_libur) :
                        $status_libur              = $this->lang->line('xin_on_holiday');
                        $status_libur_keterangan   = "Libur : " . $row_check_libur->event_name;
                    endforeach;
                } else {
                    $status_libur              = '-';
                    $status_libur_keterangan   = '-';
                }

                // =========================================================================================================
                // CUTI
                // =========================================================================================================
                $sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_cuti);
                // echo "</pre>";
                // die;
                $query_check_cuti = $this->db->query($sql_check_cuti);
                if ($query_check_cuti->num_rows() > 0) {
                    foreach ($query_check_cuti->result() as $row_check_cuti) :
                        $status_cuti              = $this->lang->line('xin_on_leave');
                        $status_cuti_keterangan   = "Cuti : " . $row_check_cuti->reason;
                    endforeach;
                } else {
                    $status_cuti              = '-';
                    $status_cuti_keterangan   = '-';
                }

                // =========================================================================================================
                // SAKIT
                // =========================================================================================================
                $sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_sakit);
                // echo "</pre>";
                // die;
                $query_check_sakit = $this->db->query($sql_check_sakit);
                if ($query_check_sakit->num_rows() > 0) {
                    foreach ($query_check_sakit->result() as $row_check_sakit) :
                        $status_sakit = $this->lang->line('xin_on_sick');
                        $status_sakit_keterangan   = "Sakit : " . $row_check_sakit->reason;
                    endforeach;
                } else {
                    $status_sakit     = '-';
                    $status_sakit_keterangan   = '-';
                }

                // =========================================================================================================
                // IZIN
                // =========================================================================================================
                $sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_izin = $this->db->query($sql_check_izin);
                if ($query_check_izin->num_rows() > 0) {
                    foreach ($query_check_izin->result() as $row_check_izin) :
                        $status_izin              = $this->lang->line('xin_on_izin');
                        $status_izin_keterangan   = "Izin : " . $row_check_izin->reason;
                    endforeach;
                } else {
                    $status_izin              = '-';
                    $status_izin_keterangan   = '-';
                }

                // =========================================================================================================
                // LIBUR KANTOR
                // =========================================================================================================
                $sql_check_libur = "SELECT * FROM view_karyawan_libur WHERE employee_id ='" . $r->user_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_libur);
                // echo "</pre>";
                // die;
                $query_check_libur = $this->db->query($sql_check_libur);
                if ($query_check_libur->num_rows() > 0) {
                    foreach ($query_check_libur->result() as $row_check_libur) :
                        $status_libur_kantor              = $row_check_libur->type_name;
                        $status_libur_kantor_simbol       = $row_check_libur->type_code;
                        $status_libur_kantor_keterangan   = "Jenis Libur : " . $row_check_libur->reason;
                    endforeach;
                } else {
                    $status_libur_kantor              = '-';
                    $status_libur_kantor_simbol       = '-';
                    $status_libur_kantor_keterangan   = '-';
                }

                // =========================================================================================================
                // DINAS
                // =========================================================================================================
                $sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='" . $r->user_id . "' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_dinas = $this->db->query($sql_check_dinas);
                if ($query_check_dinas->num_rows() > 0) {

                    foreach ($query_check_dinas->result() as $row_check_dinas) :

                        $status_dinas              = $this->lang->line('xin_travels_simbol');
                        $status_dinas_keterangan   = "Dinas : " . $row_check_dinas->description;

                    endforeach;
                } else {
                    $status_dinas     = '-';
                    $status_dinas_keterangan = '-';
                }

                // =========================================================================================================
                // LEMBUR
                // =========================================================================================================
                $sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='" . $r->user_id . "' AND overtime_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_lembur = $this->db->query($sql_check_lembur);
                if ($query_check_lembur->num_rows() > 0) {

                    foreach ($query_check_lembur->result() as $row_check_lembur) :

                        $status_lembur = $this->lang->line('xin_overtime_simbol');
                        $status_lembur_keterangan       = "Lembur : " . $row_check_lembur->description;

                    endforeach;
                } else {
                    $status_lembur     = '-';
                    $status_lembur_keterangan = '-';
                }

                // =========================================================================================================
                // PERIKSA
                // =========================================================================================================

                if ($monday_in_time == '' && $day == 'Monday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($tuesday_in_time == '' && $day == 'Tuesday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($wednesday_in_time == '' && $day == 'Wednesday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($thursday_in_time == '' && $day == 'Thursday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($friday_in_time == '' && $day == 'Friday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($saturday_in_time == '' && $day == 'Saturday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($sunday_in_time == '' && $day == 'Sunday') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $this->lang->line('xin_holiday');
                } else if ($status_libur != '-') {
                    $status                = $this->lang->line('xin_holiday');
                    $status_simbol         = $this->lang->line('xin_libur_simbol');
                    $attendance_keterangan = $status_libur_keterangan;
                } else if ($status_cuti != '-') {
                    // on leave
                    $status                = $this->lang->line('xin_on_leave');
                    $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                    $attendance_keterangan = $status_cuti_keterangan;
                } else if ($status_sakit != '-') {
                    $status                = $this->lang->line('xin_on_sick');
                    $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                    $attendance_keterangan = $status_sakit_keterangan;
                } else if ($status_izin != '-') {
                    $status                = $this->lang->line('xin_on_izin');
                    $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                    $attendance_keterangan = $status_izin_keterangan;
                } else if ($status_libur_kantor != '-') {
                    $status                = $status_libur_kantor;
                    $status_simbol         = $status_libur_kantor_simbol;
                    $attendance_keterangan = $status_libur_kantor_keterangan;
                } else if ($status_lembur != '-') {
                    $status                = $this->lang->line('xin_overtime');
                    $status_simbol         = $this->lang->line('xin_overtime_simbol');
                    $attendance_keterangan = $status_lembur_keterangan;
                } else if ($status_dinas != '-') {
                    $status                = $this->lang->line('xin_travels');
                    $status_simbol         = $this->lang->line('xin_travels_simbol');
                    $attendance_keterangan = $status_dinas_keterangan;
                } else {

                    if ($r->flag == 0) {

                        $status                = $this->lang->line('xin_absent');
                        $status_simbol         = $this->lang->line('xin_absent_simbol');
                        $attendance_keterangan = $this->lang->line('xin_absent_ket');
                    } else if ($r->flag == 1) {

                        $status                = 'Free';
                        $status_simbol         = 'F';
                        $attendance_keterangan = 'Bebas Tanpa Absensi Mesin Finger';
                    }
                }
            }

            // ==============================================================================================================
            // CEK PULANG
            // =============================================================================================================
            // check if clock-out for date
            $check_out = $this->Timesheet_model->attendance_first_out_check_new($r->employee_pin, $attendance_date);

            if ($check_out->num_rows() == 1) {

                /* early time */
                $early_time =  new DateTime($out_time . ' ' . $attendance_date);

                // check clock in time
                $first_out = $this->Timesheet_model->attendance_first_out_new($r->employee_pin, $attendance_date);

                // clock out
                $clock_out = new DateTime($first_out[0]->clock_out);

                if ($first_out[0]->clock_out != '') {

                    $clock_out2 = $clock_out->format('H:i:s');

                    // PULANG CEPAT
                    $early_new_time     = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new = strtotime($first_out[0]->clock_out);

                    if ($early_new_time <= $clock_out_time_new) {

                        $total_time_e = '0';
                    } else {
                        $interval_lateo = $clock_out->diff($early_time);
                        $hours_e        = $interval_lateo->format('%h');
                        $minutes_e      = $interval_lateo->format('%i');
                        $total_time_e   = $hours_e * 60 + $minutes_e;
                    }

                    // OVERTIME
                    $over_time =  new DateTime($out_time . ' ' . $attendance_date);
                    $overtime2 = $over_time->format('H:i:s');

                    // over time
                    $over_time_new = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new1 = strtotime($first_out[0]->clock_out);

                    if ($clock_out_time_new1 <= $over_time_new) {
                        $overtime2 = '-';
                    } else {
                        $interval_lateov = $clock_out->diff($over_time);
                        $hours_ov   = $interval_lateov->format('%h');
                        $minutes_ov = $interval_lateov->format('%i');

                        $overtime2 = $hours_ov * 60 + $minutes_ov;
                    }
                } else {
                    $clock_out2   =  '00:00:00';
                    $total_time_e = '0';
                    $overtime2    = '0';
                }
            } else {
                $clock_out2   =  '00:00:00';
                $total_time_e = '0';
                $overtime2    = '0';
            }

            // attendance date
            $d_date = $this->Core_model->set_date_format($attendance_date);
            //
            $fclckIn = $clock_in2;
            $fclckOut = $clock_out2;

            $clock_in_a = $in_time . ' s/d ' . $out_time;

            if ($fclckIn == '-' || $fclckOut == '-') {

                $total_work = '0';
            } else {

                $total_work_cin  =  new DateTime($fclckIn);
                $total_work_cout =  new DateTime($fclckOut);

                $interval_cin = $total_work_cout->diff($total_work_cin);
                $hours_in   = $interval_cin->format('%h');
                $minutes_in = $interval_cin->format('%i');
                $total_work = $hours_in * 60 + $minutes_in;
            }

            // ==============================================================================================================
            // CEK PULANG
            // =============================================================================================================


            // =========================================================================================================
            // PERIKSA
            // =========================================================================================================

            $fclckIn  = $clock_in2;
            $fclckOut = $clock_out2;

            $clock_in_a = $in_time . ' s/d ' . $out_time;

            if ($clock_in_a == '00:00:00 s/d 00:00:00') {
                $jd = 'Libur';
            } else {
                $jd = $clock_in_a;
            }

            if ($clock_in2 == '00:00:00' && $clock_out2 == '00:00:00') {

                if ($jd == 'Libur') {

                    $status                = 'Libur';
                    $status_simbol         = 'L';
                    $attendance_keterangan = 'Libur';
                } else {

                    if ($status_libur != '-') {
                        $status                = $this->lang->line('xin_holiday');
                        $status_simbol         = $this->lang->line('xin_libur_simbol');
                        $attendance_keterangan = $status_libur_keterangan;
                    } else if ($status_cuti != '-') {
                        // on leave
                        $status                = $this->lang->line('xin_on_leave');
                        $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                        $attendance_keterangan = $status_cuti_keterangan;
                    } else if ($status_sakit != '-') {
                        $status                = $this->lang->line('xin_on_sick');
                        $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                        $attendance_keterangan = $status_sakit_keterangan;
                    } else if ($status_izin != '-') {
                        $status                = $this->lang->line('xin_on_izin');
                        $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                        $attendance_keterangan = $status_izin_keterangan;
                    } else if ($status_libur_kantor != '-') {
                        $status                = $status_libur_kantor;
                        $status_simbol         = $status_libur_kantor_simbol;
                        $attendance_keterangan = $status_libur_kantor_keterangan;
                    } else if ($status_dinas != '-') {
                        $status                = $this->lang->line('xin_travels');
                        $status_simbol         = $this->lang->line('xin_travels_simbol');
                        $attendance_keterangan = $status_dinas_keterangan;
                    } else {

                        if ($r->flag == 0) {
                            $status                = $this->lang->line('xin_absent');
                            $status_simbol         = $this->lang->line('xin_absent_simbol');
                            $attendance_keterangan = $this->lang->line('xin_absent_ket');
                        } else if ($r->flag == 1) {
                            $status                = 'Free';
                            $status_simbol         = 'F';
                            $attendance_keterangan = 'Bebas Tanpa Abensi Mesin Finger';
                        }
                    }
                }
            } else {

                if ($clock_in2 != '00:00:00' && $clock_out2 != '00:00:00') {

                    if ($status_lembur != '-') {
                        $status                = $this->lang->line('xin_overtime');
                        $status_simbol         = $this->lang->line('xin_overtime_simbol');
                        $attendance_keterangan = $status_lembur_keterangan;
                    } else if ($status_dinas != '-') {
                        $status                = $this->lang->line('xin_travels');
                        $status_simbol         = $this->lang->line('xin_travels_simbol');
                        $attendance_keterangan = $status_dinas_keterangan;
                    } else if ($status_izin != '-') {
                        $status                = $this->lang->line('xin_on_izin');
                        $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                        $attendance_keterangan = $status_izin_keterangan;
                    }

                    // else if ($status_libur_kantor !='-' )
                    // {
                    // 	 $status                = $this->lang->line('xin_on_libur');
                    // 	 $status_simbol         = $this->lang->line('xin_on_libur_simbol');
                    // 	 $attendance_keterangan = $status_libur_kantor_keterangan;
                    // }

                    else if ($status_cuti != '-') {
                        $status                = $this->lang->line('xin_on_leave');
                        $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                        $attendance_keterangan = $status_cuti_keterangan;
                    } else if ($status_sakit != '-') {
                        $status                = $this->lang->line('xin_on_sick');
                        $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                        $attendance_keterangan = $status_sakit_keterangan;
                    } else {

                        if ($r->flag == 0) {
                            $status                = 'Hadir';
                            $status_simbol         = 'H';
                            $attendance_keterangan = 'Masuk';
                        } else if ($r->flag == 1) {
                            $status                = 'Free';
                            $status_simbol         = 'F';
                            $attendance_keterangan = 'Bebas Tanpa Abensi Mesin Finger';
                        }
                    }
                } else if ($clock_in2 == '00:00:00' && $clock_out2 != '00:00:00') {

                    if ($status_dinas != '-') {
                        $status                = $this->lang->line('xin_travels');
                        $status_simbol         = $this->lang->line('xin_travels_simbol');
                        $attendance_keterangan = $status_dinas_keterangan;
                    } else if ($status_izin != '-') {
                        $status                = $this->lang->line('xin_on_izin');
                        $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                        $attendance_keterangan = $status_izin_keterangan;
                    }

                    // else if ($status_libur_kantor !='-' )
                    // {
                    // 	 $status                = $this->lang->line('xin_on_libur');
                    // 	 $status_simbol         = $this->lang->line('xin_on_libur_simbol');
                    // 	 $attendance_keterangan = $status_libur_kantor_keterangan;
                    // }

                    else if ($status_cuti != '-') {
                        // on leave
                        $status                = $this->lang->line('xin_on_leave');
                        $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                        $attendance_keterangan = $status_cuti_keterangan;
                    } else if ($status_sakit != '-') {
                        $status                = $this->lang->line('xin_on_sick');
                        $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                        $attendance_keterangan = $status_sakit_keterangan;
                    } else {
                        if ($r->flag == 0) {
                            $status                = $this->lang->line('xin_absent');
                            $status_simbol         = $this->lang->line('xin_absent_simbol');
                            $attendance_keterangan = $this->lang->line('xin_absent_ket');
                        } else if ($r->flag == 1) {
                            $status                = 'Free';
                            $status_simbol         = 'F';
                            $attendance_keterangan = 'Bebas Tanpa Abensi Mesin Finger';
                        }
                    }
                } else if ($clock_in2 != '00:00:00' && $clock_out2 == '00:00:00') {

                    if ($status_dinas != '-') {
                        $status                = $this->lang->line('xin_travels');
                        $status_simbol         = $this->lang->line('xin_travels_simbol');
                        $attendance_keterangan = $status_dinas_keterangan;
                    } else if ($status_izin != '-') {
                        $status                = $this->lang->line('xin_on_izin');
                        $status_simbol         = $this->lang->line('xin_on_izin_simbol');
                        $attendance_keterangan = $status_izin_keterangan;
                    }

                    // else if ($status_libur_kantor !='-' )
                    // {
                    // 	 $status                = $this->lang->line('xin_on_libur');
                    // 	 $status_simbol         = $this->lang->line('xin_on_libur_simbol');
                    // 	 $attendance_keterangan = $status_libur_kantor_keterangan;
                    // }

                    else if ($status_sakit != '-') {
                        $status                = $this->lang->line('xin_on_sick');
                        $status_simbol         = $this->lang->line('xin_on_sick_simbol');
                        $attendance_keterangan = $status_sakit_keterangan;
                    } else if ($status_cuti != '-') {
                        // on leave
                        $status                = $this->lang->line('xin_on_leave');
                        $status_simbol         = $this->lang->line('xin_on_leave_simbol');
                        $attendance_keterangan = $status_cuti_keterangan;
                    } else {

                        if ($r->flag == 0) {
                            $status                = $this->lang->line('xin_absent');
                            $status_simbol         = $this->lang->line('xin_absent_simbol');
                            $attendance_keterangan = $this->lang->line('xin_absent_ket');
                        } else if ($r->flag == 1) {
                            $status                = 'Free';
                            $status_simbol         = 'F';
                            $attendance_keterangan = 'Bebas Tanpa Abensi Mesin Finger';
                        }
                    }
                }
            }



            $sql2 = "INSERT INTO xin_attendance_time
                                (
                                    employee_id,
                                    employee_pin,
                                    company_id,
                                     location_id,
                                     date_of_joining,
                                     jenis_gaji,
                                     jenis_kerja,
                                     attendance_jadwal,
                                     attendance_date,
                                     clock_in,
                                     clock_out,
                                     time_late,
                                     early_leaving,
                                     overtime,
                                     total_work,
                                     attendance_status,
                                     attendance_status_simbol,
                                     attendance_keterangan,
                                     rekap_date

                                ) VALUES
                                (
                                    '$r->user_id',
                                    '$r->employee_pin',
                                    '$r->company_id',
                                    '$r->location_id',
                                    '$r->date_of_joining',
                                    '$jenis_gaji',
                                    'R',
                                    '$jd',
                                     '$attendance_date',
                                     '$fclckIn',
                                     '$fclckOut',
                                     '$total_time_l',
                                     '$total_time_e',
                                     '$overtime2',
                                     '$total_work',
                                     '$status',
                                     '$status_simbol',
                                     '$attendance_keterangan',
                                     NOW()


                                )";

            // print_r($sql2);
            // exit();

            $query2 = $this->db->query($sql2);

            if ($fclckIn == '00:00:00') {
                $jam_masuk = '-';
            } else {
                $jam_masuk = $fclckIn;
            }

            if ($fclckOut == '00:00:00') {
                $jam_pulang = '-';
            } else {
                $jam_pulang = $fclckOut;
            }

            $data[] = array(
                $no,
                strtoupper($full_name),
                substr(strtoupper($designation_name), 0, 30),
                $comp_name,
                $jd,
                $d_date,
                $status,
                $jam_masuk,
                $jam_pulang,
                $total_time_l,
                $total_time_e,
                $overtime2,
                $total_work,
                $attendance_keterangan
            );
            $no++;
            // }
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // =============================================================================
    // TAMPILKAN
    // =============================================================================

    // get company > employees
    public function get_employees()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/get_employees", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > employees
    public function get_periode()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'month_year' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/get_periode", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function get_periode_harian()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'month_year' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/get_periode_harian", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function get_periode_borongan()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'month_year' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/get_periode_borongan", $data);
        } else {
            redirect('admin/');
        }
    }

    // get company > employees
    public function get_employees_office()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/get_employees_office", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > projects
    public function get_company_project()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/tasks/get_company_project", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > employees
    public function get_company_employees()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/tasks/get_employees", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // daily attendance list > timesheet
    public function dtwise_attendance_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/timesheet/attendance_list", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $employee = $this->Core_model->read_user_attendance_info();

        $data = array();

        foreach ($employee->result() as $r) {
            $data[] = array('', '', '', '', '', '', '', '', '', '', '');
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // date wise attendance list > timesheet
    public function date_wise_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        $user_info = $this->Core_model->read_user_info($session['user_id']);
        if (!empty($session)) {
            $this->load->view("admin/timesheet/date_wise", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();

        $employee_id = $this->input->get("user_id");

        $system = $this->Core_model->read_setting_info(1);
        $employee = $this->Core_model->read_user_info($employee_id);

        $start_date = new DateTime($this->input->get("start_date"));
        $end_date = new DateTime($this->input->get("end_date"));
        $end_date = $end_date->modify('+1 day');

        $interval_re = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval_re, $end_date);
        $attendance_arr = array();

        $data = array();
        foreach ($date_range as $date) {
            $attendance_date =  $date->format("Y-m-d");

            $get_day = strtotime($attendance_date);
            $day = date('l', $get_day);

            // office shift
            $office_shift = $this->Timesheet_model->read_office_shift_information($employee[0]->office_shift_id);

            // get clock in/clock out of each employee
            if ($day == 'Monday') {
                if ($monday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->monday_in_time;
                    $out_time = $office_shift[0]->monday_out_time;
                }
            } else if ($day == 'Tuesday') {
                if ($office_shift[0]->tuesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->tuesday_in_time;
                    $out_time = $office_shift[0]->tuesday_out_time;
                }
            } else if ($day == 'Wednesday') {
                if ($office_shift[0]->wednesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->wednesday_in_time;
                    $out_time = $office_shift[0]->wednesday_out_time;
                }
            } else if ($day == 'Thursday') {
                if ($office_shift[0]->thursday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->thursday_in_time;
                    $out_time = $office_shift[0]->thursday_out_time;
                }
            } else if ($day == 'Friday') {
                if ($office_shift[0]->friday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->friday_in_time;
                    $out_time = $office_shift[0]->friday_out_time;
                }
            } else if ($day == 'Saturday') {
                if ($office_shift[0]->saturday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->saturday_in_time;
                    $out_time = $office_shift[0]->saturday_out_time;
                }
            } else if ($day == 'Sunday') {
                if ($office_shift[0]->sunday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->sunday_in_time;
                    $out_time = $office_shift[0]->sunday_out_time;
                }
            }
            // check if clock-in for date
            $attendance_status = '';
            $check = $this->Timesheet_model->attendance_first_in_check($employee[0]->user_id, $attendance_date);
            if ($check->num_rows() > 0) {
                // check clock in time
                $attendance = $this->Timesheet_model->attendance_first_in($employee[0]->user_id, $attendance_date);

                // clock in
                $clock_in = new DateTime($attendance[0]->clock_in);
                $clock_in2 = $clock_in->format('H:i:s');

                $clkInIp = $clock_in2;

                $office_time =  new DateTime($in_time . ' ' . $attendance_date);
                //time diff > total time late
                $office_time_new = strtotime($in_time . ' ' . $attendance_date);
                $clock_in_time_new = strtotime($attendance[0]->clock_in);
                if ($clock_in_time_new <= $office_time_new) {
                    $total_time_l = '-';
                } else {
                    $interval_late = $clock_in->diff($office_time);
                    $hours_l   = $interval_late->format('%h');
                    $minutes_l = $interval_late->format('%i');
                    $total_time_l = $hours_l . "j " . $minutes_l . "m ";
                }

                // total hours work/ed
                $total_hrs = $this->Timesheet_model->total_hours_worked_attendance($employee[0]->user_id, $attendance_date);
                $hrs_old_int1 = 0;
                $Total = '';
                $Trest = '';
                $hrs_old_seconds = 0;
                $hrs_old_seconds_rs = 0;
                $total_time_rs = '';
                $hrs_old_int_res1 = 0;
                foreach ($total_hrs->result() as $hour_work) {
                    // total work
                    $timee = $hour_work->total_work . ':00';
                    $str_time = $timee;

                    $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

                    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                    $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                    $hrs_old_int1 += $hrs_old_seconds;

                    $Total = gmdate("H:i", $hrs_old_int1);
                }
                if ($Total == '') {
                    $total_work = '-';
                } else {
                    $total_work = $Total;
                }

                // total rest >
                $total_rest = $this->Timesheet_model->total_rest_attendance($employee[0]->user_id, $attendance_date);
                foreach ($total_rest->result() as $rest) {
                    // total rest
                    $str_time_rs = $rest->total_rest . ':00';
                    //$str_time_rs =$timee_rs;

                    $str_time_rs = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time_rs);

                    sscanf($str_time_rs, "%d:%d:%d", $hours_rs, $minutes_rs, $seconds_rs);

                    $hrs_old_seconds_rs = $hours_rs * 3600 + $minutes_rs * 60 + $seconds_rs;

                    $hrs_old_int_res1 += $hrs_old_seconds_rs;

                    $total_time_rs = gmdate("H:i", $hrs_old_int_res1);
                }

                // check attendance status
                $status = $attendance[0]->attendance_status;
                if ($total_time_rs == '') {
                    $Trest = '00:00:00';
                } else {
                    $Trest = $total_time_rs;
                }
            } else {
                $clock_in2 = '00:00:00';
                $total_time_l = '-';
                $total_work = '-';
                $Trest = '00:00:00';
                $clkInIp = $clock_in2;
                // get holiday/leave or absent
                /* attendance status */
                // get holiday
                $h_date_chck = $this->Timesheet_model->holiday_date_check($attendance_date);
                $holiday_arr = array();
                if ($h_date_chck->num_rows() == 1) {
                    $h_date = $this->Timesheet_model->holiday_date($attendance_date);
                    $begin = new DateTime($h_date[0]->start_date);
                    $end = new DateTime($h_date[0]->end_date);
                    $end = $end->modify('+1 day');

                    $interval = new DateInterval('P1D');
                    $daterange = new DatePeriod($begin, $interval, $end);

                    foreach ($daterange as $date) {
                        $holiday_arr[] =  $date->format("Y-m-d");
                    }
                } else {
                    $holiday_arr[] = '99-99-99';
                }

                // get leave/employee
                $leave_date_chck = $this->Timesheet_model->leave_date_check($employee[0]->user_id, $attendance_date);
                $leave_arr = array();
                if ($leave_date_chck->num_rows() == 1) {
                    $leave_date = $this->Timesheet_model->leave_date($employee[0]->user_id, $attendance_date);
                    $begin1 = new DateTime($leave_date[0]->from_date);
                    $end1 = new DateTime($leave_date[0]->to_date);
                    $end1 = $end1->modify('+1 day');

                    $interval1 = new DateInterval('P1D');
                    $daterange1 = new DatePeriod($begin1, $interval1, $end1);

                    foreach ($daterange1 as $date1) {
                        $leave_arr[] =  $date1->format("Y-m-d");
                    }
                } else {
                    $leave_arr[] = '99-99-99';
                }

                if ($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
                    $status = $this->lang->line('xin_holiday');
                } else if (in_array($attendance_date, $holiday_arr)) { // holiday
                    $status = $this->lang->line('xin_holiday');
                } else if (in_array($attendance_date, $leave_arr)) { // on leave
                    $status = $this->lang->line('xin_on_leave');
                } else {
                    $status = $this->lang->line('xin_absent');
                }
            }

            // check if clock-out for date
            $check_out = $this->Timesheet_model->attendance_first_out_check($employee[0]->user_id, $attendance_date);
            if ($check_out->num_rows() == 1) {
                /* early time */
                $early_time =  new DateTime($out_time . ' ' . $attendance_date);
                // check clock in time
                $first_out = $this->Timesheet_model->attendance_first_out($employee[0]->user_id, $attendance_date);
                // clock out
                $clock_out = new DateTime($first_out[0]->clock_out);

                if ($first_out[0]->clock_out != '') {
                    $clock_out2 = $clock_out->format('H:i:s');

                    // early leaving
                    $early_new_time = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new = strtotime($first_out[0]->clock_out);

                    if ($early_new_time <= $clock_out_time_new) {
                        $total_time_e = '-';
                    } else {
                        $interval_lateo = $clock_out->diff($early_time);
                        $hours_e   = $interval_lateo->format('%h');
                        $minutes_e = $interval_lateo->format('%i');
                        $total_time_e = $hours_e . "j " . $minutes_e . "m ";
                    }

                    /* over time */
                    $over_time =  new DateTime($out_time . ' ' . $attendance_date);
                    $overtime2 = $over_time->format('H:i:s');
                    // over time
                    $over_time_new = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new1 = strtotime($first_out[0]->clock_out);

                    if ($clock_out_time_new1 <= $over_time_new) {
                        $overtime2 = '-';
                    } else {
                        $interval_lateov = $clock_out->diff($over_time);
                        $hours_ov   = $interval_lateov->format('%h');
                        $minutes_ov = $interval_lateov->format('%i');
                        $overtime2 = $hours_ov . "j " . $minutes_ov . "m ";
                    }
                } else {
                    $clock_out2 =  '00:00:00';
                    $total_time_e = '-';
                    $overtime2 = '-';
                }
            } else {
                $clock_out2 =  '00:00:00';
                $total_time_e = '-';
                $overtime2 = '-';
            }
            // user full name
            $full_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
            // get company
            $company = $this->Core_model->read_company_info($employee[0]->company_id);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '--';
            }
            // attendance date
            $tdate = $this->Core_model->set_date_format($attendance_date);

            $fclckIn = $clock_in2;
            $fclckOut = $clock_out2;

            $data[] = array(
                $full_name,
                $employee[0]->employee_id,
                $comp_name,
                $status,
                $tdate,
                $fclckIn,
                $fclckOut,
                $total_time_l,
                $total_time_e,
                $overtime2,
                $total_work,
                $Trest
            );
        }

        $output = array(
            "draw" => $draw,
            //"recordsTotal" => count($date_range),
            //"recordsFiltered" => count($date_range),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // get record of leave by id > modal
    public function read_variation_record()
    {
        $data['title'] = $this->Core_model->site_title();
        $variation_id = $this->input->get('variation_id');
        $result = $this->Timesheet_model->read_variation_information($variation_id);

        $data = array(
            'variation_id' => $result[0]->variation_id,
            'project_id' => $result[0]->project_id,
            'company_id' => $result[0]->company_id,
            'client_approval' => $result[0]->client_approval,
            'created_by' => $result[0]->created_by,
            'variation_name' => $result[0]->variation_name,
            'assigned_to' => $result[0]->assigned_to,
            'start_date' => $result[0]->start_date,
            'end_date' => $result[0]->end_date,
            'variation_hours' => $result[0]->variation_hours,
            'variation_status' => $result[0]->variation_status,
            'variation_no' => $result[0]->variation_no,
            'description' => $result[0]->description,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Core_model->all_employees()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/timesheet/tasks/dialog_task', $data);
        } else {
            redirect('admin/');
        }
    }

    // get record of attendance
    public function read()
    {
        $data['title'] = $this->Core_model->site_title();
        $attendance_id = $this->input->get('attendance_id');
        $result = $this->Timesheet_model->read_attendance_information($attendance_id);
        $user = $this->Core_model->read_user_info($result[0]->employee_id);
        // user full name
        $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;

        $in_time = new DateTime($result[0]->clock_in);
        $out_time = new DateTime($result[0]->clock_out);

        $clock_in = $in_time->format('H:i:s');
        if ($result[0]->clock_out == '') {
            $clock_out = '';
        } else {
            $clock_out = $out_time->format('H:i:s');
        }

        $data = array(
            'time_attendance_id' => $result[0]->time_attendance_id,
            'employee_id' => $result[0]->employee_id,
            'full_name' => $full_name,
            'attendance_date' => $result[0]->attendance_date,
            'clock_in' => $clock_in,
            'clock_out' => $clock_out
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/timesheet/dialog_attendance', $data);
        } else {
            redirect('admin/');
        }
    }

    // get record of holiday

    //read_map_info
    public function read_map_info()
    {
        $data['title'] = $this->Core_model->site_title();
        //$office_shift_id = $this->input->get('office_shift_id');
        //$result = $this->Timesheet_model->read_office_shift_information($office_shift_id);

        $data = array(
            //	'office_shift_id' => $result[0]->office_shift_id,
            //'company_id' => $result[0]->company_id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/timesheet/dialog_read_map', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and update info in database
    public function default_shift()
    {

        if ($this->input->get('office_shift_id')) {

            $id = $this->input->get('office_shift_id');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $data = array(
                'default_shift' => '0'
            );

            $data2 = array(
                'default_shift' => '1'
            );

            $result = $this->Timesheet_model->update_default_shift_zero($data);
            $result = $this->Timesheet_model->update_default_shift_record($data2, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_shift_default_made');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // delete attendance record
    public function delete_attendance()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_attendance_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_employe_attendance_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete shift record
    public function delete_shift()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_shift_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_shift_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    //  #### EXTEND FUNCTION 
    function get_office_time_in_out($attendance_date,$shift_id,$jenis = 'REGULAR'){
        $get_day = strtotime($attendance_date);
        $day = date('l', $get_day);
        if ($jenis == 'REGULAR') {
            # code...
            $office_time = $this->Timesheet_model->read_office_jadwal_information_reguler($shift_id);
        }else{
            $office_time = $this->Timesheet_model->read_office_jadwal_information_shift($shift_id);
        }

        if (!is_null($office_time)) {
            switch ($day) {
                case 'Monday':
                    $in_time =  $office_time[0]->monday_in_time;
                    $out_time = $office_time[0]->monday_out_time;
                    break;    
                case 'Tuesday':
                    $in_time =  $office_time[0]->tuesday_in_time;
                    $out_time = $office_time[0]->tuesday_out_time;
                    break;
                case 'Wednesday':
                    $in_time =  $office_time[0]->wednesday_in_time;
                    $out_time = $office_time[0]->wednesday_out_time;
                    break;
                case 'Thursday':
                    $in_time =  $office_time[0]->thursday_in_time;
                    $out_time = $office_time[0]->thursday_out_time;
                    break;
                case 'Friday':
                    $in_time =  $office_time[0]->friday_in_time;
                    $out_time = $office_time[0]->friday_out_time;
                    break;
                case 'Saturday':
                    $in_time =  $office_time[0]->saturday_in_time;
                    $out_time = $office_time[0]->saturday_out_time;
                    break;
                case 'Sunday':
                    $in_time =  $office_time[0]->sunday_in_time;
                    $out_time = $office_time[0]->sunday_out_time;
                    break;
                default:
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                    break;
            }
        } else {
            $in_time = '00:00:00';
            $out_time = '00:00:00';
        }
       $output['in_time'] = $in_time;
       $output['out_time'] = $out_time;
       return $output;
    }
    // cek hari libur 
    public function check_hari_libur($company_id,$attendance_date){
        $sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%" . $company_id . "%' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";

        $check_libur = $this->db->query($sql_check_libur)->row();
       
        if (!empty($check_libur)) {
            $output['status'] = true;
            $output['attendance_status']          = $this->lang->line('xin_on_holiday');
            $output['attendance_simbol']    = $check_libur->type_code;
            $output['attendance_keterangan']   = "Libur : " . $check_libur->event_name;
        }else{
            $output['status'] = false;
        }
        return $output;

    }
    // cek hari libur kantor per karyawan
    public function check_hari_libur_kantor($employee_id,$attendance_date){
        $sql_check_libur = "SELECT * FROM view_karyawan_libur WHERE employee_id ='" . $employee_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";

        $check_libur = $this->db->query($sql_check_libur)->row();
       
        if (!empty($check_libur)) {
            $output['status'] = true;
            $output['attendance_status']          = $check_libur->type_name;
            $output['attendance_simbol']    = $check_libur->type_code;
            $output['attendance_keterangan']   = "Jenis Libur : " . $check_libur->reason;
        }else{
            $output['status'] = false;
        }
        return $output;
    }
    //  cek cuti karyawan
    public function check_cuti_karyawan($employee_id,$attendance_date){
        $sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='" . $employee_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
    
        $row_check_cuti = $this->db->query($sql_check_cuti)->row();
        if (!empty($row_check_cuti)) {
            $output['status'] = true;
            $output['attendance_status']    =$this->lang->line('xin_on_leave');
            $output['attendance_simbol']    = $this->lang->line('xin_on_leave_simbol');
            $output['attendance_keterangan']   = "Cuti : " . $row_check_cuti->reason;
        }else{
            $output['status'] = false;
        }
        return $output;
    }
    //  cek dinas karyawan
    public function check_dinas_karyawan($employee_id,$attendance_date){
        $sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='" . $employee_id . "' AND start_date <= '" . $attendance_date . "' AND end_date >= '" . $attendance_date . "' ";
        $row_check_dinas = $this->db->query($sql_check_dinas)->row();
        if (!empty($row_check_dinas)) {
            $output['status'] = true;
            $output['attendance_status']    =$this->lang->line('xin_travels');
            $output['attendance_simbol']    = $this->lang->line('xin_travels_simbol');
            $output['attendance_keterangan']   = "Dinas : " . $row_check_dinas->description;
        }else{
            $output['status'] = false;
        }
        return $output;
       
    }
    // check sakit / izin
    public function check_sakit_izin_karyawan($employee_id,$attendance_date){
        $sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='" . $employee_id. "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
        $sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='" . $employee_id . "' AND from_date <= '" . $attendance_date . "' AND to_date >= '" . $attendance_date . "' ";
        $row_check_sakit = $this->db->query($sql_check_sakit)->row();
        $row_check_izin = $this->db->query($sql_check_izin)->row();
        // check sakit
        if (!empty($row_check_sakit)) {
            $output['status'] = true;
            $output['attendance_status']    =$this->lang->line('xin_on_sick');
            $output['attendance_simbol']    = $this->lang->line('xin_on_sick_simbol');
            $output['attendance_keterangan']   = "Sakit : " . $row_check_sakit->reason;
        }
        // check izin
        elseif (!empty($row_check_izin)) {
            $output['status'] = true;
            $output['attendance_status']    =$this->lang->line('xin_on_izin');
            $output['attendance_simbol']    = $this->lang->line('xin_on_izin_simbol');
            $output['attendance_keterangan']   = "Izin : " . $row_check_izin->reason;
        }else{
            $output['status'] = false;
        }
        return $output;
    }
    //cek lembur
    public function check_lembur($employee_id,$attendance_date){
        $sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='" . $employee_id . "' AND overtime_date = '" . $attendance_date . "' ";
        
        $row_check_lembur = $this->db->query($sql_check_lembur)->row();
        if (!empty($row_check_lembur)) {
            $output['status'] = true;
            $output['attendance_status']    =$this->lang->line('xin_overtime');
            $output['attendance_simbol']    = $this->lang->line('xin_overtime_simbol');
            $output['attendance_keterangan']   = "Lembur : " . $row_check_lembur->description;
        }else{
            $output['status'] = false;
        }
        return $output;
    }
    // cek terlambat
    public function check_terlambat($attendance_date,$office_in,$clock_in){
        $office_time_new   = strtotime($office_in . ' ' . $attendance_date);
        $clock_in_time_new = strtotime($clock_in);
        $clock_in = new DateTime($clock_in);
       
        $office_in = new DateTime($office_in . ' ' . $attendance_date);
        if ($clock_in_time_new <= $office_time_new) {
            $time_late = '0';
        } else if ($clock_in_time_new > $office_time_new) {
            $interval_late = $clock_in->diff($office_in);
            $hours_l   = $interval_late->format('%h');
            $minutes_l = $interval_late->format('%i');
            $time_late = $hours_l * 60 + $minutes_l;
        } else {
            $time_late = '0';
        }
        return $time_late;
    }
    //check pulang cepat
    public function check_pulang_cepat($attendance_date,$office_out,$clock_out){
        $office_time_new   = strtotime($office_out . ' ' . $attendance_date);
        $clock_out_time_new = strtotime($clock_out);

        $clock_out = new DateTime($clock_out);
       
        $office_out = new DateTime($office_out . ' ' . $attendance_date);

        if ($clock_out_time_new <= $office_time_new) {
            $early_leaving = '0';
        } else if ($clock_out_time_new < $office_time_new) {
            $interval_early = $clock_out->diff($office_out);
            $hours_e   = $interval_early->format('%h');
            $minutes_e = $interval_early->format('%i');
            $early_leaving = $hours_e * 60 + $minutes_e;
        } else {
            $early_leaving = '0';
        }
        return $early_leaving;
    }
    //check over time absen
    public function check_over_time_checklock($attendance_date,$office_out,$clock_out){
        $office_time_new   = strtotime($office_out . ' ' . $attendance_date);
        $clock_out_time_new = strtotime($clock_out);
        $clock_out = new DateTime($clock_out);
       
        $office_out = new DateTime($office_out . ' ' . $attendance_date);
        if ($clock_out_time_new <= $office_time_new) {
            $overtime = '0';
        } if ($clock_out_time_new > $office_time_new) {
            $interval_over = $clock_out->diff($office_out);
            $hours_e   = $interval_over->format('%h');
            $minutes_e = $interval_over->format('%i');
            $overtime= $hours_e * 60 + $minutes_e;
        } else {
            $overtime = '0';
        }
        return $overtime;
    }
              
}
