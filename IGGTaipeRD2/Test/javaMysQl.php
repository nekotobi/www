<script type="text/javascript">
		
 

//alert('測試文字');
 
  var data_library="localhost";
  var mysql   = require(data_library);
  var database="artindextype";
  	  alert('測試文字');
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
</script>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>javatest</title>
</head>
<body> 


</body>


 
 <?php
 echo "php12";
 ?>
