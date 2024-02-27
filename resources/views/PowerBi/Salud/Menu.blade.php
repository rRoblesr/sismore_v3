@extends('layouts.main', ['titlePage' => ''])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-body">
                    <iframe width="100%" style="height: 80vh;" src="{{ $link }}" frameborder="0"
                        allowFullScreen="true"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {});
    </script>
@endsection
