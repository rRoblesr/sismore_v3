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

{{-- <!DOCTYPE html>
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
 --}}

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
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> --}}
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}

    {{-- <script type="text/javascript"></script> --}}
    <style type="text/css">
        /*** This page styles (unnecessary) ***/
        body {
            color: #333;
            font-weight: normal;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, Sans serif;
            background: url(https://josetxu.com/img/fondo_blanco.png) repeat 0 0 #fff;
        }

        h1 {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 0.5em;
            margin: 0 0 2em 0;
        }

        h2 {
            font-weight: normal;
            text-align: center;
            color: #777;
        }

        form {
            max-width: 980px;
            margin: 0 auto;
        }

        .content {
            width: 50%;
            padding: 1em;
            box-sizing: border-box;
            float: left;
        }

        fieldset {
            padding: 2em;
            border-color: #d4d4d4;
            border-style: solid;
            border-width: 2px;
            background: #fff;
        }

        legend {
            color: #bfbfbf;
            padding: 0.3em 0.6em;
            border: 2px solid #d4d4d4;
            background: #fff;
            font-size: 1.3em;
        }

        legend.radio {
            border-radius: 50px;
        }

        .container ul {
            margin-top: 0;
            padding-left: 0em;
        }

        .container ul li {
            list-style-type: none;
        }

        .container ul+ul {
            margin-bottom: 0;
        }

        .container ul+ul>li+li label {
            margin-bottom: 0;
        }

        /*** Styling Radio & Checkbox Input Fields (start here) ***/
        label {
            font-weight: 600;
            color: #777777;
            margin-bottom: 11px;
            width: 100%;
            float: left;
            cursor: pointer;
            padding: 0 0.6em;
            box-sizing: border-box;
            background: #e6e6e6;
            transition: all 0.5s ease 0s;
        }

        input[type="radio"],
        input[type="checkbox"] {
            display: none;
        }

        input[type="radio"]+label,
        input[type="checkbox"]+label {
            line-height: 3em;
        }

        input[type="radio"]+label {
            border-radius: 50px;
        }

        input[type="radio"]:disabled+label,
        input[type="checkbox"]:disabled+label {
            color: #ccc !important;
            cursor: not-allowed;
        }

        input[type="radio"]:checked:disabled+label:after,
        input[type="checkbox"]:checked:disabled+label:after {
            border-color: #ccc;
        }

        input[type="radio"]+label:before,
        input[type="checkbox"]+label:before {
            content: "";
            width: 26px;
            height: 26px;
            float: left;
            margin-right: 0.5em;
            border: 2px solid #ccc;
            background: #fff;
            margin-top: 0.5em;
        }

        input[type="radio"]+label:before {
            border-radius: 100%;
        }

        input[type="radio"]:checked+label,
        input[type="checkbox"]:checked+label {
            background: #c1eec2;
        }

        input[type="radio"]:checked+label:after {
            content: "";
            width: 0;
            height: 0;
            border: 7px solid #0fbf12;
            float: left;
            margin-left: -1.85em;
            margin-top: 1em;
            border-radius: 100%;
        }

        input[type="checkbox"]:checked+label:after {
            content: "";
            width: 12px;
            height: 6px;
            border: 4px solid #0fbf12;
            float: left;
            margin-left: -1.95em;
            border-right: 0;
            border-top: 0;
            margin-top: 1em;
            transform: rotate(-55deg);
        }

        input[type="radio"]:checked+label:before,
        input[type="checkbox"]:checked+label:before {
            border-color: #0fbf12;
        }

        input[type="radio"]:checked:disabled+label,
        input[type="checkbox"]:checked:disabled+label {
            background: #ccc;
            color: #fff !important;
        }

        input[type="radio"]:checked:disabled+label:before,
        input[type="checkbox"]:checked:disabled+label:before {
            border-color: #bdbdbd;
        }

        @media (max-width: 650px) {
            .content {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <h1>Styling Radio & Checkbox Input Fields</h1>

    <h2>Create custom styles for inputs with CSS only</h2>

    <form>
        <div class="content">
            <fieldset>
                <legend class="radio">Radio</legend>
                <div class="container">
                    <ul>
                        <li>
                            <input type="radio" id="radio1" name="radio01">
                            <label for="radio1">Empty</label>
                        </li>
                        <li>
                            <input type="radio" id="radio2" name="radio01" checked="checked">
                            <label for="radio2">Checked</label>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <input type="radio" id="radio3" name="radio02" disabled="disabled">
                            <label for="radio3">Empty Disabled</label>
                        </li>
                        <li>
                            <input type="radio" id="radio4" name="radio02" disabled="disabled" checked="checked">
                            <label for="radio4">Checked Disabled</label>
                        </li>
                    </ul>
                </div>
            </fieldset>
        </div>

        <div class="content">
            <fieldset>
                <legend>Checkbox</legend>
                <div class="container">
                    <ul>
                        <li>
                            <input type="checkbox" id="checkbox1" name="checkbox01">
                            <label for="checkbox1">Empty</label>
                        </li>
                        <li>
                            <input type="checkbox" id="checkbox2" name="checkbox01" checked="checked">
                            <label for="checkbox2">Checked</label>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <input type="checkbox" id="checkbox3" name="checkbox02" disabled="disabled">
                            <label for="checkbox3">Disabled</label>
                        </li>
                        <li>
                            <input type="checkbox" id="checkbox4" name="checkbox02" disabled="disabled"
                                checked="checked">
                            <label for="checkbox4">Checked Disabled</label>
                        </li>
                    </ul>
                </div>
            </fieldset>
        </div>
    </form>
</body>

</html>
