

 <!-- Topbar Start -->
 <div class="navbar-custom">

     <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="dropdown notification-list">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle nav-link">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>


         {{-- <li class="dropdown notification-list d-none d-md-inline-block">
             <a href="#" id="btn-fullscreen" class="nav-link waves-effect waves-light">
                 <i class="mdi mdi-crop-free noti-icon"></i>
             </a>
         </li> --}}
         <li class="dropdown notification-list">
             <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                 href="#" role="button" aria-haspopup="false" aria-expanded="false">
                 <img src="{{ asset('/') }}public/assets/images/users/avatar-1.jpg" alt="user-image"
                     class="rounded-circle">
                 {{-- {{ Auth::user()->nombre }} --}}
             </a>
             <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                 <!-- item-->
                 {{-- <div class="dropdown-header noti-title">
                     <h6 class="text-overflow m-0">Bienvenido</h6>
                 </div> --}}

                 <!-- item-->
                 <a href="#" class="dropdown-item notify-item"
                     onclick="editPerfilUsuario('{{ Auth::user()->id }}')">
                     <i class="mdi mdi-face-profile"></i>
                     <span>Perfil</span>
                 </a>
                 @if (session('total_sistema') > 1)
                     <a href="javascript:void(0);" class="dropdown-item notify-item d-block d-md-none right-bar-toggle">
                         <i class="mdi mdi-view-grid"></i>
                         <span>Modulos</span>
                     </a>
                 @endif

                 <div class="dropdown-divider"></div>
                 <!-- item-->
                 @if (session()->get('total_sistema') > 1)
                     <a href="{{ route('home') }}" class="dropdown-item notify-item">
                         <i class="mdi mdi-settings-outline noti-icon"></i>
                         <span>Cambiar Sistemas</span>
                     </a>
                 @endif

                 <!-- item-->
                 <a href="{{ route('logout') }}" class="dropdown-item notify-item"
                     onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                     <i class="mdi mdi-power-settings"></i>
                     <span>Cerrar Sesión</span>
                 </a>

                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                     @csrf
                 </form>

             </div>
         </li>

         {{-- <li class="dropdown notification-list">
            <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                <i class="mdi mdi-settings-outline noti-icon"></i>
            </a>
        </li> --}}

     </ul>


     <!-- LOGO -->
     <div class="logo-box">
         <a href="{{ session()->has('sistema_nombre') ? route('sistema_acceder', session('sistema_nombre')) : route('home') }}" class="logo text-center logo-dark">
             <span class="logo-lg">
                 <!-- <img src="{{ asset('/') }}public/assets/images/logo-GRU-a1.png" alt="" height="16"> -->
                 <span class="logo-lg-text-dark">SISMORE</span>
             </span>
             <span class="logo-sm">
                 <span class="logo-lg-text-dark">M</span>
                 <!-- <img src="{{ asset('/') }}public/assets/images/logo-GRU-a1.png" alt="" height="25"> -->
             </span>
         </a>
         <a href="{{ session()->has('sistema_nombre') ? route('sistema_acceder', session('sistema_nombre')) : route('home') }}" class="logo text-center logo-light">

             <span class="logo-lg">
                 {{-- <img src="{{ asset('/') }}public/assets/images/logo-sm-blanco.png" alt="" height="68"> --}}
                 <!-- inicial -->
                 <span class="logo-lg-text-light font-30">SISMORE</span>
             </span>
             <span class="logo-sm">
                 <!-- <span class="logo-lg-text-dark">M</span> -->
                 <img src="{{ asset('/') }}public/assets/images/logo-sm-blanco.png" alt="" height="28">
             </span>
         </a>

     </div>


     {{-- <div class="logo-box"> --}}
     {{-- <h3 style="color:white"><strong>S I S M O R E</strong> </h3>
        <h5 style="color:white">SISTEMA DE MONITOREO REGIONAL</h5> --}}

     {{-- <strong style="color:white; font-size: xx-large;">S I S M O R E</strong>
        <br>
        <strong style="color:white ;">SISTEMA DE MONITOREO REGIONAL</strong>
    </div>






     <!-- <div>
        <br><h3>SISTEMA DE MONITOREO REGIONAL </h3>
    </div> -->

     <!-- LOGO -->
 </div>
 <!-- end Topbar -->


