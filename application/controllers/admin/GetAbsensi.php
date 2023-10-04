<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Location_model $Location_model
 * @property Timesheet_model $Timesheet_model
 * @property Employees_model $Employees_model
 * @property Designation_model $Designation_model
 */
class GetAbsensi extends MY_Controller
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

    function getAttendanceRegular() {
        // echo date('H:i:s');
        // $cek_hadir = $this->Timesheet_model->hitung_jumlah_status_kehadiran('733', '2023-09-16',  '2023-09-30', 'H');
        // var_dump($cek_hadir);
        // return;
        $companies = $this->db->get("xin_companies")->result();
        $jenis_gaji = $this->db->get("xin_payroll_jenis")->result();

        // var_dump($this->db->affected_rows());return;
        foreach ($companies as $key => $value) {
            $company_id = $value->company_id;
            // $company_id = 2;
            foreach ($jenis_gaji as $key => $gaji) {
                $gaji_id = $gaji->jenis_gaji_id;
                // $gaji_id = 2;
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-d');
                $lenghDay = (int) date('d');
                $attendance_date = date('Y-m-01');
                for ($i=1; $i < $lenghDay; $i++) { 
                    $getAttendance = $this->attendance_reguler_list($gaji_id,$company_id,$attendance_date);
                    // var_dump($getAttendance);
                    // return;
                    $attendance_date = new DateTime($attendance_date);
                    $attendance_date->modify('+1 day');
                    $attendance_date = $attendance_date->format('Y-m-d');
                }
            }
        }
        echo $i.PHP_EOL;
        echo '----';
        echo 'Sukses  -- ';
        echo date('H:i:s');
    }

    // index > timesheet

    // =============================================================================
    // 0910 TARIK ABSENSI REGULER
    // =============================================================================



    // daily attendance list > timesheet
   
    public function attendance_reguler_list($jenis_gaji,$company_id,$attendance_date)
    {
        // $attendance_date = $this->input->get("attendance_date");

        $employee = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji, $company_id);
        // var_dump($employee);return;
        $system   = $this->Core_model->read_setting_info(1);
        $sql1 = "DELETE FROM xin_attendance_time WHERE 1=1
        AND company_id ='" . $company_id . "' AND  attendance_date = '" . $attendance_date . "' AND  jenis_gaji = '" . $jenis_gaji . "'  ";

        $query1   = $this->db->query($sql1);

        $data = array();

        $no = 1;
        $dataInsert = [];
        
        foreach ($employee->result() as $r) {   
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
            // get office time
            $office_time = $this->get_office_time_in_out($attendance_date,$r->office_shift_id,'REGULAR');
            $in_time = $office_time['in_time'];
            $out_time = $office_time['out_time'];
            $attendance_jadwal = $in_time . ' s/d ' . $out_time;
            // var_dump($in_time);return;
            // return 123123;
            
            // return;
            // =========================================================================================================
            // CEK HARI LIBUR
            // =========================================================================================================
            $check_hari_libur = $this->check_hari_libur($r->company_id,$attendance_date);
            $attendance_in = $this->Timesheet_model->attendance_first_in_new($r->employee_pin, $attendance_date);
            if ($r->date_of_joining > $attendance_date) {
                $attendance_jadwal = 'Belum Masuk';
                $attendance_status = 'Belum Masuk';
                $attendance_simbol = 'BM';
                $attendance_keterangan = 'Belum Mulai Kerja';
            
            }else if ($in_time == '00:00:00' && empty($attendance_in)) {
                $attendance_status                = $this->lang->line('xin_holiday');
                $attendance_simbol         = $this->lang->line('xin_libur_simbol');
                $attendance_keterangan = $this->lang->line('xin_holiday');
                $flag = 'L';
            }else if ($r->flag == 1) {
                $attendance_status                = 'Free';
                $attendance_simbol         = 'F';
                $attendance_keterangan = 'Bebas Tanpa Absensi Mesin Finger';
            }else if ($check_hari_libur['status'] == true && empty($attendance_in)) {
                $attendance_status = $check_hari_libur['attendance_status'] == false ? 'Libur' :$check_hari_libur['attendance_status'];
                $attendance_simbol = $check_hari_libur['attendance_simbol'];
                $attendance_keterangan = $check_hari_libur['attendance_keterangan'];
                $flag = 'L';
                $attendance_jadwal = 'Libur';
                // var_dump($check_hari_libur);return;
              
            }else{
                // cek apakah karyawan sudah mulai bekerja
                if ($r->date_of_joining > $attendance_date) {
                    $attendance_jadwal = 'Belum Masuk';
                    $attendance_status = 'Belum Masuk';
                    $attendance_simbol = 'BM';
                    $attendance_keterangan = 'Belum Mulai Kerja';
                } else {
                    $get_day = strtotime($attendance_date);
                    $day = date('l', $get_day);
                  
                    $attendance_jadwal = $in_time . ' s/d ' . $out_time;

                    //cek libur kantor
                    $check_hari_libur_kantor = $this->check_hari_libur_kantor($r->user_id,$attendance_date);
                    if ($check_hari_libur['status']== true && empty($attendance_in)) {
                        # code...
                        $attendance_status = $check_hari_libur_kantor['attendance_status'] == false ? 'Libur' :$check_hari_libur_kantor['attendance_status'];
                        $attendance_simbol = $check_hari_libur_kantor['attendance_simbol'] == false ? 'L' :$check_hari_libur_kantor['attendance_simbol'];
                        $attendance_keterangan = $check_hari_libur_kantor['attendance_keterangan'];
                        $flag = 'L';
                        // var_dump($check_hari_libur_kantor);return;
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
                                        $clock_in =$attendance_in[0]->clock_in;
                                        
                                        if (!empty($attendance_out)) {
                                            $clock_out = $attendance_out[0]->clock_out;
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
            // break;
            // }
        }
        
        $this->db->insert_batch('xin_attendance_time', $dataInsert);

        return ($this->db->affected_rows() != 0) ? true : false;
    }

    // =============================================================================
    // 0920 TARIK ABSENSI SHIFT
    // =============================================================================

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
                // $attendance_status = $check_hari_libur['attendance_status'];
                $attendance_status = $check_hari_libur['attendance_status'] == false ? 'Libur' :$check_hari_libur['attendance_status'];
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
                    $tanggal = 'T' . date('d', $get_day);
                    $office_time = $this->get_office_time_in_out($attendance_date,$r->office_shift_id,'SHIFT');
                    $in_time = $office_time['in_time'];
                    $out_time = $office_time['out_time'];
                    $attendance_jadwal = $in_time . ' s/d ' . $out_time;

                    //cek libur kantor
                    $check_hari_libur_kantor = $this->check_hari_libur_kantor($r->user_id,$attendance_date);
                    if ($check_hari_libur['status']== true) {
                        # code...
                        // $attendance_status = $check_hari_libur_kantor['attendance_status'];
                        $attendance_status = $check_hari_libur_kantor['attendance_status'] == false ? 'Libur' :$check_hari_libur_kantor['attendance_status'];
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
            // $data[] = array(
            //     $no,
            //     strtoupper($full_name),
            //     substr(strtoupper($designation_name), 0, 30),
            //     $comp_name,
            //     $jd,
            //     $d_date,
            //     $status,
            //     $jam_masuk,
            //     $jam_pulang,
            //     $total_time_l,
            //     $total_time_e,
            //     $overtime2,
            //     $total_work
            // );
            $data[] = array(
                $no,
                strtoupper($full_name),
                // date("d-m-Y", strtotime($r->date_of_joining)),
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

    //  #### EXTEND FUNCTION 
    function get_office_time_in_out($attendance_date,$shift_id,$jenis = 'REGULAR'){
        $in_time = '00:00:00';
        $out_time = '00:00:00';
        $attendance_jadwal = $in_time . ' s/d ' . $out_time;
        $get_day = strtotime($attendance_date);
        $day = date('l', $get_day);
        if ($jenis == 'REGULAR') {
            # code...
            $office_time = $this->Timesheet_model->read_office_jadwal_information_reguler($shift_id);
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
                        $in_time =  '00:00:00';
                        $out_time = '00:00:00';
                        break;
                    default:
                        $in_time = '00:00:00';
                        $out_time = '00:00:00';
                        break;
                }
                $attendance_jadwal = $in_time . ' s/d ' . $out_time;
            }
        }else{
            $office_time_shift = $this->Timesheet_model->read_office_jadwal_information_shift($shift_id);
            $tanggal = 'T' . date('d', $get_day);
            if (!empty('office_time_shift')) {
                # code...
                $jam_shift = $this->Timesheet_model->read_office_jadwal_jam_shift($office_time_shift[0]->$tanggal);
                if (!is_null($jam_shift)) {
                    $in_time =  $jam_shift[0]->start_date;
                    $out_time = $jam_shift[0]->end_date;
                    $attendance_jadwal = $office_time_shift[0]->$tanggal .' - '. $in_time . ' s/d ' . $out_time;
                }
            }
        }

       
       
       $output['in_time'] = $in_time;
       $output['out_time'] = $out_time;
       $output['attendance_jadwal'] = $attendance_jadwal;
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
