


 <div class="col-md-12 col-xl-12" >
            <div class="media-body align-self-center">
                <div class="text-right">
                    <h4 class="font-26 my-0 font-weight-bold"><span data-plugin="counterup">{{number_format($totalMatriculados,0)}}</span></h4>
                    @if($tipoDescrip !='EBE')
                        <p class="card-title mb-0 mt-1 text-truncate">MATRICULADOS {{$tipoDescrip}} al {{$fecha_Matricula_texto}}(Inicial, Primaria y Secundaria)</p>                          
                    @else
                        <p class="card-title mb-0 mt-1 text-truncate">MATRICULADOS {{$tipoDescrip}} al {{$fecha_Matricula_texto}})</p>                  
                    @endif
                    
                </div>
            </div>
    <!-- end card-box-->
</div>                                 


 <div class="col-md-12" style="background-color: rgb(235, 235, 250)">


     <div class="row">
         
      @foreach ($lista_total_matricula_EBR as $item)                                   
                                
         <div class="col-md-6 col-xl-3" >
            <br>
             <div class="card text-center">
                 <div class="pricing-header {{$item->color}} p-4 rounded-top">
                     <h1 class="text-white font-44 font-weight-normal">{{number_format($item->hombres + $item->mujeres,0)}}  </h1>
                     <h5 class="text-white font-17 mt-4">{{$item->nombre}}</h5>
                    
                 </div>

                 <div>

                  
                    @if($tipoDescrip !='EBE')
                   
                        @foreach ($lista_total_matricula_EBR_porUgeles as $item2)
                        @if ($item->id == $item2->id)
                                <ul class="list-unstyled mb-0">                         
                                    <li class="mt-2 pt-1">Inicial {{number_format($item2->inicial,0)}}</li>
                                    <li class="mt-2 pt-1">Primaria {{number_format($item2->primaria,0)}} </li>
                                    <li class="mt-2 pt-1">Secundaria {{number_format($item2->secundaria,0)}} </li>                                  
                                </ul>
                                <br>
                        @endif
                        
                        @endforeach
                    @endif
                    

                     {{-- <div class="col-md-12 col-xl-12">
                          
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar {{$item->color}}" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 100%">                          
                            </div>
                        </div>
    
                    </div> --}}

                    <ul class="list-unstyled mb-0"> 
                        <li class="card-title mt-2 pt-3" > INSTITUCIONES EDUCATIVAS {{number_format($item->cantInstituciones,0)}} </li>                        
                    </ul>
                    <br>


                 </div>
             </div>
         </div>
         <!-- end col -->

         @endforeach
       
     </div>
     <!-- end row -->
     
 </div>
 <!-- end Col-12 -->
 <div  class="col-md-6">
   <br>
    Fuente: SIAGIE- MINEDU          
</div>


