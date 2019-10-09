<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增excel資料到mysql</title>
</head>

<body bgcolor="#b5c4b1">
<?php //主控台
     // include('PubApi.php');
 
	  require_once  dirname(dirname(__FILE__))  .'\PubApi.php';
	  require_once('xls2mysqlApi.php');
      input();
      updata();
?>


<?php
   function input(){
	   global $submit;
      if ($submit!="")return;
          echo "<form id=form name=form method=post action=xls2mysql.php>";
	      echo "<input type=hidden name=typ value=1>"; //隱藏數值
	      echo "<label><div align=center>";
          echo "<p>直接copy 貼上剪貼簿之excel表格(請從名稱..類別..開始圈選範圍)<a href=xls_mysql.jpg>範例</a><br />";
          echo "<textarea name=txt cols=90 rows=12></textarea></p>";
          echo "<p><label>";
          echo "<input type=submit name=submit value=新增全新表格 />";
		  echo "<input type=submit name=submit value=追加內容 />";
		  echo "<input type=submit name=submit value=修改內容 />";
	      echo "<input type=hidden name=data_library value=$data_library >";
          echo "</label>";
          echo "</div></label></form>";
   }
   function updata(){
	   global $submit;
       if ($submit=="")return; 
	    $data=getTxtArray();
	   if($submit=="新增全新表格") newTable( $data);
	   if($submit=="追加內容") AddTable( $data); 
	   if($submit=="修改內容") changedata($data);
   }
   function newTable( $data){
           $data_library=$data[0][1];
           echo "<p>表單名=".$data[0][0]."</p>";
	       echo "<p>資料庫名=".$data[0][1]."</p>";
		   $stmt= "CREATE TABLE  `".$data[0][0]."` (";
	       $stmt2=" INSERT INTO  `".$data[0][0]."` (";
	       for ($j=0;$j<count($data[0]);$j++){;
	             $stmt=$stmt."`".$data[1][$j]."`  VARCHAR( ".$data[2][$j]." ) NOT NULL ";
				 $stmt2=$stmt2."`".$data[1][$j]."`";
				 if ($j!=(count($data[0])-1)){; 
				    $stmt=$stmt.",";
					$stmt2=$stmt2.",";
					};
				 };
	       $stmt=$stmt." ) ENGINE = MYISAM ";
		   $db = mysql_connect( "localhost","root","1406");
           mysql_select_db($data_library,$db);
           mysql_query("SET NAMES 'big5'");
	       $re = mysql_query($stmt,$db) ;
		   
   }
 
?>
<?php //pubapi
/*
  	 function returnTables($data_library ,$tableName){ //取得資料表所有欄位名稱
		    $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
			$sql = "SELECT * from ".$tableName;
			$result = mysql_query($sql, $db );
		    $tables=array();
		    while($property = mysql_fetch_field($result)){
			      array_push($tables, $property->name);
            }
			return $tables;
	 }
 	 function MakeNewStmt( $data_library,$table,$WHEREtable,$WHEREData){
	      $stmt="INSERT INTO `".$data_library."`.`".$table."` (";
	      for($i=0;$i<count($WHEREtable);$i++){
			   $stmt=$stmt.$WHEREtable[$i];
			   	if($i!=(count($WHEREtable)-1)) $stmt=$stmt.",";
		   }
            $stmt=$stmt.")VALUES (";
		   for($i=0;$i<count($WHEREtable);$i++){
			    $stmt=$stmt."'".$WHEREData[$i]."'";
			  if($i!=(count($WHEREData)-1)) $stmt=$stmt.",";
		   }
		   $stmt=$stmt.");";
          return $stmt;
	  }
	 function SendCommand($stmt,$data_library){
	  	  $db = mysql_connect("localhost","root","1406");
                mysql_select_db( $data_library,$db);
                mysql_query("SET NAMES 'UTF8'");
		  $re = mysql_query($stmt,$db) ;	
		  echo ">".$re;
		 // echo $stmt;
	  }
     function MakeUpdateStmtv2($tableName,$Base,$up,$WHEREtable,$WHEREData){
	       $stmt="UPDATE `".$tableName."` SET ";
           for($i=0;$i<count($Base);$i++){
		         $stmt=$stmt." `".$Base[$i]."` = '".$up[$i]."'";
				 if($i!=(count($Base)-1)) $stmt=$stmt.",";
		   }
		   $stmt=$stmt." WHERE ";
		   for($i=0;$i<count($WHEREtable);$i++){
			   if($i!=0)$stmt=$stmt." AND ";
			     $stmt=$stmt." CONVERT( `".$tableName."`.`".$WHEREtable[$i]."` USING utf8 ) = '".$WHEREData[$i]."' ";
		   }
		   $stmt=$stmt." LIMIT 1 ;";
	    return $stmt;
	  }
*/
?>
</body>
</html>