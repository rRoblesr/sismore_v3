
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

            <!--- por ahora -->

            {{-- @if (session('sistema_id')==1)
                <ul class="metismenu" id="side-menu">

                    <li>
                        <a href="{{route('sistema_acceder',session('sistema_id'))}}" class="waves-effect">
                            <i class="mdi mdi-home"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
    
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-folder-upload-outline"></i>
                            <span> Importar </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="{{route('PadronWeb.importar')}}">Padron Web - Inst. Educativas</a></li>
                            <li><a href="{{route('CuadroAsigPersonal.importar')}}">Asignacion de Personal</a></li>
                            <li><a href="{{route('ece.importar')}}">Eval. Censal de Estudiantes</a></li>      
                            <li><a href="{{route('Censo.importar')}}">Censo Educativo</a></li>       
   
                        </ul>
                    </li>
    
                    <li>
                        <a href="{{route('importacion.inicio')}}" class="waves-effect">
                            <i class="mdi mdi-check-bold"></i>
                            <span> Aprobar Importación</span>                  
                        </a>                   
                    </li>
    
                    <li>
                        <a href="{{route('Clasificador.menu','01')}}" class="waves-effect">
                            <i class="mdi mdi-equalizer-outline"></i>
                            <span> Indicadores </span>
                        </a>                    
                    </li>

                    
                    <li>
                        <a href="{{route('Clasificador.menu','01')}}" class="waves-effect">
                            <i class="mdi mdi-equalizer-outline"></i>
                            <span> Educacion ggggg </span>
                        </a>                    
                    </li>
    
                    <li>
                        <a href="{{route('Clasificador.menu','04')}}" class="waves-effect">
                            <i class="mdi mdi-chart-tree"></i>
                            <span> PDRC </span>
                        </a>                    
                    </li>
    
                    <li>
                        <a href="{{route('Clasificador.menu','05')}}" class="waves-effect">
                            <i class="mdi mdi-equalizer"></i>
                            <span> Obj. Estrat. Instit. </span>
                        </a>                    
                    </li>
    
                    <li>
                    
                        <a href="{{route('AEI_tempo')}}" class="waves-effect">   
                            <i class="mdi mdi-poll-box"></i>
                            <span> Act. Estrat. Instit. </span>
                        </a>                    
                    </li>
    
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-view-list"></i>
                            <span> Inst. Educativas </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="tables-basic.html">Basic Tables</a></li>
                            <li><a href="tables-datatable.html">Data Table</a></li>
                            <li><a href="tables-editable.html">Editable Table</a></li>
                            <li><a href="tables-responsive.html">Responsive Table</a></li>
                        </ul>
                    </li>
    
                   
         
                </ul> 
            
            @else
            
                <ul class="metismenu" id="side-menu">
j
                    <li>
                        <a href="{{route('sistema_acceder',session('sistema_id'))}}" class="waves-effect">
                            <i class="mdi mdi-home"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
    
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="mdi mdi-folder-upload-outline"></i>
                            <span> Importar </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">       
                            
                            <li><a href="{{route('Datass.importar')}}">Datass</a></li>        
                        </ul>
                    </li>
    
                    <li>
                        <a href="{{route('importacion.inicio')}}" class="waves-effect">
                            <i class="mdi mdi-check-bold"></i>
                            <span> Aprobar Importación</span>                  
                        </a>                   
                    </li>
         
                </ul> 
                          
            @endif --}}
          

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->