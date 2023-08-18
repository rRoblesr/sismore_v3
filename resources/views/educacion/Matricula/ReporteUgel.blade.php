
    <div class="col-md-6">
        <div class="card-header bg-primary py-3 text-white">
            <h5  class="card-title mb-0 text-white"> Total matrícula {{$tipoDescrip}} según UGEL al {{$fecha_Matricula_texto}} </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>                                                 
                        <th>UNIDAD DE GESTION EDUCATIVA LOCAL</th>
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
                    <td>{{$item->nombre}}</td>
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
                            <h5 class="card-title mb-0 text-white">Total matrícula {{$tipoDescrip}} nivel INICIAL por Ciclo, Edad  y Sexo según UGEL al {{$fecha_Matricula_texto}}</h5>
                        </div>
                        <div id="cardCollpase1" class="collapse show">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" >
                                        <thead>
                                            <tr>
                                                <th colspan="1" rowspan="4" class="columna_inherit">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                                                <th colspan="3" class="columna_centro">TOTAL</th>
                                                <th colspan="12" class="columna_centro">MATRICULA POR EDAD Y SEXO</th>
                                            </tr>
                            
                                            <tr> 
                                                <th class="columna_derecha" rowspan="3" >TOTAL</th>
                                                <th class="columna_derecha" rowspan="3">HOMBRE</th>
                                                <th class="columna_derecha" rowspan="3">MUJER</th>
                                                <th class="columna_centro" colspan="6" style="color: #9c9205;">CICLO I</th>
                                                <th class="columna_centro" colspan="6" style="color: #039717;">CICLO II</th>                                     
                            
                                            </tr>
                            
                                            <tr> 
                                                
                                                <th class="columna_centro" colspan="2" style="color: #9c9205;"> 0</th>
                                                <th class="columna_centro" colspan="2" style="color: #9c9205;"> 1</th>
                                                <th class="columna_centro" colspan="2" style="color: #9c9205;"> 2</th>
                                                <th class="columna_centro" colspan="2" style="color: #039717;"> 3</th>
                                                <th class="columna_centro" colspan="2" style="color: #039717;"> 4</th>
                                                <th class="columna_centro" colspan="2" style="color: #039717;"> 5</th>
                                                
                            
                                            </tr>
                            
                                            <tr>                   
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                                $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                                $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                                $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;$sum_cero_nivel_hombre=0;$sum_cero_nivel_mujer=0;
                                            @endphp
                                        
                                        @foreach ($lista_total_matricula_Inicial as $item)
                                                
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
                                                <td>{{$item->nombre}}</td>
                                                <td class="columna_derecha">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                <td class="columna_derecha">{{number_format($item->hombres,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->mujeres,0)}}</td>
                            
                                                <td class="columna_derecha">{{number_format($item->cero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_mujer,0)}}</td>
                            
                                                
                                            </tr>
                            
                                            @endforeach
                                            
                                            <tr> 
                                                <td> <b> TOTAL </b></td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sumMujer,0)}}  </td>
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_cero_nivel_hombre,0)}}  </td>  
                                                <td class="columna_derecha_total"> {{number_format($sum_cero_nivel_mujer,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td> 
                            
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
                            <h5 class="card-title mb-0 text-white">Total matrícula {{$tipoDescrip}} nivel PRIMARIA por grado y sexo según UGEL al {{$fecha_Matricula_texto}}</h5>
                        </div>
                        <div id="cardCollpase2" class="collapse show">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th colspan="1" rowspan="3" class="columna_inherit">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                                                <th colspan="3" class="columna_centro">TOTAL</th>
                                                <th colspan="12" class="columna_centro">MATRICULA POR GRADO Y SEXO</th>
                                            </tr>
                                            <tr> 
                                                <th class="columna_derecha" rowspan="2" >TOTAL</th>
                                                <th class="columna_derecha" rowspan="2">HOMBRE</th>
                                                <th class="columna_derecha" rowspan="2">MUJER</th>
                                                <th class="columna_centro" colspan="2"> 1°</th>
                                                <th class="columna_centro" colspan="2"> 2°</th>
                                                <th class="columna_centro" colspan="2"> 3°</th>
                                                <th class="columna_centro" colspan="2"> 4°</th>
                                                <th class="columna_centro" colspan="2"> 5°</th>
                                                <th class="columna_centro" colspan="2"> 6°</th>
                            
                                            </tr>
                            
                                            <tr>                   
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                                $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                                $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                                $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;$sum_sexto_nivel_hombre=0;$sum_sexto_nivel_mujer=0;
                                            @endphp
                                        
                                        @foreach ($lista_total_matricula_Primaria as $item)
                                                
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
                                                <td>{{$item->nombre}}</td>
                                                <td class="columna_derecha">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                <td class="columna_derecha">{{number_format($item->hombres,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->mujeres,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_mujer,0)}}</td>
                            
                                                <td class="columna_derecha">{{number_format($item->sexto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->sexto_nivel_mujer,0)}}</td>
                                            </tr>
                            
                                            @endforeach
                                            
                                            <tr> 
                                                <td> <b> TOTAL </b></td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sumMujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td>  
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_sexto_nivel_hombre,0)}}  </td>  
                                                <td class="columna_derecha_total"> {{number_format($sum_sexto_nivel_mujer,0)}}  </td>                    
                            
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
                            <h5 class="card-title mb-0 text-white">Total matrícula {{$tipoDescrip}} nivel SECUNDARIA por grado y sexo según UGEL al {{$fecha_Matricula_texto}}</h5>
                        </div>
                        <div id="cardCollpase3" class="collapse show">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th colspan="1" rowspan="3" class="columna_inherit">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                                                <th colspan="3" class="columna_centro">TOTAL</th>
                                                <th colspan="10" class="columna_centro">MATRICULA POR GRADO Y SEXO</th>
                                            </tr>
                                            <tr> 
                                                <th class="columna_derecha" rowspan="2" >TOTAL</th>
                                                <th class="columna_derecha" rowspan="2">HOMBRE</th>
                                                <th class="columna_derecha" rowspan="2">MUJER</th>
                                                <th class="columna_centro" colspan="2"> 1°</th>
                                                <th class="columna_centro" colspan="2"> 2°</th>
                                                <th class="columna_centro" colspan="2"> 3°</th>
                                                <th class="columna_centro" colspan="2"> 4°</th>
                                                <th class="columna_centro" colspan="2"> 5°</th>
                            
                                            </tr>
                            
                                            <tr>                   
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                                $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                                $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                                $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;
                                            @endphp
                                        
                                        @foreach ($lista_total_matricula_Secundaria as $item)
                                                
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
                                                <td>{{$item->nombre}}</td>
                                                <td class="columna_derecha">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                <td class="columna_derecha">{{number_format($item->hombres,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->mujeres,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_mujer,0)}}</td>
                                            </tr>
                            
                                            @endforeach
                                            
                                            <tr> 
                                                <td> <b> TOTAL </b></td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sumMujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td>                     
                            
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
    
    @else

        <!-- NIVEL EBE -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Portlet card -->
                    <div class="card">
                        <div class="card-header bg-primary py-3 text-white">
                            <div class="card-widgets">
                            
                                <a data-toggle="collapse" href="#cardCollpase4" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                    <i class="mdi mdi-minus"></i>
                                </a>
                            
                            </div>
                            <h5 class="card-title mb-0 text-white">Total matrícula Educación Básica Especial, Inicial por edad y sexo, Primaria por grado y sexo segun Ugel al {{$fecha_Matricula_texto}} </h5>
                        </div>
                        <div id="cardCollpase4" class="collapse show">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th colspan="1" rowspan="3" class="columna_inherit">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                                                <th colspan="3" class="columna_centro">TOTAL</th>
                                                <th colspan="6" class="columna_centro">MATRICULA POR EDAD Y SEXO</th>
                                                <th colspan="12" class="columna_centro">MATRICULA POR GRADO Y SEXO</th>
                                            </tr>
                                            <tr> 
                                                <th class="columna_derecha" rowspan="2" >TOTAL</th>
                                                <th class="columna_derecha" rowspan="2">HOMBRE</th>
                                                <th class="columna_derecha" rowspan="2">MUJER</th>
                                                <th class="columna_centro" colspan="2"> 3</th>
                                                <th class="columna_centro" colspan="2"> 4</th>
                                                <th class="columna_centro" colspan="2"> 5</th>
                            
                                                <th class="columna_centro" colspan="2"> 1°</th>
                                                <th class="columna_centro" colspan="2"> 2°</th>
                                                <th class="columna_centro" colspan="2"> 3°</th>
                                                <th class="columna_centro" colspan="2"> 4°</th>
                                                <th class="columna_centro" colspan="2"> 5°</th>
                                                <th class="columna_centro" colspan="2"> 6°</th>
                            
                                            </tr>
                            
                                            <tr>                   
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                                <th class="columna_centro">H</th>
                                                <th class="columna_centro">M</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sunHombre=0; $sumMujer=0; $sum_primer_nivel_hombre=0; $sum_primer_nivel_mujer=0;
                                                $sum_segundo_nivel_hombre=0; $sum_segundo_nivel_mujer=0;$sum_tercero_nivel_hombre=0; 
                                                $sum_tercero_nivel_mujer=0; $sum_cuarto_nivel_hombre=0;$sum_cuarto_nivel_mujer=0;
                                                $sum_quinto_nivel_hombre=0; $sum_quinto_nivel_mujer=0;
                                                $sum_sexto_nivel_hombre=0; $sum_sexto_nivel_mujer=0;
                            
                                                $sum_tres_anios_mujer_ebe=0;$sum_cuatro_anios_mujer_ebe=0;$sum_cinco_anios_mujer_ebe=0;
                                                $sum_tres_anios_hombre_ebe=0;$sum_cuatro_anios_hombre_ebe=0;$sum_cinco_anios_hombre_ebe=0;
                                            @endphp
                                        
                                        @foreach ($lista_total_matricula_EBE as $item)
                                                
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
                                                $sum_sexto_nivel_mujer+=$item->sexto_nivel_mujer;
                            
                                                $sum_tres_anios_mujer_ebe+= $item->tres_anios_mujer_ebe;
                                                $sum_cuatro_anios_mujer_ebe+= $item->cuatro_anios_mujer_ebe;
                                                $sum_cinco_anios_mujer_ebe+= $item->cinco_anios_mujer_ebe;
                            
                                                $sum_tres_anios_hombre_ebe+= $item->tres_anios_hombre_ebe;
                                                $sum_cuatro_anios_hombre_ebe+= $item->cuatro_anios_hombre_ebe;
                                                $sum_cinco_anios_hombre_ebe+= $item->cinco_anios_hombre_ebe;
                                            @endphp
                            
                                            <tr>                                            
                                                <td>{{$item->nombre}}</td>
                                                <td class="columna_derecha">{{ number_format($item->hombres + $item->mujeres,0) }} </td>
                                                <td class="columna_derecha">{{number_format($item->hombres,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->mujeres,0)}}</td>
                            
                                                <td class="columna_derecha">{{number_format($item->tres_anios_hombre_ebe,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tres_anios_mujer_ebe,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuatro_anios_hombre_ebe,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuatro_anios_mujer_ebe,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cinco_anios_hombre_ebe,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cinco_anios_mujer_ebe,0)}}</td>
                            
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->primer_nivel_mujer,0)}}</td> 
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->segundo_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->tercero_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->cuarto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->quinto_nivel_mujer,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->sexto_nivel_hombre,0)}}</td>
                                                <td class="columna_derecha">{{number_format($item->sexto_nivel_mujer,0)}}</td>
                                            </tr>
                            
                                            @endforeach
                                            
                                            <tr> 
                                                <td> <b> TOTAL </b></td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre + $sumMujer,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sunHombre,0)}} </td>
                                                <td class="columna_derecha_total"> {{number_format($sumMujer,0)}}  </td>
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_tres_anios_hombre_ebe,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tres_anios_mujer_ebe,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_cuatro_anios_hombre_ebe,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_cuatro_anios_mujer_ebe,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_cinco_anios_hombre_ebe,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_cinco_anios_mujer_ebe,0)}}  </td>
                                                
                            
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_primer_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_segundo_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_tercero_nivel_mujer,0)}}  </td> 
                            
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_hombre,0)}}  </td> 
                                                <td class="columna_derecha_total"> {{number_format($sum_cuarto_nivel_mujer,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_quinto_nivel_mujer,0)}}  </td> 
                                                
                                                <td class="columna_derecha_total"> {{number_format($sum_sexto_nivel_hombre,0)}}  </td>
                                                <td class="columna_derecha_total"> {{number_format($sum_sexto_nivel_mujer,0)}}  </td> 
                            
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
        <!-- FIN EBE-->
    
    @endif





