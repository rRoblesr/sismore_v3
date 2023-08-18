@extends('layouts.main',['titlePage'=>''])

@section('content') 

<div class="row">
    <div class="col-md-12 col-xl-12">
       <div class="card-box">     
           <h4>DIRECCION REGIONAL DE TRABAJO Y PROMOCION DEL EMPLEO DE UCAYALI</h4>
           <h5>RESUMEN ESTADISTICO MENSUAL - 2022</h5>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card card-border card-primary"> 
            <div class="card-body">

                <div class="card-header border-primary bg-transparent p-0">                
                </div>

                <div class="table-responsive">
                    <table style="width: 100%;" border="1px solid #000" >
                        
                        <thead>
                            <tr>
                                <th class="titulo_tabla" rowspan="2">Item</th>     
                                <th class="titulo_tabla" rowspan="2">Actividad</th>               
                                <th class="titulo_tabla" rowspan="2">Unidad de Medida</th>
                                <th class="titulo_tabla" rowspan="2">Meta Anual</th>
                                <th class="titulo_tabla" colspan="12">Mes-Periodo</th>
                                <th class="titulo_tabla" rowspan="2">Porcentaje</th>
                                <th class="titulo_tabla" rowspan="2">Acumulado</th>
                            </tr>
                            <tr>                                
                                <th class="titulo_tabla" >Ene.</th>
                                <th class="titulo_tabla" >Feb.</th>
                                <th class="titulo_tabla" >Mar.</th>
                                <th class="titulo_tabla" >Abr.</th>
                                <th class="titulo_tabla" >May.</th>
                                <th class="titulo_tabla" >Jun.</th>
                                <th class="titulo_tabla" >Jul.</th>
                                <th class="titulo_tabla" >Ago.</th>
                                <th class="titulo_tabla" >Set.</th>
                                <th class="titulo_tabla" >Oct.</th>
                                <th class="titulo_tabla" >Nov.</th>
                                <th class="titulo_tabla" >Dic.</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($direcciones as $item)
                                <tr>                                            
                                    <td class="titulo_tabla_izq" colspan="18">{{$item->direccion}} </td>
                                </tr>  

                                @foreach ($actividades as $item4)

                                    @if($item->Direccion_id == $item4->direccion_id)
                                        <tr>             
                                            <td class="titulo_tabla_izq"  > </td>                               
                                            {{-- <td class="fila_tabla" colspan="11">{{$item4->actividad}}  </td> --}}
                                            @if($item4->esSubTitulo)                            
                                                <td class="titulo_tabla_izq" colspan="1">{{$item4->actividad}}  </td>
                                            @else
                                                <td class="fila_tabla" colspan="1">{{$item4->actividad}}  </td>
                                            @endif

                                            <td class="titulo_tabla" colspan="1"> {{$item4->uniMed}}</td>
                                            <td class="titulo_tabla" colspan="1"> {{$item4->valor}}</td>

                                         
                                            @foreach ($Actividad_Resultado as $item5)

                                                @if($item5->actividad_id == $item4->actividad_id)
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M1}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M2}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M3}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M4}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M5}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M6}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M7}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M8}}</td>  

                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M9}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M10}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M11}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M12}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->M7}}</td>
                                                    <td class="titulo_tabla" colspan="1"> {{$item5->Acumulado}}</td>
                                                @else   
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>   
                                                    
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                    <td class="titulo_tabla" colspan="1"> </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach


                                @foreach ($subDirecciones as $item2)

                                    @if($item->Direccion_id == $item2->dependencia)
                                        <tr>             
                                            <td class="titulo_tabla_izq"  >  </td>                               
                                            <td class="titulo_tabla_izq" colspan="17">{{$item2->direccion}} </td>
                                        </tr>

                                        @foreach ($actividades as $item3)

                                            @if($item2->Direccion_id == $item3->direccion_id)
                                                <tr>             
                                                    <td class="titulo_tabla_izq"  >  </td>  
                                                   
                                                    @if($item3->esSubTitulo)                            
                                                        <td class="titulo_tabla_izq" colspan="11">{{$item3->actividad}}  </td>
                                                    @else
                                                        <td class="fila_tabla" colspan="17">{{$item3->actividad}}  </td>
                                                    @endif
                                                </tr>
                                            @endif
                                            
                                        @endforeach

                                    @endif                                    

                                @endforeach
                                
                            @endforeach

                            <tr>                                            
                                <td class="titulo_tabla_izq" colspan="18"> -------------</td>
                            </tr>
                            <tr>                                            
                                <td class="titulo_tabla_izq" colspan="18"> -------------</td>
                            </tr>
                            <tr>                                            
                                <td class="titulo_tabla_izq" colspan="18"> -------------</td>
                            </tr>

                            <tr>                                            
                                <td class="titulo_tabla_izq" colspan="18">DIRECCION DE PROMOCION DEL EMPLEO Y CAPACITACION LABORAL </td>
                            </tr>  
                        
                            <tr>       
                                <td class="titulo_tabla">1</td>                                     
                                <td class="fila_tabla">Acercamiento Empresarial (empresas visitadas) </td>                    
                                <td class="titulo_tabla">Empresa</td>
                                <td class="titulo_tabla">120</td>
                                <td class="titulo_tabla">25</td>
                                <td class="titulo_tabla">23</td>                    
                                <td class="titulo_tabla">30</td>
                                <td class="titulo_tabla">18</td>
                                <td class="titulo_tabla">19</td>
                                <td class="titulo_tabla">19</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        111.66 %
                                    </div>
                                </td>   
                                <td class="titulo_tabla">134</td>                                 
                            </tr>        

                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div> 
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xl-12">

        <div class="card card-border card-primary"> 
            <div class="card-body">

                <div class="card-header border-primary bg-transparent p-0">
                
                </div>
                
                <div class="table-responsive">
                    <table style="width: 100%;" border="1px solid #000" >
                        <thead>
                            <tr>
                                <th class="titulo_tabla" rowspan="2">Item</th>     
                                <th class="titulo_tabla" rowspan="2">Actividad</th>               
                                <th class="titulo_tabla" rowspan="2" >Unidad de Medida</th>
                                <th class="titulo_tabla" rowspan="2" >Meta Anual</th>
                                <th class="titulo_tabla" colspan="6" >Mes-Periodo</th>

                                <th class="titulo_tabla" rowspan="2" >Porcentaje</th>
                                <th class="titulo_tabla" rowspan="2" >Acumulado</th>
                            </tr>
            
                            <tr>                                
                                <th class="titulo_tabla" >Enero</th>
                                <th class="titulo_tabla" >Febrero</th>
                                <th class="titulo_tabla" >Marzo</th>
                                <th class="titulo_tabla" >Abril</th>
                                <th class="titulo_tabla" >Mayo</th>
                                <th class="titulo_tabla" >Junio</th>
                            </tr>
                            
                        
                        </thead>

                        <tbody>

                            <tr>                                            
                                <td class="titulo_tabla_izq" colspan="12">DIRECCION DE PROMOCION DEL EMPLEO Y CAPACITACION LABORAL </td>
                            </tr>  
                        
                            <tr>       
                                <td class="titulo_tabla">1</td>                                     
                                <td class="fila_tabla">Acercamiento Empresarial (empresas visitadas) </td>                    
                                <td class="titulo_tabla">Empresa</td>
                                <td class="titulo_tabla">120</td>
                                <td class="titulo_tabla">25</td>
                                <td class="titulo_tabla">23</td>                    
                                <td class="titulo_tabla">30</td>
                                <td class="titulo_tabla">18</td>
                                <td class="titulo_tabla">19</td>
                                <td class="titulo_tabla">19</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        111.66 %
                                    </div>
                                </td>   
                                <td class="titulo_tabla">134</td>  
                                
                                
                            </tr>           
                            
                            <tr>       
                                <td class="titulo_tabla">2</td>                                     
                                <td class="titulo_tabla_izq">Bolsa de trabajo </td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla" colspan="9"></td>
                            </tr> 
                            
                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Ofertas (buscadores de empleo)</td>    
                                <td class="titulo_tabla"></td>                
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">98</td>
                                <td class="titulo_tabla">90</td>
                                <td class="titulo_tabla">90</td>                    
                                <td class="titulo_tabla">110</td>
                                <td class="titulo_tabla">150</td>
                                <td class="titulo_tabla">180</td>
                          
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">718</td>                        
                            </tr>
                            
                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Vacantes</td>    
                                <td class="titulo_tabla"></td>                
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">84</td>
                                <td class="titulo_tabla">102</td>
                                <td class="titulo_tabla">164</td>                    
                                <td class="titulo_tabla">100</td>
                                <td class="titulo_tabla">129</td>
                                <td class="titulo_tabla">168</td>
                          
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">747</td>                        
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Colocados</td>    
                                <td class="titulo_tabla"></td>                
                                <td class="titulo_tabla">1650</td>
                                <td class="titulo_tabla">50</td>
                                <td class="titulo_tabla">68</td>
                                <td class="titulo_tabla">84</td>                    
                                <td class="titulo_tabla">83</td>
                                <td class="titulo_tabla">112</td>
                                <td class="titulo_tabla">155</td>
                          
                                <td class="titulo_tabla">  
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="33.45" aria-valuemin="0" aria-valuemax="33.45" style="width: 100%">
                                        33.45 %
                                    </div>
                                </td>
                                <td class="titulo_tabla">552</td>                        
                            </tr>
                      
                            <tr>       
                                <td class="titulo_tabla">3</td>                                     
                                <td class="fila_tabla">Colocados de personas con discapacidad</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">1</td>                    
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla"></td>   
                                <td class="titulo_tabla">2</td>                     
                            </tr> 

                            <tr>       
                                <td class="titulo_tabla">4</td>                                     
                                <td class="fila_tabla">Certificado Unico Laboral (CUL)</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">3,157</td>
                                <td class="titulo_tabla">1,928</td>
                                <td class="titulo_tabla">1,298</td>                    
                                <td class="titulo_tabla">2,392</td>
                                <td class="titulo_tabla">2,105</td>
                                <td class="titulo_tabla">2,153</td>
                                <td class="titulo_tabla">1,950</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        374.59 %
                                    </div>    
                                </td>   
                                <td class="titulo_tabla">11,826</td>                     
                            </tr> 

                            <tr>       
                                <td class="titulo_tabla">5</td>                                     
                                <td class="fila_tabla">Asesoria en Busqueda de Empleo (ABE)</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">2,400</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>                    
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>   
                                <td class="titulo_tabla">0</td>                     
                            </tr> 

                            <tr>       
                                <td class="titulo_tabla">6</td>                                     
                                <td class="fila_tabla">Servicio de Orientacion Vocacional (SOVIO)</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">3,766</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>                    
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>   
                                <td class="titulo_tabla">0</td>                     
                            </tr> 

                            

                            <tr>       
                                <td class="titulo_tabla">7</td>                                     
                                <td class="titulo_tabla_izq">Formaliza Perú</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla" colspan="9"></td>
                            </tr> 

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Empleadores</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">84</td>
                                <td class="titulo_tabla">107</td>
                                <td class="titulo_tabla">63</td>                    
                                <td class="titulo_tabla">50</td>
                                <td class="titulo_tabla">39</td>
                                <td class="titulo_tabla">12</td>
                                <td class="titulo_tabla">43</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        373.80 %
                                    </div>      
                                </td>   
                                <td class="titulo_tabla">314</td>                     
                            </tr> 

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Emprendedores</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">60</td>
                                <td class="titulo_tabla">30</td>
                                <td class="titulo_tabla">102</td>                    
                                <td class="titulo_tabla">131</td>
                                <td class="titulo_tabla">90</td>
                                <td class="titulo_tabla">147</td>
                                <td class="titulo_tabla">346</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        1410 %
                                    </div>      
                                </td>   
                                <td class="titulo_tabla">846</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Empleadores del hogar</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">12</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">1</td>                    
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        33.33 %
                                    </div>      
                                </td>   
                                <td class="titulo_tabla">4</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Capacitaciones</td>                    
                                <td class="titulo_tabla">Persona</td>
                                <td class="titulo_tabla">4</td>
                                <td class="titulo_tabla">5</td>
                                <td class="titulo_tabla">2</td>                    
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">5</td>
                                <td class="titulo_tabla">5</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        525 %
                                    </div>      
                                </td>   
                                <td class="titulo_tabla">21</td>                     
                            </tr>


                            <tr>                                            
                                <td class="titulo_tabla_izq" colspan="12">DIRECCION DE PREVENCION Y SOUCION DE CONFLICTOS LABORALES </td>
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">1</td>                                     
                                <td class="fila_tabla">Resolución de primera instancia</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">9</td>
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla">1</td>                    
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        88.88 %
                                    </div>  
                                </td>   
                                <td class="titulo_tabla">8</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">2</td>                                     
                                <td class="fila_tabla">Auto primera instancia</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">37</td>
                                <td class="titulo_tabla">4</td>
                                <td class="titulo_tabla">3</td>                    
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla">11</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">6</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        78.37 %
                                    </div>    
                                </td>   
                                <td class="titulo_tabla">29</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">3</td>                                     
                                <td class="fila_tabla">Registro de empresas proveedoras de alimentos</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>                    
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla"></td>   
                                <td class="titulo_tabla">2</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">4</td>                                     
                                <td class="fila_tabla">Conciliacion de sindicato</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">0</td>                    
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla"></td>   
                                <td class="titulo_tabla">5</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">5</td>                                     
                                <td class="fila_tabla">Extraprocesos</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">1</td>                    
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla"></td>   
                                <td class="titulo_tabla">9</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">6</td>                                     
                                <td class="fila_tabla">Proveidos</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>                    
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla"></td>   
                                <td class="titulo_tabla">3</td>                     
                            </tr>


                            <tr>             
                                <td class="titulo_tabla_izq"  >  </td>                               
                                <td class="titulo_tabla_izq" colspan="11">SUB DIRECCION DE DEFENSA LEGAL GRATUITA</td>
                            </tr>

                            <tr>       
                                <td class="titulo_tabla">1</td>                                     
                                <td class="titulo_tabla_izq">Consultas</td>                    
                                <td class="titulo_tabla">usuarios</td>
                                <td class="titulo_tabla">950</td>
                                <td class="titulo_tabla">87</td>
                                <td class="titulo_tabla">103</td>                    
                                <td class="titulo_tabla">97</td>
                                <td class="titulo_tabla">72</td>
                                <td class="titulo_tabla">92</td>
                                <td class="titulo_tabla">110</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        59.05 %
                                    </div>    
                                </td>   
                                <td class="titulo_tabla">561</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Empleador</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>                    
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla"> </td>   
                                <td class="titulo_tabla">2</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Trabajador</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">87</td>
                                <td class="titulo_tabla">103</td>                    
                                <td class="titulo_tabla">95</td>
                                <td class="titulo_tabla">72</td>
                                <td class="titulo_tabla">92</td>
                                <td class="titulo_tabla">100</td>
                                <td class="titulo_tabla"> </td>   
                                <td class="titulo_tabla">559</td>                     
                            </tr>


                            <tr>       
                                <td class="titulo_tabla">2</td>                                     
                                <td class="titulo_tabla_izq">Conciliaciones</td>                    
                                <td class="titulo_tabla">usuarios</td>
                                <td class="titulo_tabla">500</td>
                                <td class="titulo_tabla">8</td>
                                <td class="titulo_tabla">11</td>                    
                                <td class="titulo_tabla">4</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">13</td>
                                <td class="titulo_tabla">12</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        10 %
                                    </div>    
                                </td>   
                                <td class="titulo_tabla">50</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Conciliadas</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">200</td>
                                <td class="titulo_tabla">7</td>
                                <td class="titulo_tabla">8</td>                    
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">10</td>
                                <td class="titulo_tabla">9</td>
                                <td class="titulo_tabla"> 
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        18 %
                                    </div>       
                                </td>   
                                <td class="titulo_tabla">36</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">Monto conciliado</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">N/D</td>
                                <td class="titulo_tabla">36,161.14</td>
                                <td class="titulo_tabla">10,015.69</td>                    
                                <td class="titulo_tabla">20,006.85</td>
                                <td class="titulo_tabla">0</td>
                                <td class="titulo_tabla">25,290.32</td>
                                <td class="titulo_tabla">17,964.76</td>
                                <td class="titulo_tabla"> </td>   
                                <td class="titulo_tabla">109,439</td>                     
                            </tr>

                            <tr>       
                                <td class="titulo_tabla"></td>                                     
                                <td class="fila_tabla">No conciliadas</td>                    
                                <td class="titulo_tabla"></td>
                                <td class="titulo_tabla">150</td>
                                <td class="titulo_tabla">1</td>
                                <td class="titulo_tabla">3</td>                    
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">2</td>
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla">3</td>
                                <td class="titulo_tabla">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="111" aria-valuemin="0" aria-valuemax="111" style="width: 100%">
                                        9.33 %
                                    </div>       
                                </td>   
                                <td class="titulo_tabla">14</td>                     
                            </tr>

                        </tbody>
                        
                    </table>
                </div>

                <br>

            </div>
        </div>
    </div>
</div> 
@endsection


