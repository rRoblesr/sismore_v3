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
                    <h5 class="card-title mb-0 text-white">Distribucion de Textos Escolares</h5>
                </div>
                <div id="cardCollpase1" class="collapse show">
                    <div class="card-body">
                  
                    
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>                                                 
                                        <th rowspan="2">BENEFICIARIO</th>
                                        <th colspan="5" class="columna_centro">CANTIDAD ENTREGADA SEGÚN</th>
                                       
                                    </tr>
                
                                    <tr>
                                        <th class="columna_centro">DREU</th>
                                        <th class="columna_centro">UGEL CORONEL PORTILLO</th>
                                        <th class="columna_centro">UGEL ATALAYA</th>
                                        <th class="columna_centro">PADRE ABAD</th>
                                        <th class="columna_centro">PURUS</th>
                                                                    
                                    </tr>
                
                                </thead>
                                <tbody>
                                    @php
                                        $suma1=0;$suma2=0;$suma3=0;$suma4=0;$suma5=0;
                                    @endphp
                                
                                @foreach ($data as $item)
                                        
                                    @php
                                        $suma1+= $item->cantDRE;   
                                        $suma2+= $item->cantAtalaya;  
                                        $suma3+= $item->cantCoronelPortillo;  
                                        $suma4+= $item->cantPadreAbad;  
                                        $suma5+= $item->cantPurus;                                        
                                    @endphp
                
                                    <tr>                                            
                                        <td>{{$item->beneficiario}}</td>
                                        <td class="columna_derecha">{{number_format($item->cantDRE,0)}}</td>
                                        <td class="columna_derecha">{{ number_format($item->cantAtalaya,0) }} </td>                                        
                                        <td class="columna_derecha">{{number_format($item->cantCoronelPortillo,0)}}</td>
                                        <td class="columna_derecha">{{number_format($item->cantPadreAbad,0)}}</td>                
                                        <td class="columna_derecha">{{number_format($item->cantPurus,0)}}</td>
                                    </tr>
                
                                    @endforeach
                
                                    <tr> 
                                        <td> <b> TOTAL </b></td>
                                        <td class="columna_derecha_total"> {{number_format($suma1,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma2,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma3,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma4,0)}}  </td>
                                        <td class="columna_derecha_total"> {{number_format($suma5,0)}}  </td>                          
                                    </tr>                                              
                                    
                                </tbody>
                            </table>
                        </div>
                
                        <div >
                            <div  class="col-md-6">
                               <p class="texto_dfuente"> Fuente: SIGA- MINEDU  </p>        
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
   
   
   