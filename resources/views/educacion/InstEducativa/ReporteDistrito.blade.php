<div class="col-md-6">
    <div class="card-header bg-primary py-3 text-white">
        <h5  class="card-title mb-0 text-white"> INSTITUCIONES EDUCATIVAS </h5>
    </div>
    <div class="table-responsive">
        <table style="width: 100%;  "border="1px solid #000"  >
            <thead>
                <tr>
                    <th class="titulo_tabla">DISTRITO</th>
                    <th class="titulo_tabla">PÚBLICAS</th>
                    <th class="titulo_tabla">PRIVADAS</th>
                    <th class="titulo_tabla">TOTAL</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($lista_resumen_porRegion as $item1)
                    {{-- <tr>     
                        <td class="fila_tabla"><b> TOTAL UCAYALI </b>  </td>
                        <td class="columna_derecha_total fila_tabla"><b>{{ number_format(0,0) }}</b>  </td>
                        <td class="columna_derecha_total fila_tabla"><b>{{ number_format(0,0) }}</b>  </td>
                        <td class="columna_derecha_total fila_tabla"><b>{{ number_format(0,0) }}</b>  </td>
                    </tr> --}}
                    
                        @foreach ($lista_resumen_porProvincia as $item2)

                            @if ($item1->region == $item2->region )
                                <tr>     
                                    <td class="fila_tabla"><b>{{ $item2->provincia }} </b>  </td>
                                    <td class="columna_derecha_total fila_tabla"><b>{{ number_format($item2->publica,0) }}</b>  </td>
                                    <td class="columna_derecha_total fila_tabla"><b>{{ number_format($item2->privada,0) }}</b>  </td>
                                    <td class="columna_derecha_total fila_tabla"><b>{{ number_format($item2->publica + $item2->privada,0) }}</b>  </td>
                                </tr>

                                @foreach ($lista_resumen_porDistrito as $item3)
                                    @if ($item2->provincia == $item3->provincia )
                                        <tr>
                                            <td class="fila_tabla"> &nbsp;&nbsp; {{$item3->distrito}}</td>
                                            <td class="columna_derecha fila_tabla">{{ number_format($item3->publica,0) }} </td>
                                            <td class="columna_derecha fila_tabla">{{ number_format($item3->privada,0) }}</td>
                                            <td class="columna_derecha fila_tabla">{{ number_format($item3->publica + $item2->privada,0) }}</td>
                                        </tr>                            
                                    @endif
                                @endforeach

                            @endif 

                        @endforeach
                @endforeach
               
            </tbody>
        </table>
    </div>

    <div >
        <div  class="col-md-6">
            Fuente: PADRON WEB - ESCALE         
        </div>
    </div>

</div>

{{-- <div class="col-md-6">
    <div id="{{$contenedor}}">       
        @include('graficos.Circular')
    </div>
</div> --}}

