

<div class="card-header card-header-primary">       
    <h2 class="card-title">ProEmpleo Por Variables del Mercado de Trabajo {{$anio}}</h2>
</div>  

<div class="card card-border card-primary"> 
    <div class="card-body">
        
        <div class="card-header border-primary bg-transparent p-0">
            <h3 class="card-title text-primary "></h3>
        </div>
        
        <div class="table-responsive">
            <table style="width: 100%;" border="1px solid #000" >
                <thead>
                    <tr>
                        <th colspan="1" rowspan="3" class="titulo_tabla">MESES</th>                                        
                        <th colspan="5" class="titulo_tabla">VARIABLES</th>
                    </tr>

                    <tr>
                        <th class="titulo_tabla" colspan="2"> OFERTA</th>
                        <th class="titulo_tabla" colspan="1" rowspan="2"> DEMANDA</th>
                        <th class="titulo_tabla" colspan="2"> COLOCADOS</th>
                    </tr>
    
                    <tr>                   
                        <th class="titulo_tabla">H</th>
                        <th class="titulo_tabla">M</th>                        
                        <th class="titulo_tabla">H</th>
                        <th class="titulo_tabla">M</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $item)
                        <tr>                                            
                            <td class="titulo_tabla">{{$item->nombreMes}}</td>
                            <td class="titulo_tabla">{{number_format($item->oferta_hombres,0) }} </td>
                            <td class="titulo_tabla">{{number_format($item->oferta_mujeres,0)}}</td>
                            <td class="titulo_tabla">{{number_format($item->demanda,0)}}</td>
                            <td class="titulo_tabla">{{number_format($item->cantColocadosM,0)}}</td>
                            <td class="titulo_tabla">{{number_format($item->cantColocadosF,0)}}</td> 
                        </tr>                       
                    @endforeach
                </tbody>
                
            </table>
        </div>

        <br>

        <div class="form-group row">
            <div  class="col-md-6">
                Fuente: DRPE - UCAYALI         
            </div>
    
            <div  class="col-md-6" style="text-align: right">
                H: Hombre / M: Mujer   
            </div>
        </div>                             
    </div>
</div>