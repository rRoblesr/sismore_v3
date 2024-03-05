<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />


<div class="tooltip-main" data-toggle="tooltip" data-placement="top" data-original-title="Hello world"><span
        class="tooltip-qm">?</span></div>
<style>
    .bs-tooltip-auto[x-placement^=bottom] .arrow::before,
    .bs-tooltip-bottom .arrow::before {
        border-bottom-color: #f00;
        /* Red */
    }


    .tooltip-main {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        font-weight: 700;
        background: #f3f3f3;
        border: 1px solid #737373;
        color: #737373;
        margin: 4px 121px 0 5px;
        float: right;
        text-align: left !important;
    }

    .tooltip-qm {
        float: left;
        margin: -2px 0px 3px 4px;
        font-size: 12px;
    }

    .tooltip-inner {
        max-width: 236px !important;
        height: 76px;
        font-size: 12px;
        padding: 10px 15px 10px 20px;
        background: #FFFFFF;
        color: rgba(0, 0, 0, .7);
        border: 1px solid #737373;
        text-align: left;
    }

    .tooltip.show {
        opacity: 1;
    }

    .bs-tooltip-auto[x-placement^=bottom] .arrow::before,
    .bs-tooltip-bottom .arrow::before {
        border-bottom-color: #f00;
        /* Red */
    }
</style>

<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
