@extends('layouts.main', ['titlePage' => ''])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <iframe title="REPORTE DE EJECUCIÓN DE GASTOS - EDUCACIÓN - PLIEGO" width="100%" height="650" src="https://app.powerbi.com/view?r=eyJrIjoiMGZmNzBkZGYtNzk1OC00ZTUzLWJmZTQtOTEyZDkzMTdlZjM3IiwidCI6ImJmZTc3ZTYwLWEwY2UtNGI4Yi1hMjc5LWQ2NTQxNTA2MzU1MSJ9" frameborder="0" allowFullScreen="true"></iframe> -->
                        <!-- <iframe title="REPORTE DE EJECUCIÓN DE GASTOS - EDUCACIÓN" width="1024" height="1060" src="https://app.powerbi.com/view?r=eyJrIjoiMGZmNzBkZGYtNzk1OC00ZTUzLWJmZTQtOTEyZDkzMTdlZjM3IiwidCI6ImJmZTc3ZTYwLWEwY2UtNGI4Yi1hMjc5LWQ2NTQxNTA2MzU1MSJ9&pageName=ReportSectionec8a37132a747f004fb7" frameborder="0" allowFullScreen="true"></iframe> -->
                            <iframe title="REPORTE DE EJECUCIÓN DE GASTOS - EDUCACIÓN" width="100%" height="650" src="https://app.powerbi.com/view?r=eyJrIjoiY2E1NGIyOWUtYjA2ZS00ZDNmLWFhNTYtMDc0MjgzMmM0ODg4IiwidCI6ImMwZTJmMGQzLTk3MDctNGRiZS1iYWE3LWJjYzg3MGZjNTZhMCJ9&pageName=ReportSectiondd2633a69e444565b835" frameborder="0" allowFullScreen="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection
