<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8" />
        <title>SISMORE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @yield('css')  
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">

        <!-- Plugins css-->
        <link href="{{ asset('/') }}public/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="{{ asset('/') }}public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="{{ asset('/') }}public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/') }}public/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        {{-- {{assets('/')}} --}}
    </head>

    <body>                 
        
        @yield('content')

      
        @yield('js')
    </body>

</html>