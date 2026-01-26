{{-- resources/views/components/menu-publico.blade.php --}}
<ul class="metismenu" id="side-menu">
    @if (session()->has('menuPublico01'))
        @foreach (session('menuPublico01') as $key => $menu)
            <li>
                @if ($menu->tipo_enlace == 0)
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="{{ $menu->icono }}"></i>
                        <span>{{ $menu->nombre }}</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        @foreach (session('menuPublico02') as $key => $subMenu)
                            @if ($menu->id == $subMenu->dependencia)
                                @if ($subMenu->tipo_enlace == 0)
                                    <li>
                                        <a href="javascript: void(0);">
                                            {{ $subMenu->nombre }}
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <ul class="nav-third-level" aria-expanded="false">
                                            @foreach (session('menuPublico03') as $key => $zmenu)
                                                @if ($subMenu->id == $zmenu->dependencia)
                                                    @if ($zmenu->tipo_enlace == 1)
                                                        <li>
                                                            @if(Route::has($zmenu->url))
                                                                <a href="{{ route($zmenu->url) }}">{{ $zmenu->nombre }}</a>
                                                            @else
                                                                <a href="#" class="text-muted"
                                                                    title="Ruta no definida: {{ $zmenu->url }}">
                                                                    {{ $zmenu->nombre }} ⚠️
                                                                </a>
                                                            @endif
                                                        </li>
                                                    @elseif ($zmenu->tipo_enlace == 2)
                                                        <li>
                                                            @if(Route::has($zmenu->url))
                                                                <a href="{{ route($zmenu->url, $zmenu->id) }}">{{ $zmenu->nombre }}</a>
                                                            @else
                                                                <a href="#" class="text-muted"
                                                                    title="Ruta no definida: {{ $zmenu->url }}">
                                                                    {{ $zmenu->nombre }} ⚠️
                                                                </a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @elseif ($subMenu->tipo_enlace == 1)
                                    <li>
                                        @if(Route::has($subMenu->url))
                                            <a href="{{ route($subMenu->url) }}">{{ $subMenu->nombre }}</a>
                                        @else
                                            <a href="#" class="text-muted"
                                                title="Ruta no definida: {{ $subMenu->url }}">
                                                {{ $subMenu->nombre }} ⚠️
                                            </a>
                                        @endif
                                    </li>
                                @elseif ($subMenu->tipo_enlace == 2)
                                    <li>
                                        @if(Route::has($subMenu->url))
                                            <a href="{{ route($subMenu->url, $subMenu->id) }}">{{ $subMenu->nombre }}</a>
                                        @else
                                            <a href="#" class="text-muted"
                                                title="Ruta no definida: {{ $subMenu->url }}">
                                                {{ $subMenu->nombre }} ⚠️
                                            </a>
                                        @endif
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                @elseif ($menu->tipo_enlace == 1)
                    <a href="{{ route($menu->url, session('sistema_publico_nombre')) }}"
                        class="waves-effect">
                        <i class="{{ $menu->icono }}"></i>
                        <span>{{ $menu->nombre }}</span>
                    </a>
                @elseif ($menu->tipo_enlace == 2)
                    <a href="{{ route($menu->url, $menu->id) }}" class="waves-effect">
                        <i class="{{ $menu->icono }}"></i>
                        <span>{{ $menu->nombre }}</span>
                    </a>
                @endif
            </li>
        @endforeach
    @endif
</ul>
