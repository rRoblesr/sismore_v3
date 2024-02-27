
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="user-box">

                <div class="float-left">
                     <img src="{{ asset('/') }}public/assets/images/users/avatar-1.jpg" alt="" class="avatar-md rounded-circle">
                </div>

                <div class="user-info">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{Auth::user()->nombre}} {{-- <i class="mdi mdi-chevron-down"></i> --}} </a>
                        {{-- <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="mdi mdi-face-profile mr-2"></i> Perfil<div class="ripple-wrapper"></div></a></li>
                            <li><a href="{{ route('logout') }}" class="dropdown-item"  onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-power-settings mr-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul> --}}
                    </div>
                    <p class="font-15  m-0"> {{session('sistema_nombre')}} </p>
                </div>
            </div>
            <!--- fin user-box -->

            <ul class="navigation-menu">

                    @foreach (session('menuNivel01') as $key => $menu)

                        <li>
                            @if ($menu->url=='')

                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="{{$menu->icono}}"></i>
                                <span> {{$menu->nombre}} </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                @foreach (session('menuNivel02') as $key => $subMenu)
                                    @if($menu->id==$subMenu->dependencia)
                                    <li><a href="{{route($subMenu->url)}}">{{$subMenu->nombre}}</a></li>
                                    @endif
                                @endforeach

                            </ul>

                            @else
                                <a href="{{route($menu->url,$menu->parametro)}}" class="waves-effect">
                                    <i class="{{$menu->icono}}"></i>
                                    <span> {{$menu->nombre}}</span>
                                </a>

                            @endif

                        </li>

                    @endforeach

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
