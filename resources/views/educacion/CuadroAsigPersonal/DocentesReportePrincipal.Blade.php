<div class="col-md-12">
    <h5 class="subtitulo_Indicadores mb-1"> DOCENTES POR UGELES </h5>
</div>
<div class="col-md-4 text-right">
    <div class="progress progress-sm m-0">
        <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="60" aria-valuemin="0"
            aria-valuemax="100" style="width: 100%"></div>
    </div>
</div>

<div class="col-md-8 text-right">
    <p class="texto_dfuente  mb-0"> </p>
</div>

<div class="col-md-4 ">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="titulo_tabla">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                    <th class="titulo_tabla">TOTAL </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $suma = 0;
                @endphp

                @foreach ($plazas_porTipoTrab as $item)
                    @php
                        $suma += $item->cantidad;
                    @endphp

                    <tr>
                        <td class="fila_tabla">{{ $item->ugel }}</td>
                        <td class="columna_derecha fila_tabla">{{ number_format($item->cantidad, 0) }} </td>
                    </tr>
                @endforeach

                <tr>
                    <td class="columna_derecha_total fila_tabla"> <b> TOTAL </b></td>
                    <td class="columna_derecha_total fila_tabla"> {{ number_format($suma, 0) }} </td>

                </tr>

            </tbody>
        </table>
    </div>

</div>

<div class="col-md-8 text-right">
    <div id="barra1">

    </div>
</div>

<div class="col-md-12 ">
    <div class="progress progress-sm m-0">
        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
            style="width: 100%"></div>
    </div>
</div>

<div class="col-md-12 ">
    <br>
    <div id="barra2">

    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        cargar_GraficoBarra();
        cargar_GraficoBarra2();
    });


    function cargar_GraficoBarra() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            },
            url: "{{ url('/') }}/CuadroAsigPersonal/Docentes/GraficoBarras_DocentesPrincipal/" + 1 + "/" +
                $('#fechas').val(),
            type: 'post',
        }).done(function(data) {
            $('#barra1').html(data);
        }).fail(function() {
            alert("Lo sentimos a ocurrido un error");
        });
    }

    function cargar_GraficoBarra2() {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            },
            url: "{{ url('/') }}/CuadroAsigPersonal/Docentes/GraficoBarras_DocentesNivelEducativo/" + 1 +
                "/" + $('#fechas').val(),
            type: 'post',
        }).done(function(data) {
            $('#barra2').html(data);
        }).fail(function() {
            alert("Lo sentimos a ocurrido un error");
        });
    }
</script>
