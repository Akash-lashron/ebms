<!DOCTYPE html>
<html>
  <head>
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>

    <link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
    <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>

    <meta charset=utf-8 />
    <title>DataTables - JS Bin</title>
  </head>
  <body>
    <div class="container">
      <table id="example" class="display" width="100%">
      </table>
    </div>
  </body>
</html>
<style>
body {
  font: 90%/1.45em "Helvetica Neue", HelveticaNeue, Verdana, Arial, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
  color: #333;
  background-color: #fff;
}


div.container {
  min-width: 980px;
  margin: 0 auto;
}
</style>
<script>
$(document).ready( function () {
  var data = [
    ['subgroupN', 'Group1', 'sub-subgroupN', 'ElementN', '2Element N'],
    ['subgroup1', 'Group2', 'sub-subgroup1', 'Element1', '2Element 1'],
    ['subgroup2', 'Group2', 'sub-subgroup1', 'Element1', '2Element 1'],
    ['subgroup2', 'Group2', 'sub-subgroup1', 'Element2', '2Element 2'],
    ['subgroup2', 'Group2', 'sub-subgroup2', 'Element3', '2Element 2'],
    ['subgroup2', 'Group2', 'sub-subgroup2', 'Element4', '2Element 4'],
    ['subgroup2', 'Group2', 'sub-subgroup2', 'Element2', '2Element 2'],
    ['subgroup3', 'Group1', 'sub-subgroup1', 'Element1', '2Element 1'],
    ['subgroup3', 'Group1', 'sub-subgroup1', 'Element1', '2Element 1'],
    ['subgroup2', 'Group2', 'sub-subgroup2', 'Element1', '2Element 1'],
    ['subgroup4', 'Group2', 'sub-subgroup2', 'Element1', '2Element 1'],
    ['subgroup4', 'Group2', 'sub-subgroup3', 'Element10', '2Element 17'],
    ['subgroup4', 'Group2', 'sub-subgroup3', 'Element231', '2Element 211'],

  ];
  var table = $('#example').DataTable({
    columns: [
        {   name: 'First',
            title: 'First group',
        },
        {
            name: 'second',
            title: 'Second group [order first]',
        },
        {
            name: 'third',
            title: 'Third group',
        },
        {   name:'fouth',
            title: 'Forth ungrouped',
        },
        {   name:'Fifth',
            title: 'Fifth ungrouped',
        },
    ],
    data: data,
    rowsGroup: [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
                // (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
      
      //'First:name',
      //'second:name',
      //'third:name',
      'fouth:name',
      'Fifth:name'
    ],
    pageLength: '20',
    });
} );

</script>