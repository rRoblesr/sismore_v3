
    <div class="col-md-6">
        <div class="card-header bg-primary py-3 text-white">
            <h5  class="card-title mb-0 text-white"> Total cuadro de asignación de Personal según UGEL </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>                                                 
                        <th class="titulo_tabla">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                        <th class="titulo_tabla">TOTAL PERSONAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $suma=0;
                    @endphp
                
                @foreach ($lista_principal as $item)
                        
                    @php
                        $suma+= $item->cantidad; 
                    @endphp

                    <tr>                                            
                        <td class="fila_tabla">{{$item->ugel}}</td>
                        <td class="columna_derecha fila_tabla">{{ number_format($item->cantidad,0) }} </td>
                    </tr>

                    @endforeach

                    <tr> 
                        <td class="columna_derecha_total fila_tabla"> <b> TOTAL </b></td>
                        <td class="columna_derecha_total fila_tabla"> {{number_format($suma,0)}} </td>
                       
                    </tr>                                              
                    
                </tbody>
            </table>
        </div>

        <div >
            <div  class="col-md-12">
                Fuente: Sistema de Administración y Control de Plazas – NEXUS        
            </div>
        </div>

    </div>
        
    <div class="col-md-6">
        {{-- <div id="{{$contenedor}}">       
            @include('graficos.Circular')
        </div> --}}
    </div> 

    <br><br><br>
    <div class="col-md-6">
        <br><br>
        <div class="card-header bg-primary py-3 text-white">
             <h5   class="card-title mb-0 text-white"> Total cuadro de asignación de Personal por tipo de Trabajador según UGEL</h5>
        </div>

        <div class="table-responsive">
            <table style="width: 100%;"  border="1px solid #000"  >
                <thead>
                    <tr>                                                 
                        <th class="titulo_tabla">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                        <th class="titulo_tabla">TOTAL PERSONAL</th>
               
                    </tr>
                </thead>
                <tbody>
                    @php
                        $suma=0;                                            
                       
                    @endphp
                
                @foreach ($lista_principal as $itemCab)
                        
                    @php
                        $suma+= $itemCab->cantidad; 
                    @endphp

                    <tr>                                            
                        <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                        <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->cantidad,0) }} </td>
                    </tr>

                    @foreach ($lista_ugel_tipoTrab as $item)
                   
                    @if ($itemCab->ugel==$item->ugel)
                                   
                    <tr>                                            
                        <td class="fila_tabla">{{$item->tipoTrab}}</td>
                        <td class="columna_derecha fila_tabla">{{ number_format($item->cantidad,0) }} </td>                    
                    </tr>

                    @endif

                @endforeach


                @endforeach

                    <tr> 
                        <td  class="columna_derecha_total fila_tabla"> <b> TOTAL </b></td>
                        <td  class="columna_derecha_total fila_tabla"> {{number_format($suma,0)}} </td>
                    </tr>                                              
                    
                </tbody>
            </table>
        </div>    
        
    </div>

    <div class="col-md-6">
    </div>

    <br><br><br>
    <div class="col-md-6">
        <br><br>
        <div class="card-header bg-primary py-3 text-white">
             <h5  class="card-title mb-0 text-white"> Total cuadro de asignación de Personal por niveles según UGEL </h5>
        </div>

        <div class="table-responsive">
            <table style="width: 100%;"  border="1px solid #000"  >
                <thead>
                    <tr>                                                 
                        <th class="titulo_tabla">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                        <th class="titulo_tabla">TOTAL PERSONAL</th>
               
                    </tr>
                </thead>
                <tbody>
                    @php
                        $suma=0;                                            
                       
                    @endphp
                
                @foreach ($lista_principal as $itemCab)
                        
                    @php
                        $suma+= $itemCab->cantidad; 
                    @endphp

                    <tr>                                            
                        <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                        <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->cantidad,0) }} </td>
                    </tr>

                    @foreach ($lista_ugel_nivel as $item)
                   
                    @if ($itemCab->ugel==$item->ugel)
                                   
                    <tr>                                            
                        <td class="fila_tabla">{{$item->nivel}}</td>
                        <td class="columna_derecha fila_tabla">{{ number_format($item->cantidad,0) }} </td>                    
                    </tr>

                    @endif

                @endforeach


                @endforeach

                    <tr> 
                        <td  class="columna_derecha_total fila_tabla"> <b> TOTAL </b></td>
                        <td  class="columna_derecha_total fila_tabla"> {{number_format($suma,0)}} </td>
                    </tr>                                              
                    
                </tbody>
            </table>
        </div>
      
        
    </div>


