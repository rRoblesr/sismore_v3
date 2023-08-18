 <!--  -->
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
                    <h5 class="card-title mb-0 text-white">Distribucion de tabletas según UGEL al {{$fecha_texto}}</h5>
                </div>
                <div id="cardCollpase1" class="collapse show">
                    <div class="card-body">
                  
                    
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>                                                 
                                        <th rowspan="2">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                                        <th colspan="2" class="columna_centro">Total tabletas a distribuir</th>
                                        <th colspan="2" class="columna_centro">Tabletas despachadas</th>
                                        <th colspan="2" class="columna_centro">Tabletas en I.E <br> (Registro SIAGIE)</th>
                                        <th colspan="2" class="columna_centro">Tabletas asignadas <br> (Registro SIAGIE)</th>
                                    </tr>
                
                                    <tr>
                                        <th class="columna_centro">I.E</th>
                                        <th class="columna_centro">Tabletas</th>
                                        <th class="columna_centro">I.E</th>
                                        <th class="columna_centro">Tabletas</th>
                                        <th class="columna_centro">I.E</th>
                                        <th class="columna_centro">Tabletas</th>
                                        <th class="columna_centro">I.E</th>
                                        <th class="columna_centro">Tabletas</th>
                                    </tr>
                
                                </thead>
                                <tbody>
                                    @php
                                        $suma1=0;$suma2=0;$suma3=0;$suma4=0;$suma5=0; $suma6=0;$suma7=0; $suma8=0;
                                    @endphp
                                
                                @foreach ($resumen_tabletas_ugel as $item)
                                        
                                    @php
                                        $suma1+= $item->nroInstituciones_aDistribuir;   
                                        $suma2+= $item->total_aDistribuir;  
                                        $suma3+= $item->nroInstituciones_Despachado;  
                                        $suma4+= $item->total_Despachado;  
                                        $suma5+= $item->nroInstituciones_Recepcionadas;  
                                        $suma6+= $item->total_Recepcionadas;  
                                        $suma7+= $item->nroInstituciones_Asignadas;  
                                        $suma8+= $item->total_Asignadas;                                          
                                        // $sumMujer+= $item->mujeres
                                    @endphp
                
                                    <tr>                                            
                                        <td>{{$item->ugel}}</td>
                                        <td class="columna_derecha">{{number_format($item->nroInstituciones_aDistribuir,0)}}</td>
                                        <td class="columna_derecha">{{ number_format($item->total_aDistribuir,0) }} </td>
                                        
                                        <td class="columna_derecha">{{number_format($item->nroInstituciones_Despachado,0)}}</td>
                                        <td class="columna_derecha">{{number_format($item->total_Despachado,0)}}</td>
                
                                        <td class="columna_derecha">{{number_format($item->nroInstituciones_Recepcionadas,0)}}</td>
                                        <td class="columna_derecha">{{number_format($item->total_Recepcionadas,0)}}</td>
                
                                        <td class="columna_derecha">{{number_format($item->nroInstituciones_Asignadas,0)}}</td>
                                        <td class="columna_derecha">{{number_format($item->total_Asignadas,0)}}</td>
                                    </tr>
                
                                    @endforeach
                
                                    <tr> 
                                        <td> <b> TOTAL </b></td>
                                        <td class="columna_derecha_total"> {{number_format($suma1,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma2,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma3,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma4,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma5,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma6,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma7,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma8,0)}}  </td>
                                    </tr>                                              
                                    
                                </tbody>
                            </table>
                        </div>
                
                        <div >
                            <div  class="col-md-6">
                               <p class="texto_dfuente"> Fuente: SIAGIE- MINEDU  </p>        
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
            <!-- end card-->
        </div>
    </div>
</div>
<!-- FIN -->
   
   
   