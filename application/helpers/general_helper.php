<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function initialize_elfinder($value=''){
    $CI =& get_instance();
    $CI->load->helper('path');
    $opts = array(
        //'debug' => true,
        'roots' => array(
          array(
            'driver' => 'LocalFileSystem',
            'path'   => './uploads/files_manager/',
            'URL'    => site_url('uploads/files_manager').'/'
            // more elFinder options here
          )
        )
    );
    return $opts;
}
if ( ! function_exists('get_employee_leave_category'))
{
    function get_employee_leave_category() {
        $CI =&	get_instance();
        $sql = "select * from xin_leave_type ";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_employee_sick_category'))
{
    function get_employee_sick_category() {
        $CI =&	get_instance();
        $sql = "select * from xin_sick_type ";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_employee_izin_category'))
{
    function get_employee_izin_category() {
        $CI =&	get_instance();
        $sql = "select * from xin_izin_type ";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_employee_libur_category'))
{
    function get_employee_libur_category() {
        $CI =&	get_instance();
        $sql = "select * from xin_libur_type ";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_sub_departments'))
{
    function get_sub_departments($id) {
        $CI =&	get_instance();
        $sql = "select * from xin_sub_departments where department_id = $id";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_main_departments_employees'))
{
    function get_main_departments_employees() {
        $CI =&	get_instance();
        $sql = "select d.*,e.* from xin_departments as d, xin_employees as e where d.department_id = e.department_id";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_sub_departments_employees'))
{
    function get_sub_departments_employees($id,$empid) {
        $CI =&	get_instance();
        $sql = "select d.*,e.* from xin_sub_departments as d, xin_employees as e where d.sub_department_id = e.sub_department_id and e.department_id = '".$id."' and e.employee_id!= '".$empid."' group by e.sub_department_id";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_sub_departments_designations'))
{
    function get_sub_departments_designations($id,$empid,$mainid) {
        $CI =&	get_instance();
        $sql = "select d.*,e.* from xin_designations as d, xin_employees as e where d.designation_id = e.designation_id and e.employee_id!= '".$empid."' and e.employee_id!= '".$mainid."' and e.designation_id = '".$id."'";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_main_companies_chart'))
{
    function get_main_companies_chart() {
        $CI =&	get_instance();
        $sql = "select * from xin_companies";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_main_companies_location_chart'))
{
    function get_main_companies_location_chart($company_id) {
        $CI =&	get_instance();
        $sql = "select * from xin_office_location where company_id = '".$company_id."'";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_location_departments_head_employees'))
{
    function get_location_departments_head_employees($location_id) {
        $CI =&	get_instance();
        $sql = "select * from xin_departments where location_id = '".$location_id."'";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_main_departments_head_employees'))
{
    function get_main_departments_head_employees() {
        $CI =&	get_instance();
        $sql = "select * from xin_departments";
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('get_departments_designations'))
{
    function get_departments_designations($department_id,$employee_id) {
        $CI =&	get_instance();
        $sql = "select d.*,e.* from xin_designations as d, xin_employees as e where d.department_id= '".$department_id."' and d.designation_id = e.designation_id";
        $CI->db->group_by("d.designation_id");
        $query = $CI->db->query($sql);
        $result = $query->result();
        return $result;
    }
}
if ( ! function_exists('total_salaries_paid'))
{
    function total_salaries_paid() {
            $CI =&	get_instance();
            $CI->db->from('xin_salary_payslips');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $tinc += $inc->net_salary;
            }
            return $tinc;
        }else{
            return 0;
        }
    }

}
if ( ! function_exists('total_invoices_paid'))
{
    function total_invoices_paid() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','income');
            $CI->db->where('dr_cr','cr');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $tinc += $inc->amount;
            }
            return $tinc;
        }else{
            return 0;
        }
    }

}
if ( ! function_exists('count_leaves_info'))
{
    function count_leaves_info($tahun,$leave_type_id,$employee_id) {
            $CI =&	get_instance();

            // $tahun = date('Y');

            $CI->db->from('view_hris_employee_leave');
            $CI->db->where('tahun',$tahun);
            $CI->db->where('employee_id',$employee_id);
            $CI->db->where('leave_type_id',$leave_type_id);
            $CI->db->where('status!=',3);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $ifrom_date =  $inc->from_date;
                $ito_date =  $inc->to_date;
                $datetime1 = new DateTime($ifrom_date);
                $datetime2 = new DateTime($ito_date);
                $interval = $datetime1->diff($datetime2);
                if(strtotime($inc->from_date) == strtotime($inc->to_date)){
                    $tinc +=1;
                } else {
                    $tinc += $interval->format('%a') + 1;
                }

            }
            return $tinc;
        }else{
            return 0;
        }
    }

}
if ( ! function_exists('count_sicks_info'))
{
    function count_sicks_info($sick_type_id,$employee_id) {
            $CI =&	get_instance();
            $CI->db->from('xin_sick_applications');
            $CI->db->where('employee_id',$employee_id);
            $CI->db->where('sick_type_id',$sick_type_id);
            $CI->db->where('status!=',3);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $ifrom_date =  $inc->from_date;
                $ito_date =  $inc->to_date;
                $datetime1 = new DateTime($ifrom_date);
                $datetime2 = new DateTime($ito_date);
                $interval = $datetime1->diff($datetime2);
                if(strtotime($inc->from_date) == strtotime($inc->to_date)){
                    $tinc +=1;
                } else {
                    $tinc += $interval->format('%a') + 1;
                }

            }
            return $tinc;
        }else{
            return 0;
        }
    }

}

if ( ! function_exists('count_izins_info'))
{
    function count_izins_info($izin_type_id,$employee_id) {
            $CI =&	get_instance();
            $CI->db->from('xin_izin_applications');
            $CI->db->where('employee_id',$employee_id);
            $CI->db->where('izin_type_id',$izin_type_id);
            $CI->db->where('status!=',3);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $ifrom_date =  $inc->from_date;
                $ito_date =  $inc->to_date;
                $datetime1 = new DateTime($ifrom_date);
                $datetime2 = new DateTime($ito_date);
                $interval = $datetime1->diff($datetime2);
                if(strtotime($inc->from_date) == strtotime($inc->to_date)){
                    $tinc +=1;
                } else {
                    $tinc += $interval->format('%a') + 1;
                }

            }
            return $tinc;
        }else{
            return 0;
        }
    }

}


if ( ! function_exists('count_liburs_info'))
{
    function count_liburs_info($libur_type_id,$employee_id) {
            $CI =&	get_instance();
            $CI->db->from('xin_libur_applications');
            $CI->db->where('employee_id',$employee_id);
            $CI->db->where('libur_type_id',$libur_type_id);
            $CI->db->where('status!=',3);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $ifrom_date =  $inc->from_date;
                $ito_date =  $inc->to_date;
                $datetime1 = new DateTime($ifrom_date);
                $datetime2 = new DateTime($ito_date);
                $interval = $datetime1->diff($datetime2);
                if(strtotime($inc->from_date) == strtotime($inc->to_date)){
                    $tinc +=1;
                } else {
                    $tinc += $interval->format('%a') + 1;
                }

            }
            return $tinc;
        }else{
            return 0;
        }
    }

}

if ( ! function_exists('all_employees'))
    {
        function all_employees() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

// ========================================================================================
// AKTIF
// ========================================================================================

    if ( ! function_exists('active_employees'))
    {
        function active_employees() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_male'))
    {
        function active_employees_male() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_male_1'))
    {
        function active_employees_male_1() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('company_id',1);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_male_2'))
    {
        function active_employees_male_2() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('company_id',2);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_male_3'))
    {
        function active_employees_male_3() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('company_id',3);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female'))
    {
        function active_employees_female() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }
    if ( ! function_exists('active_employees_female_1'))
    {
        function active_employees_female_1() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('company_id',1);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female_2'))
    {
        function active_employees_female_2() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('company_id',2);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female_3'))
    {
        function active_employees_female_3() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',1);
            $CI->db->where('company_id',3);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

// ========================================================================================
// TIDAK AKTIF
// ========================================================================================

    if ( ! function_exists('inactive_employees'))
    {
        function inactive_employees() {
            $CI =&	get_instance();
            $CI->db->from('view_karyawan_resign');

            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('inactive_employees_male'))
    {
        function inactive_employees_male() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',0);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('inactive_employees_female'))
    {
        function inactive_employees_female() {
            $CI =&	get_instance();
            $CI->db->from('xin_employees');
            $CI->db->where('is_active',0);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }


// ========================================================================================
// CUTI
// ========================================================================================
    if ( ! function_exists('active_employees_male_leave'))
    {
        function active_employees_male_leave() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_leave');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Male');
            $CI->db->where('tahun_cuti', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female_leave'))
    {
        function active_employees_female_leave() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_leave');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Female');
            $CI->db->where('tahun_cuti', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

// ========================================================================================
// SICK
// ========================================================================================
    if ( ! function_exists('active_employees_male_sick'))
    {
        function active_employees_male_sick() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_sick');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Male');
            $CI->db->where('tahun_sick', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female_sick'))
    {
        function active_employees_female_sick() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_sick');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Female');
            $CI->db->where('tahun_sick', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

// ========================================================================================
// IZIN
// ========================================================================================
    if ( ! function_exists('active_employees_male_izin'))
    {
        function active_employees_male_izin() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_izin');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Male');
            $CI->db->where('tahun_izin', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female_izin'))
    {
        function active_employees_female_izin() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_izin');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Female');
            $CI->db->where('tahun_izin', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

// ========================================================================================
// LEMBUR
// ========================================================================================
    if ( ! function_exists('active_employees_male_lembur'))
    {
        function active_employees_male_lembur() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_lembur');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Male');
            $CI->db->where('tahun_lembur', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('active_employees_female_lembur'))
    {
        function active_employees_female_lembur() {
            date_default_timezone_set("Asia/Jakarta");
            $now_year  = date("Y");

            $CI =&	get_instance();
            $CI->db->from('view_hris_info_lembur');
            $CI->db->where('is_active',1);
            $CI->db->where('gender','Female');
            $CI->db->where('tahun_lembur', $now_year);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }



// ========================================================================================
// PENGAJUAN
// ========================================================================================

    // ========================================================================================
    // CUTI
    // ========================================================================================

        if ( ! function_exists('jum_cuti_bulan_ini'))
        {
            function jum_cuti_bulan_ini() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_cuti');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_cuti_bulan_ini_male'))
        {
            function jum_cuti_bulan_ini_male() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_cuti');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Male');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_cuti_bulan_ini_female'))
        {
            function jum_cuti_bulan_ini_female() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_cuti');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Female');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

    // ========================================================================================
    // SAKIT
    // ========================================================================================

        if ( ! function_exists('jum_sakit_bulan_ini'))
        {
            function jum_sakit_bulan_ini() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_sakit');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_sakit_bulan_ini_male'))
        {
            function jum_sakit_bulan_ini_male() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_sakit');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Male');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_sakit_bulan_ini_female'))
        {
            function jum_sakit_bulan_ini_female() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_sakit');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Female');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

    // ========================================================================================
    // IZIN
    // ========================================================================================

        if ( ! function_exists('jum_izin_bulan_ini'))
        {
            function jum_izin_bulan_ini() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_izin');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_izin_bulan_ini_male'))
        {
            function jum_izin_bulan_ini_male() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_izin');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Male');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_izin_bulan_ini_female'))
        {
            function jum_izin_bulan_ini_female() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_izin');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Female');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

    // ========================================================================================
    // LEMBUR
    // ========================================================================================

        if ( ! function_exists('jum_lembur_bulan_ini'))
        {
            function jum_lembur_bulan_ini() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_lembur');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_lembur_bulan_ini_male'))
        {
            function jum_lembur_bulan_ini_male() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_lembur');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Male');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_lembur_bulan_ini_female'))
        {
            function jum_lembur_bulan_ini_female() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_lembur');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Female');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

    // ========================================================================================
    // RESIGN
    // ========================================================================================

        if ( ! function_exists('jum_resign_bulan_ini'))
        {
            function jum_resign_bulan_ini() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_resign');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_resign_bulan_ini_male'))
        {
            function jum_resign_bulan_ini_male() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_resign');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Male');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_resign_bulan_ini_female'))
        {
            function jum_resign_bulan_ini_female() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_resign');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Female');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

    // ========================================================================================
    // BARU
    // ========================================================================================

        if ( ! function_exists('jum_baru_bulan_ini'))
        {
            function jum_baru_bulan_ini() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_baru');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_baru_bulan_ini_male'))
        {
            function jum_baru_bulan_ini_male() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_baru');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Male');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }

        if ( ! function_exists('jum_baru_bulan_ini_female'))
        {
            function jum_baru_bulan_ini_female() {
                date_default_timezone_set("Asia/Jakarta");
                $tahun  = date("Y");
                $bulan  = date("m");

                $CI =&	get_instance();
                $CI->db->from('view_hris_jumlah_baru');
                $CI->db->where('tahun', $tahun);
                $CI->db->where('bulan', $bulan);
                $CI->db->where('gender', 'Female');
                $query=$CI->db->get();
                if ($query->num_rows() > 0) {
                    return $query->num_rows();
                }else{
                    return 0;
                }
            }
        }


// ========================================================================================
// KONTRAK
// ========================================================================================

    if ( ! function_exists('jum_status_kontrak'))
    {
        function jum_status_kontrak() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_karyawan_aktif_kontrak');

            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_belum_aktivasi'))
    {
        function jum_status_kontrak_belum_aktivasi() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_belum_aktivasi');

            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_belum_ada'))
    {
        function jum_status_kontrak_belum_ada() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_hris_karyawan_status_kontrak_belum_dibuat');

            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_sudah'))
    {
        function jum_status_kontrak_sudah() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_karyawan_aktif_kontrak_sudah');

            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }




    // ========================================================================================
    // BERLANGSUNG
    // ========================================================================================

    if ( ! function_exists('jum_status_kontrak_berlangsung'))
    {
        function jum_status_kontrak_berlangsung() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis_belum');

            $CI->db->where('notif >', $now_date);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_berlangsung_male'))
    {
        function jum_status_kontrak_berlangsung_male() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis_belum');
            $CI->db->where('gender','Male');
            $CI->db->where('notif >', $now_date);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_berlangsung_female'))
    {
        function jum_status_kontrak_berlangsung_female() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis_belum');
            $CI->db->where('gender','Female');
            $CI->db->where('notif >', $now_date);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    // ========================================================================================
    // AKAN HABIS
    // ========================================================================================

    if ( ! function_exists('jum_status_kontrak_akan_habis'))
    {
        function jum_status_kontrak_akan_habis() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis_akan');
            $CI->db->where('notif <=', $now_date);
            $CI->db->where('kontrak_end_date >=', $now_date);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }


    if ( ! function_exists('jum_status_kontrak_akan_habis_male'))
    {
        function jum_status_kontrak_akan_habis_male() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis_akan');
            $CI->db->where('notif <=', $now_date);
            $CI->db->where('kontrak_end_date >=', $now_date);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }


    if ( ! function_exists('jum_status_kontrak_akan_habis_female'))
    {
        function jum_status_kontrak_akan_habis_female() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis_akan');
            $CI->db->where('notif <=', $now_date);
            $CI->db->where('kontrak_end_date >=', $now_date);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    // ========================================================================================
    // SUDAH HABIS
    // ========================================================================================

    if ( ! function_exists('jum_status_kontrak_sudah_habis'))
    {
        function jum_status_kontrak_sudah_habis() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis');
            $CI->db->where('kontrak_end_date <', $now_date);
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_sudah_habis_male'))
    {
        function jum_status_kontrak_sudah_habis_male() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis');
            $CI->db->where('kontrak_end_date <', $now_date);
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_kontrak_sudah_habis_female'))
    {
        function jum_status_kontrak_sudah_habis_female() {
            date_default_timezone_set("Asia/Jakarta");
            $now_date  = date("Y-m-d");

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_habis');
            $CI->db->where('kontrak_end_date <', $now_date);
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    // ========================================================================================
    // TETAP
    // ========================================================================================


    if ( ! function_exists('jum_status_tetap'))
    {
        function jum_status_tetap() {

            $CI =&	get_instance();
            $CI->db->from('view_hris_karyawan_status_tetap');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_tetap_male'))
    {
        function jum_status_tetap_male() {

            $CI =&	get_instance();
            $CI->db->from('view_hris_karyawan_status_tetap');
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_tetap_female'))
    {
        function jum_status_tetap_female() {

            $CI =&	get_instance();
            $CI->db->from('view_hris_karyawan_status_tetap');
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    // ========================================================================================
    // BELUM ADA KONTRAK
    // ========================================================================================


    if ( ! function_exists('jum_status_belum_ada_kontrak'))
    {
        function jum_status_belum_ada_kontrak() {

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_belum_ada');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_belum_ada_kontrak_male'))
    {
        function jum_status_belum_ada_kontrak_male() {

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_belum_ada');
            $CI->db->where('gender','Male');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }

    if ( ! function_exists('jum_status_belum_ada_kontrak_female'))
    {
        function jum_status_belum_ada_kontrak_female() {

            $CI =&	get_instance();
            $CI->db->from('view_report_karyawan_kontrak_belum_ada');
            $CI->db->where('gender','Female');
            $query=$CI->db->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
            }else{
                return 0;
            }
        }
    }


//after v1.0.11
if ( ! function_exists('system_settings_info'))
{
        function system_settings_info($id) {
            $CI =&	get_instance();
            $CI->db->from('xin_system_setting');
            $CI->db->where('setting_id',$id);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result;
        }else{
            return "";
        }
    }

}
if ( ! function_exists('xin_company_info'))
{
        function xin_company_info($id) {
            $CI =&	get_instance();
            $CI->db->from('xin_company_info');
            $CI->db->where('company_info_id',$id);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result;
        }else{
            return "";
        }
    }

}


if ( ! function_exists('system_currency_sign'))
{
    //set currency sign
    function system_currency_sign($number) {

        // get details
        $system_setting = system_settings_info(1);
        // currency code/symbol
        if($system_setting->show_currency=='code'){
            $ar_sc = explode(' -',$system_setting->default_currency_symbol);
            $sc_show = $ar_sc[0];
        } else {
            $ar_sc = explode('- ',$system_setting->default_currency_symbol);
            $sc_show = $ar_sc[1];
        }
        if($system_setting->currency_position=='Prefix'){
            $sign_value = $sc_show.''.$number;
        } else {
            $sign_value = $number.''.$sc_show;
        }
        return $sign_value;
    }
}

if ( ! function_exists('total_travel_expense'))
{
    function total_travel_expense() {
        $CI =&	get_instance();
        $CI->db->from('xin_employee_travels');
        $CI->db->where('status',1);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $tinc += $inc->actual_budget;
            }
            return $tinc;
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('pending_travel'))
{
    function pending_travel() {
        $CI =&	get_instance();
        $CI->db->from('xin_employee_travels');
        $CI->db->where('status',0);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('pending_leave_request'))
{
    function pending_leave_request() {
        $CI =&	get_instance();
        $CI->db->from('xin_leave_applications');
        $CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('accepted_leave_request'))
{
    function accepted_leave_request() {
        $CI =&	get_instance();
        $CI->db->from('xin_leave_applications');
        $CI->db->where('status',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('rejected_leave_request'))
{
    function rejected_leave_request() {
        $CI =&	get_instance();
        $CI->db->from('xin_leave_applications');
        $CI->db->where('status',3);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('rejected_leave_request'))
{
    function rejected_leave_request() {
        $CI =&	get_instance();
        $CI->db->from('xin_leave_applications');
        $CI->db->where('status',3);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_total_shifts'))
{
    function employee_total_shifts() {
        $CI =&	get_instance();
        $CI->db->from('xin_office_shift');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('accepted_travel'))
{
    function accepted_travel() {
        $CI =&	get_instance();
        $CI->db->from('xin_employee_travels');
        $CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('rejected_travel'))
{
    function rejected_travel() {
        $CI =&	get_instance();
        $CI->db->from('xin_employee_travels');
        $CI->db->where('status',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_training'))
{
    function total_training() {
        $CI =&	get_instance();
        $CI->db->from('xin_training');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_pending_training'))
{
    function total_pending_training() {
        $CI =&	get_instance();
        $CI->db->from('xin_training');
        $CI->db->where('training_status',0);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_started_training'))
{
    function total_started_training() {
        $CI =&	get_instance();
        $CI->db->from('xin_training');
        $CI->db->where('training_status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_completed_training'))
{
    function total_completed_training() {
        $CI =&	get_instance();
        $CI->db->from('xin_training');
        $CI->db->where('training_status',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_assets'))
{
    function total_assets() {
        $CI =&	get_instance();
        $CI->db->from('xin_assets');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_projects'))
{
    function total_projects() {
        $CI =&	get_instance();
        $CI->db->from('xin_projects');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_last_projects'))
{
    function total_last_projects() {
        $CI =&	get_instance();
        $CI->db->from('xin_projects');
        $CI->db->order_by("project_id", "desc");
        $CI->db->limit(3);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_tasks'))
{
    function total_last_tasks() {
        $CI =&	get_instance();
        $CI->db->from('xin_tasks');
        $CI->db->order_by("task_id", "desc");
        $CI->db->limit(3);
        $query=$CI->db->get();
        return $query->result();
    }
}

if ( ! function_exists('total_last_leaves'))
{
    function total_last_leaves() {
        $CI =&	get_instance();
        $CI->db->from('view_hris_employee_leave');
        $CI->db->order_by("leave_id", "desc");
        $CI->db->limit(6);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_holidays'))
{
    function total_last_holidays() {

        $CI =&	get_instance();
        $CI->db->from('view_holidays');
        $CI->db->order_by("holiday_id", "desc");
        $CI->db->limit(3);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_overtime'))
{
    function total_last_overtime() {
        $CI =&	get_instance();
        $CI->db->from('xin_attendance_time_request');
        $CI->db->order_by("time_request_id", "desc");
        $CI->db->limit(2);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_estimates'))
{
    function total_last_estimates() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->order_by("quote_id", "desc");
        $CI->db->limit(4);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_5_invoice_payments'))
{
    function total_last_5_invoice_payments() {
        $CI =&	get_instance();
        $CI->db->from('xin_finance_transaction');
        $CI->db->order_by("transaction_id", "desc");
        $CI->db->where('invoice_id!=','');
        $CI->db->limit(5);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_clients'))
{
    function total_last_clients() {
        $CI =&	get_instance();
        $CI->db->from('xin_clients');
        $CI->db->order_by("client_id", "desc");
        $CI->db->limit(3);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_leads'))
{
    function total_last_leads() {
        $CI =&	get_instance();
        $CI->db->from('xin_leads');
        $CI->db->order_by("client_id", "desc");
        $CI->db->limit(3);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('total_last_5_qprojects'))
{
    function total_last_5_qprojects() {
        $CI =&	get_instance();
        $CI->db->from('xin_quoted_projects');
        $CI->db->order_by("project_id", "desc");
        $CI->db->limit(5);
        $query=$CI->db->get();
        return $query->result();
    }
}
if ( ! function_exists('get_projects_status'))
{
    function get_projects_status() {
        $CI =&	get_instance();
        $CI->db->from('xin_projects');
        $CI->db->group_by("status");
        $query=$CI->db->get();
        return $query;
    }
}
if ( ! function_exists('get_tasks_status'))
{
    function get_tasks_status() {
        $CI =&	get_instance();
        $CI->db->from('xin_tasks');
        $CI->db->group_by("task_status");
        $query=$CI->db->get();
        return $query;
    }
}
if ( ! function_exists('total_projects_status'))
{
    function total_projects_status($status) {
        $CI =&	get_instance();
        $CI->db->from('xin_projects');
        $CI->db->where('status',$status);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_tasks_status'))
{
    function total_tasks_status($status) {
        $CI =&	get_instance();
        $CI->db->from('xin_tasks');
        $CI->db->where('task_status',$status);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_tasks'))
{
    function total_tasks() {
        $CI =&	get_instance();
        $CI->db->from('xin_tasks');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_completed_tasks'))
{
    function total_completed_tasks() {
        $CI =&	get_instance();
        $CI->db->from('xin_tasks');
        $CI->db->where('task_status',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_inprogress_tasks'))
{
    function total_inprogress_tasks() {
        $CI =&	get_instance();
        $CI->db->from('xin_tasks');
        $CI->db->where('task_status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_cancelled_projects'))
{
    function total_cancelled_projects() {
        $CI =&	get_instance();
        $CI->db->from('xin_projects');
        $CI->db->where('status',3);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_completed_projects'))
{
    function total_completed_projects() {
        $CI =&	get_instance();
        $CI->db->from('xin_projects');
        $CI->db->where('status',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_clients'))
{
    function total_clients() {
        $CI =&	get_instance();
        $CI->db->from('xin_clients');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_leads'))
{
    function total_leads() {
        $CI =&	get_instance();
        $CI->db->from('xin_leads');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_leads_converted'))
{
    function total_leads_converted() {
        $CI =&	get_instance();
        $CI->db->from('xin_leads');
        $CI->db->where('is_changed',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_invoices'))
{
    function total_invoices() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_invoices');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_paid_invoices'))
{
    function total_paid_invoices() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_invoices');
        $CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_unpaid_invoices'))
{
    function total_unpaid_invoices() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_invoices');
        $CI->db->where('status',0);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_estimate'))
{
    function total_estimate() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_estimate_converted'))
{
    function total_estimate_converted() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_quoted_projects'))
{
    function total_quoted_projects() {
        $CI =&	get_instance();
        $CI->db->from('xin_quoted_projects');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_assets_working'))
{
    function total_assets_working() {
        $CI =&	get_instance();
        $CI->db->from('xin_assets');
        $CI->db->where('is_working',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('total_assets_not_working'))
{
    function total_assets_not_working() {
        $CI =&	get_instance();
        $CI->db->from('xin_assets');
        $CI->db->where('is_working',0);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('cr_quote_quoted'))
{
    function cr_quote_quoted() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',0);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('cr_quote_project_created'))
{
    function cr_quote_project_created() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',1);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('cr_quote_inprogress'))
{
    function cr_quote_inprogress() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',2);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('cr_quote_project_completed'))
{
    function cr_quote_project_completed() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',3);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('cr_quote_invoiced'))
{
    function cr_quote_invoiced() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',4);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('cr_quote_paid'))
{
    function cr_quote_paid() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',5);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('cr_quote_deffered'))
{
    function cr_quote_deffered() {
        $CI =&	get_instance();
        $CI->db->from('xin_hris_quotes');
        $CI->db->where('status',6);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('employee_leave_halfday_cal'))
{
    function employee_leave_halfday_cal($tahun,$leave_type_id,$employee_id) {

        // $tahun = date('Y');

        $CI =&	get_instance();
        $CI->db->from('view_hris_employee_leave');
        $CI->db->where('tahun',$tahun);
        $CI->db->where('employee_id',$employee_id);
        $CI->db->where('leave_type_id',$leave_type_id);
        $CI->db->where('is_half_day',1);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return $query->result();
        }
    }
}
if ( ! function_exists('employee_sick_halfday_cal'))
{
    function employee_sick_halfday_cal($sick_type_id,$employee_id) {
        $CI =&	get_instance();
        $CI->db->from('xin_sick_applications');
        $CI->db->where('employee_id',$employee_id);
        $CI->db->where('sick_type_id',$sick_type_id);
        $CI->db->where('is_half_day',1);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return $query->result();
        }
    }
}
if ( ! function_exists('employee_izin_halfday_cal'))
{
    function employee_izin_halfday_cal($izin_type_id,$employee_id) {
        $CI =&	get_instance();
        $CI->db->from('xin_izin_applications');
        $CI->db->where('employee_id',$employee_id);
        $CI->db->where('izin_type_id',$izin_type_id);
        $CI->db->where('is_half_day',1);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return $query->result();
        }
    }
}
if ( ! function_exists('employee_request_leaves'))
{
    function employee_request_leaves() {
        $CI =&	get_instance();
        $CI->db->from('xin_leave_applications');
        //$CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_holidays'))
{
    function employee_holidays() {
        $CI =&	get_instance();
        $CI->db->from('xin_holidays');
        //$CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_published_holidays'))
{
    function employee_published_holidays() {
        $CI =&	get_instance();
        $CI->db->from('xin_holidays');
        $CI->db->where('is_publish',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_unpublished_holidays'))
{
    function employee_unpublished_holidays() {
        $CI =&	get_instance();
        $CI->db->from('xin_holidays');
        $CI->db->where('is_publish',0);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_overtime'))
{
    function employee_overtime() {
        $CI =&	get_instance();
        $CI->db->from('xin_attendance_time_request');
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_approved_overtime'))
{
    function employee_approved_overtime() {
        $CI =&	get_instance();
        $CI->db->from('xin_attendance_time_request');
        $CI->db->where('is_approved',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_pending_overtime'))
{
    function employee_pending_overtime() {
        $CI =&	get_instance();
        $CI->db->from('xin_attendance_time_request');
        $CI->db->where('is_approved',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('employee_rejected_overtime'))
{
    function employee_rejected_overtime() {
        $CI =&	get_instance();
        $CI->db->from('xin_attendance_time_request');
        $CI->db->where('is_approved',3);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('dashboard_total_sales'))
{
    function dashboard_total_sales() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','income');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $tinc = 0;
            foreach($result as $inc){
                $tinc += $inc->amount;
            }
            return $tinc;
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('dashboard_total_expense'))
{
    function dashboard_total_expense() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','expense');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $texp = 0;
            foreach($result as $exp){
                $texp += $exp->amount;
            }
            return $texp;
        }else{
            return 0;
        }
    }
}
if ( ! function_exists('dashboard_total_payees'))
{
    function dashboard_total_payees() {
        $CI =&	get_instance();
        $CI->db->from("xin_finance_payees");
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('dashboard_total_payers'))
{
    function dashboard_total_payers() {
        $CI =&	get_instance();
        $CI->db->from("xin_finance_payers");
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('dashboard_paid_invoices'))
{
    function dashboard_paid_invoices() {
        $CI =&	get_instance();
        $CI->db->from("xin_hris_invoices");
        $CI->db->where('status',1);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('dashboard_unpaid_invoices'))
{
    function dashboard_unpaid_invoices() {
        $CI =&	get_instance();
        $CI->db->from("xin_hris_invoices");
        $CI->db->where('status',0);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('dashboard_cancelled_invoices'))
{
    function dashboard_cancelled_invoices() {
        $CI =&	get_instance();
        $CI->db->from("xin_hris_invoices");
        $CI->db->where('status',2);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}
if ( ! function_exists('dashboard_last_two_invoices'))
{
    function dashboard_last_two_invoices() {
            $CI =&	get_instance();
            $CI->db->from('xin_hris_invoices');
            $CI->db->order_by('invoice_id','desc');
            $CI->db->limit(2);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('dashboard_bankcash'))
{
    function dashboard_bankcash() {
        $CI =&	get_instance();
        $CI->db->from("xin_finance_bankcash");
        $CI->db->order_by('bankcash_id','asc');
        $CI->db->limit(6);
        $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('dashboard_last_five_income'))
{
    function dashboard_last_five_income() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','income');
            $CI->db->order_by('transaction_id','desc');
            $CI->db->limit(4);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }

}
if ( ! function_exists('dashboard_last_five_expense'))
{
    function dashboard_last_five_expense() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','expense');
            $CI->db->order_by('transaction_id','desc');
            $CI->db->limit(4);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }

}
if ( ! function_exists('income_transaction_record'))
{
    function income_transaction_record() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','income');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }

}
if ( ! function_exists('awards_transaction_record'))
{
    function awards_transaction_record() {
            $CI =&	get_instance();
            $CI->db->from('xin_awards');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('travel_transaction_record'))
{
    function travel_transaction_record() {
            $CI =&	get_instance();
            $CI->db->from('xin_employee_travels');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('payroll_transaction_record'))
{
    function payroll_transaction_record() {
            $CI =&	get_instance();
            $CI->db->from('xin_salary_payslips');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('training_transaction_record'))
{
    function training_transaction_record() {
            $CI =&	get_instance();
            $CI->db->from('xin_training');
            $CI->db->where('training_status',2);
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('invoice_payments_transaction_record'))
{
    function invoice_payments_transaction_record() {
            $CI =&	get_instance();
            $CI->db->from('xin_finance_transaction');
            $CI->db->where('transaction_type','income');
            $CI->db->where('description','Invoice Payments');
            $query=$CI->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }else{
            $result = $query->result();
            return $result;
        }
    }
}
if ( ! function_exists('get_reports_to'))
{
    function get_reports_to() {
        $CI =&	get_instance();
        $CI->db->from("xin_employees");
        $CI->db->where('is_active',1);
        $query=$CI->db->get();
        return $query->result();
    }
}

if ( ! function_exists('get_view_company_id'))
{
    function get_view_company_id() {
        $CI =&	get_instance();
        $CI->db->from("xin_company_role");
        // $CI->db->where('user_role_id!=',1);
        $query=$CI->db->get();
        return $query->result();
    }
}

if ( ! function_exists('get_reports_team_data'))
{
    function get_reports_team_data($reports_to) {
        $CI =&	get_instance();
        $CI->db->from("xin_employees");
        $CI->db->where('reports_to',$reports_to);
        $query=$CI->db->get();
        return $query->num_rows();
    }
}

if ( ! function_exists('total_last_overtime_request'))
{
    function total_last_overtime_request() {
        $CI =&	get_instance();
        $CI->db->from('xin_salary_overtime');
        $CI->db->order_by("salary_overtime_id", "desc");
        $CI->db->limit(2);
        $query=$CI->db->get();
        return $query->result();
    }
}

if ( ! function_exists('json_response'))
{
    function json_response($data, $status_code = 200) {
        $ci =& get_instance();
        $data = is_array($data) ? $data : [$data];
        $ci->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode($data));
    }
}

define('ENCRYPTION_KEY', '4736d52f85bdb63e46bf7d6d41bbd551af36e1bfb7c68164bf81e2400d291319');

if (!function_exists('simple_encrypt')) {
    function simple_encrypt($string) {
        return base64_encode(openssl_encrypt($string, 'AES-256-CBC', ENCRYPTION_KEY, 0, str_pad(substr(ENCRYPTION_KEY, 0, 16), 16, '0', STR_PAD_LEFT)));
    }
}

if (!function_exists('simple_decrypt')) {
    function simple_decrypt($string) {
        return openssl_decrypt(base64_decode($string), 'AES-256-CBC', ENCRYPTION_KEY, 0, str_pad(substr(ENCRYPTION_KEY, 0, 16), 16, '0', STR_PAD_LEFT));
    }
}

if (!function_exists('get_values')) {
    function get_values($array, $index, $value = null) {
        $data = [];

        foreach ($array as $a) {
            if ($index !== 0) {
                $data[$a->$index] = !is_null($value) ? $a->$value : $a;
            } else {
                $data[] = !is_null($value) ? $a->$value : $a;
            }
        }

        return $data;
    }
}

if (!function_exists('is_post')) {
    function is_post() {
        $ci =& get_instance();
        return $ci->input->method() == 'post';
    }
}

if (!function_exists('is_get')) {
    function is_get() {
        $ci =& get_instance();
        return $ci->input->method() == 'get';
    }
}

if (!function_exists('is_ajax')) {
    function is_ajax() {
        $ci =& get_instance();
        return $ci->input->is_ajax_request();
    }
}
