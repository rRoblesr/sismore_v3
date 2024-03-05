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
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</head>

<body>

    <div id="target" class="k-group">Target</div>
<script>
    $(document).ready(function () {
        $("#target").kendoPopover({
            showOn: "click",
            width: "330px",
            position: "right",
            header: kendo.template($("#header-template").html()),
            body: kendo.template($("#body-template").html()),
        });
    });
</script>

<script id="header-template" type="text/x-kendo-template">
    <h1>Header</h1>
</script>

<script id="body-template" type="text/x-kendo-template">
    <div class="template-wrapper">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
</script> --}}


{{-- 
    <div class="row">
        <div class="col-sm-2">
            <div class="input-group">
                <input type="text" class="form-control jq-timePicker" value="09:30">
                <span class="input-group-addon" rel="popover">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var popoverTemplate = ['<div class="timePickerWrapper popover">',
                '<div class="arrow"></div>',
                '<div class="popover-content">',
                '</div>',
                '</div>'
            ].join('');

            var content = ['<div class="timePickerCanvas">asfaf asfsadf</div>',
                '<div class="timePickerClock timePickerHours">asdf asdfasf</div>',
                '<div class="timePickerClock timePickerMinutes"> asfa </div>',
            ].join('');


            $('body').popover({
                selector: '[rel=popover]',
                trigger: 'click',
                content: content,
                template: popoverTemplate,
                placement: "bottom",
                html: true
            });
        });
    </script> --}}

{{-- 


</body>

</html> --}}


{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}




{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}
{{--  --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Example of Creating Custom Template for Bootstrap 3 Popovers</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({
                html: true,
                template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Close</a></div></div>'
            });

            // Custom jQuery to hide popover on click of the close button
            $(document).on("click", ".popover-footer .btn", function() {
                $(this).parents(".popover").popover('hide');
            });
        });
    </script>
    <style type="text/css">
        .bs-example {
            margin: 150px 50px;
        }

        /* Styles for custom popover template */
        .popover-footer {
            padding: 6px 14px;
            background-color: #f7f7f7;
            border-top: 1px solid #ebebeb;
            text-align: right;
        }
    </style>
</head>

<body>
    <div>
        <button type="button" class="btn btn-primary btn-lg" data-toggle="popover" title="Custom Popover Template"
            data-content="A simple example of a customized Bootstrap popover that displays a footer with close button on every popover without adding any extra markup to the popover HTML code.">Customized
            Popover</button>
    </div>
</body>

</html>
