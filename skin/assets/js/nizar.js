    var $base_url = $("body").data("base_url");

    
	
    // ================================================================================================================
    //  ALERT FUNCTION : SUKSES 
    // ================================================================================================================

        // 1. Sukses : Simpan -
        function alert_success($jenis,$pesan) {
            swal({
                title : $jenis,
                text  : $pesan,
                type  : "success",
                showConfirmButton: false,
                confirmButtonClass: "btn-raised btn-success",
                confirmButtonText: "OK",
                timer: 3500
            });
             
        }

        function alert_fail($jenis,$pesan) 
        {
            swal({
                title: $jenis,
                text: $pesan,
                type: "error",
                confirmButtonColor : '#ed6b75',
                confirmButtonClass: "btn-raised btn-danger",
                confirmButtonText: "OK",
            });
            
        }

   function doLogout() {
            var $base_url = $("body").data("base_url");
            // // responsiveVoice.speak("Apakah Anda Yakin akan Keluar dari Aplikasi ini ?", "Indonesian Female");
            swal({
              title              : "Keluar dari Aplikasi",
              text               : $base_url,
              type               : "warning",
              showCancelButton   : true,
              cancelButtonClass  : "btn-raised btn-warning",
              cancelButtonText   : "Tidak !",
              confirmButtonClass : "btn-raised btn-danger",
              confirmButtonText  : "Ya !",
              closeOnConfirm     : false,
              showLoaderOnConfirm: true
            },                 
            function() {
                // setTimeout(function () {
                    $.ajax({
                        url: $base_url+'admin/logout',
                        type: 'POST',
                        dataType: 'json',
                        success: function (JSON) {
                          if (JSON.error != '') {
                            toastr.clear();                           
                            toastr.error(JSON.error);
                           
                          } else {
                            toastr.clear();                          
                            toastr.success(JSON.result);
                          
                            if(is_redirect==0) {
                              // window.location = site_url+'admin/dashboard?module=dashboard';
                              redirect('admin/', 'refresh');
                            }
                          }
                        }
                       
                    });
                // }, 0); 
            })
        }

