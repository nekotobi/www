<script type="text/javascript">
  var data_library="localhost"
  var mysql   = require(data_library);
  var database="artindextype"
  var connection = mysql.createConnection({
     host     : 'localhost',
     user     : 'root',
     password : '1406',
     database : database
    });
  connection.connect();
  connection.query('SELECT 1 + 1 AS solution', function (error, results, fields) {
  if (error) throw error;
  console.log('The solution is: ', results[0].solution);
});
</script>