

<div class="row">
    <div class="col-md-6 col-xl-6">

        <div class="card card-border card-primary"> 
            <div class="card-body">
                
                <h3 class="card-title text-secundary ">Ingreso promedio mensual</h3>
                <h3 class="card-title text-secundary ">Población Economicamente Activa - PEA</h3>
                <br>

                <div class="card-header border-primary bg-transparent p-0">
                
                </div>
                
                <div class="table-responsive">
                    <table style="width: 100%;" border="1px solid #000" >
                        <thead>
                            <tr>
                                <th  class="titulo_tabla">AÑO</th>               
                                <th class="titulo_tabla" > Masculina</th>
                                <th class="titulo_tabla" > Femenina</th>
                                <th class="titulo_tabla" > Total</th>
                            </tr>
            
                        
                        </thead>

                        <tbody>
                            @foreach ($dataPea_IPM as $item)
                                <tr>                                            
                                    <td class="titulo_tabla">{{$item->anio}}</td>
                    
                                    <td class="titulo_tabla">{{number_format($item->Masculino,0)}}</td>
                                    <td class="titulo_tabla">{{number_format($item->Femenino,0)}}</td>
                                    <td class="titulo_tabla">{{number_format($item->total,0)}}</td>
                            
                                </tr>                       
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>

                <br>
{{-- 
                <div class="form-group row">
                    <div  class="col-md-12">
                    Fuente: INEI - Encuesta Nacional de Hogares sobre Condiciones de Vida y Pobreza        
                    </div>
                
                </div>                              --}}
            </div>
        </div>
    </div>



</div> 

<div class="row">

    <div class="col-md-12 col-xl-12">
        <div class="alert alert-info">                                                          
            <div id="Grafico_PEA_IPM">       
                {{-- se carga con el scrip lineas abajo --}}
            </div>                     
        </div>    
    </div> 

</div> 