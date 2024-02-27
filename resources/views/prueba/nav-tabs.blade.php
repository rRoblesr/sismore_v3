<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sistema | Control de Acceso </title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- <link rel="icon" type="image/png" href="https://creditos.epicscode.com/public/src/img/favicon.png"> --}}
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">

    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet"> --}}

    <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/bootstrap/dist/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/fontawesome-free/css/all.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/icon-kit/dist/css/iconkit.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/ionicons/dist/css/ionicons.min.css"> --}}
    {{-- <link rel="stylesheet"
        href="https://creditos.epicscode.com/public/plugins/perfect-scrollbar/css/perfect-scrollbar.css"> --}}

    {{-- <link rel="stylesheet"
        href="https://creditos.epicscode.com/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.css"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.all.min.js"></script> --}}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css"> -->
    {{-- <link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css"
        rel="stylesheet"> --}}

    <link rel="stylesheet" href="https://creditos.epicscode.com/public/dist/css/theme.min.css">

    <style>
        body,
        html {
            height: 100%;
        }

        /* when not active use specificity to override the !important on border-(color) */
        .nav-tabs .nav-link:not(.active) {
            border-color: transparent !important;
        }
    </style>
</head>

<body>
    <div class="container h-100 py-2">
        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active border border-primary border-bottom-0" id="home-tab" data-toggle="tab"
                    href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border border-warning border-bottom-0" id="profile-tab" data-toggle="tab"
                    href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border border-danger border-bottom-0" id="messages-tab" data-toggle="tab"
                    href="#messages" role="tab" aria-controls="messages" aria-selected="false">Messages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border border-info border-bottom-0" id="settings-tab" data-toggle="tab"
                    href="#settings" role="tab" aria-controls="settings" aria-selected="false">Settings</a>
            </li>
        </ul>

        <div class="tab-content h-75">
            <div class="tab-pane h-100 p-3 active border border-primary" id="home" role="tabpanel"
                aria-labelledby="home-tab">Home tab content...</div>
            <div class="tab-pane h-100 p-3 border border-warning" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">Profile tab content..</div>
            <div class="tab-pane h-100 p-3 border border-danger" id="messages" role="tabpanel"
                aria-labelledby="messages-tab">Message tab content...</div>
            <div class="tab-pane h-100 p-3 border border-info" id="settings" role="tabpanel"
                aria-labelledby="settings-tab">Settings tab content...</div>
        </div>

    </div>

    {{-- <script type="text/javascript">
        base_url = 'https://creditos.epicscode.com/'
    </script> --}}
    <script src="https://creditos.epicscode.com/public/src/js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="https://creditos.epicscode.com/public/src/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="https://creditos.epicscode.com/public/plugins/popper.js/dist/umd/popper.min.js"></script>
    <script src="https://creditos.epicscode.com/public/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://creditos.epicscode.com/public/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="https://creditos.epicscode.com/public/plugins/screenfull/dist/screenfull.js"></script>
    <script src="https://creditos.epicscode.com/public/plugins/moment/moment.min.js"></script>
    <script
        src="https://creditos.epicscode.com/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js">
    </script>

    {{-- <script src="https://creditos.epicscode.com/public/dist/js/theme.min.js"></script> --}}

    <!-- <link rel="stylesheet" href="https://creditos.epicscode.com/public/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css"> -->
    <!-- <script src="https://creditos.epicscode.com/public/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js">
    </script> -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/4.2.0/jquery.bootstrap-touchspin.min.js"
        integrity="sha512-VzUh7hLMvCqgvfBmkd2OINf5/pHDbWGqxS+RFaL/fsgA+rT94LxTFnjlFkm0oKM5BXWbc9EjBQAuARqzGKLbcA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    {{-- <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/4.2.0/jquery.bootstrap-touchspin.css"
        integrity="sha512-M+RT/z+GO2INvbXyfkn7l5qN+g09mr0+JQ++nxLUfqAufrp/v5GIQ1k4IMn0BIHgxZK2Ss+YA+kHK4wJUKJK0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <script>
        /*  $("#logotipo").change(function() {
                    readImage(this);
                });

                function readImage(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgPreview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                } */
    </script>

</body>

</html>
