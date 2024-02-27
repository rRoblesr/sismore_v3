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
        .nav-tabs .nav-item .nav-link {
            background-color: #0080FF;
            color: #FFF;
        }

        .nav-tabs .nav-item .nav-link.active {
            color: #0080FF;
        }

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: transparent;
            padding: 15px;
        }

        .tab-content .tab-pane {
            background-color: #FFF;
            color: #0080FF;
            min-height: 200px;
            height: auto;
        }
    </style>
</head>

<body>



    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" /> --}}

    <div class="col-md-11">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active " id="home-tab" data-toggle="tab" href="#home" role="tab"
                    aria-controls="home" aria-selected="true">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">Contact</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">The
                standard Lorem Ipsum passage, used since the 1500s "Lorem ipsum dolor sit amet, consectetur adipiscing
                elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa
                qui officia deserunt mollit anim id est
                laborum." Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC "Sed ut
                perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem
                aperiam, eaque ipsa quae ab illo inventore veritatis
                et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
                aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi
                nesciunt. Neque porro quisquam est, qui dolorem
                ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt
                ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum
                exercitationem ullam corporis suscipit laboriosam,
                nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate
                velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla
                pariatur?"</div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
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
