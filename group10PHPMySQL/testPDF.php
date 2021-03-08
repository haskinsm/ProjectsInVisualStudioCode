<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
     <style type="text/css">
        body
        {
            font-family: Arial;
            font-size: 10pt;
        }
        table
        {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }
        table th
        {
            background-color: #F7F7F7;
            color: #333;
            font-weight: bold;
        }
        table th, table td
        {
            padding: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
     <table id="tblCustomers" cellspacing="0" cellpadding="0">
        <tr>
            <th>Customer Id</th>
            <th>Name</th>
            <th>Country</th>
        </tr>
        <tr>
            <td>1</td>
            <td>John Hammond</td>
            <td>United States</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Mudassar Khan</td>
            <td>India</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Suzanne Mathews</td>
            <td>France</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Robert Schidner</td>
            <td>Russia</td>
        </tr>
    </table>
    <br />
    <input type="button" id="btnExport" value="Export" onclick="Export()" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script type="text/javascript">
        function Export() {
            html2canvas(document.getElementById('tblCustomers'), {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Table.pdf");
                }
            });
        }
    </script>
</body>
</html>