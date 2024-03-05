{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container">
        <h3>Tooltip Example</h3>
        <a href="#" data-toggle="tooltip" title="Hooray!">Hover over me</a>
    </div>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<div class="row">

    <!-- Popovers -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Popovers</h3>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <button type="button" class="btn btn-secondary" data-container="body" title=""
                        data-toggle="popover" data-placement="top"
                        data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." data-original-title="">
                        Popover on top
                    </button>

                    <button type="button" class="btn btn-secondary" data-container="body" title=""
                        data-toggle="popover" data-placement="bottom"
                        data-content="Vivamus
                             sagittis lacus vel augue laoreet rutrum faucibus."
                        data-original-title="">
                        Popover on bottom
                    </button>

                    <button type="button" class="btn btn-secondary" data-container="body" title=""
                        data-toggle="popover" data-placement="right"
                        data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." data-original-title="">
                        Popover on right
                    </button>

                    <button type="button" class="btn btn-secondary" data-container="body" title=""
                        data-toggle="popover" data-placement="left"
                        data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."
                        data-original-title="Popover title">
                        Popover on left
                    </button>

                    <button type="button" tabindex="0" class="btn btn-secondary" data-toggle="popover"
                        data-trigger="focus" title=""
                        data-content="And here's some amazing content. It's very engaging. Right?"
                        data-original-title="Dismissible popover" data-placement="top">Dismissible popover
                    </button>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- card -->
    </div>

    <!-- Tooltips -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tooltips</h3>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left"
                        title="Tooltip on left">Tooltip on left</button>

                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top"
                        title="" data-original-title="Tooltip on top">Tooltip on top</button>

                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom"
                        title="" data-original-title="Tooltip on bottom">Tooltip on bottom</button>

                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="right"
                        title="" data-original-title="Tooltip on right">Tooltip on right</button>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<body>

    <div class="container">
        <h3>Tooltip Example</h3>
        <a href="#" data-toggle="tooltip" title="Hooray!">Hover over me</a>

        <span data-toggle="tooltip" title="" data-content="And here's some amazing content. It's very engaging. Right?"
        data-original-title="Dismissible popover">Hover over me</span>+

        <span data-toggle="popover" data-trigger="focus" title=""
            data-content="And here's some amazing content. It's very engaging. Right?"
            data-original-title="Dismissible popover"></span>
    </div>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
        });
    </script>

</body>

</html>
