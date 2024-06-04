<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Example</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- FixedHeader CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css">
    <!-- Custom CSS -->
    <style>
        table.dataTable th {
            width: 150px;
            /* Adjust the width as needed */
            white-space: nowrap;
            /* Prevent text from overflowing */
        }
    </style>
</head>

<body>
    <table id="miTabla" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>Column 3</th>
                <th>Column 4</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <td>Data 3</td>
                <td>Data 4</td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- FixedHeader JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js">
    </script>
    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            $('#miTabla').DataTable({
                "columnDefs": [{
                        "width": "150px",
                        "targets": 0
                    }, // Set width for the first column
                    {
                        "width": "200px",
                        "targets": 1
                    } // Set width for the second column
                ],
                "autoWidth": false, // Disable automatic width adjustment
                "scrollX": true, // Enable horizontal scrolling
                "scrollY": "400px", // Enable vertical scrolling with 400px height
                "scrollCollapse": true, // Allow the table to reduce in height when fewer records are shown
                "paging": false, // Disable pagination
                fixedHeader: true // Enable fixed header
            });
        });
    </script>
</body>

</html>
