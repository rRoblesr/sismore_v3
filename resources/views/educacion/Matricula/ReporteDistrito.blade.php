<div class="col-md-6">
    <div class="card-header bg-primary py-3 text-white">
        <h5  class="card-title mb-0 text-white"> Total matrícula {{$tipoDescrip}} según DISTRITO al {{$fecha_Matricula_texto}} </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>                                                 
                    <th>PROVINCIA</th>
                    <th class="columna_derecha">TOTAL</th>
                    <th class="columna_derecha">HOMBRE</th>
                    <th class="columna_derecha">MUJER</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sunHombre=0;                                            
                    $sumMujer=0
                @endphp
            
            @foreach ($lista_total_matricula_EBR as $item)
                    
                @php
                    $sunHombre+= $item->hombres;                                           
                    $sumMujer+= $item->mujeres
                @endphp

                <tr>                                            
                <td>{{$item->provincia}}</td>
                <td class="columna_derecha">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                <td class="columna_derecha">{{number_format($item->hombres,0)}}</td>
                <td class="columna_derecha">{{number_format($item->mujeres,0)}}</td>
                </tr>

                @endforeach

                <tr> 
                <td> <b> TOTAL </b></td>
                <td class="columna_derecha_total"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                <td class="columna_derecha_total"> {{number_format($sunHombre,0)}} </td>
                <td class="columna_derecha_total"> {{number_format($sumMujer,0)}}  </td>
                </tr>                                              
                
            </tbody>
        </table>
    </div>

    <div >
        <div  class="col-md-6">
            Fuente: SIAGIE- MINEDU          
        </div>
    </div>

</div>

<div class="col-md-6">
    <div id="{{$contenedor}}">       
        @include('graficos.Circular')
    </div>
</div>

@if($tipoDescrip !='EBE')
    <!-- NIVEL INICIAL -->
    <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Portlet card -->
                    <div class="card">
                        <div class="card-header bg-primary py-3 text-white">
                            <div class="card-widgets">
                            
                                <a data-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                    <i class="mdi mdi-minus"></i>
                                </a>
                            
                            </div>
                            <h5 class="card-title mb-0 text-white">Total matrícula {{$tipoDescrip}} nivel INICIAL por Ciclo, Edad  y Sexo según Distrito al {{$fecha_Matricula_texto}}</h5>
                        </div>
                        <div id="cardCollpase1" class="collapse show">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    {{-- class="table table-striped mb-0" --}}
                                    <table style="width: 100%;  "  border="1px solid #000"  >
                                        <thead>
                                            <tr >
                                                <th colspan="1" rowspan="4" class="titulo_tabla" >UGEL / DISTRITO</th>
                                                <th colspan="3" class="titulo_tabla">TOTAL</th>
                                                <th colspan="12" class="titulo_tabla">MATRICULA POR EDAD Y SEXO</th>
                                            </tr>
                                
                                            <tr> 
                                                <th class="titulo_tabla" rowspan="3" >TOTAL</th>
                                                <th class="titulo_tabla" rowspan="3">HOMBRE</th>
                                                <th class="titulo_tabla" rowspan="3">MUJER</th>
                                                <th class="titulo_tabla" colspan="6" >CICLO I</th>
                                                <th class="titulo_tabla" colspan="6" >CICLO II</th>                                   
                                
                                            </tr>
                                
                                            <tr>
                                                <th class="titulo_tabla" colspan="2" > 0</th>
                                                <th class="titulo_tabla" colspan="2" > 1</th>
                                                <th class="titulo_tabla" colspan="2" > 2</th>
                                                <th class="titulo_tabla" colspan="2" > 3</th>
                                                <th class="titulo_tabla" colspan="2" > 4</th>
                                                <th class="titulo_tabla" colspan="2" > 5</th>
                                            </tr>
                                
                                            <tr>                   
                                                <th class="titulo_tabla">H</th>
                                                <th class="titulo_tabla">M</th>
                                                <th class="titulo_tabla">H</th>
                                                <th class="titulo_tabla">M</th>
                                                <th class="titulo_tabla">H</th>
                                                <th class="titulo_tabla">M</th>
                                                <th class="titulo_tabla">H</th>
                                                <th class="titulo_tabla">M</th>
                                                <th class="titulo_tabla">H</th>
                                                <th class="titulo_tabla">M</th>
                                                <th class="titulo_tabla">H</th>
                                                <th class="titulo_tabla">M</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                                $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                                $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                                $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;$sum_cero_nivel_hombre=0;$sum_cero_nivel_mujer=0;
                                            @endphp
                                        
                                            @foreach ($lista_matricula_Inicial_cabecera as $itemCab)
                                            
                                            <tr>                                            
                                                <td class="fila_tabla"><b> {{$itemCab->provincia}} </b>  </td>
                                                <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->hombres + $itemCab->mujeres,0) }} </td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->hombres,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->mujeres,0)}}</td>
                                
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->quinto_nivel_mujer,0)}}</td>             
                                            </tr>
                                
                                                @foreach ($lista_total_matricula_Inicial as $item)
                                                    
                                                    @if ($itemCab->provincia==$item->provincia)
                                                    
                                                    @php
                                                        $sunHombre+= $item->hombres;
                                                        $sumMujer+= $item->mujeres;
                                                        $sum_primer_nivel_hombre+= $item->primer_nivel_hombre; 
                                                        $sum_primer_nivel_mujer+= $item->primer_nivel_mujer;
                                                        $sum_segundo_nivel_hombre+= $item->segundo_nivel_hombre;
                                                        $sum_segundo_nivel_mujer+=$item->segundo_nivel_mujer;
                                                        $sum_tercero_nivel_hombre+=$item->tercero_nivel_hombre;
                                                        $sum_tercero_nivel_mujer+=$item->tercero_nivel_mujer;
                                
                                                        $sum_cuarto_nivel_hombre+= $item->cuarto_nivel_hombre;
                                                        $sum_cuarto_nivel_mujer+= $item->cuarto_nivel_mujer;
                                                        $sum_quinto_nivel_hombre+=$item->quinto_nivel_hombre;
                                                        $sum_quinto_nivel_mujer+=$item->quinto_nivel_mujer;
                                                        $sum_cero_nivel_hombre+=$item->cero_nivel_hombre;
                                                        $sum_cero_nivel_mujer+= $item->cero_nivel_mujer;
                                                    @endphp
                                
                                                    <tr>                                            
                                                        <td class="fila_tabla">{{$item->distrito}}</td>
                                                        <td class="columna_derecha fila_tabla">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->hombres,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->mujeres,0)}}</td>
                                
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->cero_nivel_hombre,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->cero_nivel_mujer,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                        <td class="columna_derecha fila_tabla">{{number_format($item->quinto_nivel_mujer,0)}}</td>                     
                                                    </tr>
                                
                                                    @endif
                                
                                                @endforeach
                                            @endforeach
                                
                                            <tr> 
                                                <td class="fila_tabla"> <b> TOTAL </b></td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sunHombre,0)}} </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sumMujer,0)}}  </td>
                                
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cero_nivel_hombre,0)}}  </td>  
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cero_nivel_mujer,0)}}  </td> 
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total fila_tabla"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td>
                                            </tr>                                              
                                            
                                        </tbody>
                                    </table>
                                </div> 
                            
                                <div class="form-group row">
                                    <div  class="col-md-6">
                                        Fuente: SIAGIE- MINEDU          
                                    </div>
                            
                                    <div  class="col-md-6" style="text-align: right">
                                        H: Hombre / M: Mujer   
                                    </div>
                                </div> 
                            
                            </div>
                        </div>
                    </div>
                    <!-- end card-->
                </div>
            </div>
    </div>
    <!-- FIN NIVEL INICIAL-->


    <!-- NIVEL PRIMARIA -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Portlet card -->
                <div class="card">
                    <div class="card-header bg-primary py-3 text-white">
                        <div class="card-widgets">
                        
                            <a data-toggle="collapse" href="#cardCollpase2" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                <i class="mdi mdi-minus"></i>
                            </a>
                        
                        </div>
                        <h5 class="card-title mb-0 text-white">Total matrícula {{$tipoDescrip}} nivel PRIMARIA por grado y sexo según Distrito al {{$fecha_Matricula_texto}}</h5>
                    </div>
                    <div id="cardCollpase2" class="collapse show">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table style="width: 100%;  "  border="1px solid #000" >
                                    <thead>
                                        <tr>
                                            <th colspan="1" rowspan="3" class="titulo_tabla">UGEL / DISTRITO</th>
                                            <th colspan="3" class="titulo_tabla">TOTAL</th>
                                            <th colspan="12" class="titulo_tabla">MATRICULA POR GRADO Y SEXO</th>
                                        </tr>
                                        <tr> 
                                            <th class="titulo_tabla" rowspan="2" >TOTAL</th>
                                            <th class="titulo_tabla" rowspan="2">HOMBRE</th>
                                            <th class="titulo_tabla" rowspan="2">MUJER</th>
                                            <th class="titulo_tabla" colspan="2"> 1°</th>
                                            <th class="titulo_tabla" colspan="2"> 2°</th>
                                            <th class="titulo_tabla" colspan="2"> 3°</th>
                                            <th class="titulo_tabla" colspan="2"> 4°</th>
                                            <th class="titulo_tabla" colspan="2"> 5°</th>
                                            <th class="titulo_tabla" colspan="2"> 6°</th>
                        
                                        </tr>
                        
                                        <tr>                   
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                            $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                            $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                            $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;$sum_sexto_nivel_hombre=0;$sum_sexto_nivel_mujer=0;
                                        @endphp
                        
                                    @foreach ($lista_matricula_Primaria_cabecera as $itemCab)
                                                
                                    <tr>                                            
                                    <td class="fila_tabla"><b> {{$itemCab->provincia}} </b>  </td>
                                    <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->hombres + $itemCab->mujeres,0) }} </td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->hombres,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->mujeres,0)}}</td>
                        
                                    
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->primer_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->primer_nivel_mujer,0)}}</td> 
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->segundo_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->segundo_nivel_mujer,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->tercero_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->tercero_nivel_mujer,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cuarto_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cuarto_nivel_mujer,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->quinto_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->quinto_nivel_mujer,0)}}</td>  
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->sexto_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->sexto_nivel_mujer,0)}}</td>           
                                    </tr>
                                    
                                        @foreach ($lista_total_matricula_Primaria as $item)
                                            @if ($itemCab->provincia==$item->provincia)    
                                            @php
                                                $sunHombre+= $item->hombres;
                                                $sumMujer+= $item->mujeres;
                                                $sum_primer_nivel_hombre+= $item->primer_nivel_hombre; 
                                                $sum_primer_nivel_mujer+= $item->primer_nivel_mujer;
                                                $sum_segundo_nivel_hombre+= $item->segundo_nivel_hombre;
                                                $sum_segundo_nivel_mujer+=$item->segundo_nivel_mujer;
                                                $sum_tercero_nivel_hombre+=$item->tercero_nivel_hombre;
                                                $sum_tercero_nivel_mujer+=$item->tercero_nivel_mujer;
                        
                                                $sum_cuarto_nivel_hombre+= $item->cuarto_nivel_hombre;
                                                $sum_cuarto_nivel_mujer+= $item->cuarto_nivel_mujer;
                                                $sum_quinto_nivel_hombre+=$item->quinto_nivel_hombre;
                                                $sum_quinto_nivel_mujer+=$item->quinto_nivel_mujer;
                                                $sum_sexto_nivel_hombre+=$item->sexto_nivel_hombre;
                                                $sum_sexto_nivel_mujer+= $item->sexto_nivel_mujer;
                                            @endphp
                        
                                            <tr>                                            
                                                <td class="fila_tabla">{{$item->distrito}}</td>
                                                <td class="columna_derecha fila_tabla">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->hombres,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->mujeres,0)}}</td>
                        
                                                <td class="columna_derecha fila_tabla">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha fila_tabla">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->quinto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->sexto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->sexto_nivel_mujer,0)}}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                        
                                        <tr> 
                                            <td class="fila_tabla"> <b> TOTAL </b></td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sunHombre,0)}} </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sumMujer,0)}}  </td>
                        
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td>  
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_sexto_nivel_hombre,0)}}  </td>  
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_sexto_nivel_mujer,0)}}  </td>                    
                        
                                        </tr>                                              
                                        
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="form-group row">
                                <div  class="col-md-6">
                                    Fuente: SIAGIE- MINEDU          
                                </div>
                        
                                <div  class="col-md-6" style="text-align: right">
                                    H: Hombre / M: Mujer   
                                </div>
                            </div> 
                        
                        </div>
                    </div>
                </div>
                <!-- end card-->
            </div>
        </div>
    </div>

    <!-- FIN NIVEL PRIMARIA-->


    <!-- NIVEL SECUNDARIA -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Portlet card -->
                <div class="card">
                    <div class="card-header bg-primary py-3 text-white">
                        <div class="card-widgets">
                        
                            <a data-toggle="collapse" href="#cardCollpase3" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                <i class="mdi mdi-minus"></i>
                            </a>
                        
                        </div>
                        <h5 class="card-title mb-0 text-white">Total matrícula {{$tipoDescrip}} nivel SECUNDARIA por grado y sexo según Distrito al {{$fecha_Matricula_texto}}</h5>
                    </div>
                    <div id="cardCollpase3" class="collapse show">
                        <div class="card-body">
                        
                            <div class="table-responsive">
                                <table style="width: 100%;  "  border="1px solid #000" >
                                    <thead>
                                        <tr>
                                            <th colspan="1" rowspan="3" class="titulo_tabla">UGEL / DISTRITO</th>
                                            <th colspan="3" class="titulo_tabla">TOTAL</th>
                                            <th colspan="10" class="titulo_tabla">MATRICULA POR GRADO Y SEXO</th>
                                        </tr>
                                        <tr> 
                                            <th class="titulo_tabla" rowspan="2" >TOTAL</th>
                                            <th class="titulo_tabla" rowspan="2">HOMBRE</th>
                                            <th class="titulo_tabla" rowspan="2">MUJER</th>
                                            <th class="titulo_tabla" colspan="2"> 1°</th>
                                            <th class="titulo_tabla" colspan="2"> 2°</th>
                                            <th class="titulo_tabla" colspan="2"> 3°</th>
                                            <th class="titulo_tabla" colspan="2"> 4°</th>
                                            <th class="titulo_tabla" colspan="2"> 5°</th>
                                        </tr>
                        
                                        <tr>                   
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>
                                            <th class="titulo_tabla">H</th>
                                            <th class="titulo_tabla">M</th>               
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                            $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                            $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                            $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;
                                        @endphp
                        
                                    @foreach ($lista_matricula_Secundaria_cabecera as $itemCab)
                                                
                                    <tr>                                            
                                    <td class="fila_tabla"><b> {{$itemCab->provincia}} </b>  </td>
                                    <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->hombres + $itemCab->mujeres,0) }} </td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->hombres,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->mujeres,0)}}</td>
                        
                                    
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->primer_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->primer_nivel_mujer,0)}}</td> 
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->segundo_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->segundo_nivel_mujer,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->tercero_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->tercero_nivel_mujer,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cuarto_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->cuarto_nivel_mujer,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->quinto_nivel_hombre,0)}}</td>
                                    <td class="columna_derecha_total fila_tabla">{{number_format($itemCab->quinto_nivel_mujer,0)}}</td>                    
                                    </tr>
                                    
                                        @foreach ($lista_total_matricula_Secundaria as $item)
                                            @if ($itemCab->provincia==$item->provincia)    
                                            @php
                                                $sunHombre+= $item->hombres;
                                                $sumMujer+= $item->mujeres;
                                                $sum_primer_nivel_hombre+= $item->primer_nivel_hombre; 
                                                $sum_primer_nivel_mujer+= $item->primer_nivel_mujer;
                                                $sum_segundo_nivel_hombre+= $item->segundo_nivel_hombre;
                                                $sum_segundo_nivel_mujer+=$item->segundo_nivel_mujer;
                                                $sum_tercero_nivel_hombre+=$item->tercero_nivel_hombre;
                                                $sum_tercero_nivel_mujer+=$item->tercero_nivel_mujer;
                        
                                                $sum_cuarto_nivel_hombre+= $item->cuarto_nivel_hombre;
                                                $sum_cuarto_nivel_mujer+= $item->cuarto_nivel_mujer;
                                                $sum_quinto_nivel_hombre+=$item->quinto_nivel_hombre;
                                                $sum_quinto_nivel_mujer+=$item->quinto_nivel_mujer;
                                            @endphp
                        
                                            <tr>                                            
                                                <td class="fila_tabla">{{$item->distrito}}</td>
                                                <td class="columna_derecha fila_tabla">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->hombres,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->mujeres,0)}}</td>
                        
                                                <td class="columna_derecha fila_tabla">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha fila_tabla">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha fila_tabla">{{number_format($item->quinto_nivel_mujer,0)}}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                        
                                        <tr> 
                                            <td class="fila_tabla"> <b> TOTAL </b></td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sunHombre,0)}} </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sumMujer,0)}}  </td>
                        
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                            <td class="columna_derecha_total fila_tabla"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td>
                        
                                        </tr>                                              
                                        
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="form-group row">
                                <div  class="col-md-6">
                                    Fuente: SIAGIE- MINEDU          
                                </div>
                        
                                <div  class="col-md-6" style="text-align: right">
                                    H: Hombre / M: Mujer   
                                </div>
                            </div> 
                        
                        </div>
                    </div>
                </div>
                <!-- end card-->
            </div>
        </div>
    </div>

    <!-- FIN NIVEL SECUNDARIA-->

 @endif
