<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>excel創建表格測試</title>
</head>

<body>
<?php
   if ($typ==""){;
      echo "<form id=form name=form method=post action=xls_mysql.php>";
      //隱藏數值
	  echo "<input type=hidden name=typ value=1>";
	  echo "<label><div align=center>";
      echo "<p>直接copy 貼上剪貼簿之excel表格(請從名稱..類別..開始圈選範圍)<a href=xls_mysql.jpg>範例</a><br />";
      echo "<textarea name=txt cols=90 rows=12></textarea></p>";
      echo "<p><label>";
      echo "<input type=submit name=Submit2 value=送出 />";
	  echo "<input type=hidden name=data_library value=$data_library >";
      echo "</label>";
      echo "</div></label></form>";
	  };
   if ($typ=="1"){;
      $row=explode("\n",$txt) ;
	  //表單名[0]格英文[1] 中文[2] 字數[3]
	  for ($i=0;$i<count($row);$i++){;
	      $line=explode("\t",$row[$i]) ;
	      for ($j=0;$j<count($line);$j++){;
		      $data[$i][$j]=trim($line[$j]);
			  };
	      }; 
		   $data_library=$data[0][1];
       echo "<p>表單名=".$data[0][0]."</p>";
	     echo "<p>資料庫名=".$data[0][1]."</p>";
       //echo "<p>表單英文=".$data[1][0]."</p>";
      // echo "<p>表單文字數=".$data[2][0]."</p>";
	  // echo "<p>表單中文=".$data[3][0]."</p>";
	   //創建表格

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
      //輸入資料庫
	   echo $stmt;
       $db = mysql_connect( "localhost","root","1406");
       mysql_select_db($data_library,$db);
       mysql_query("SET NAMES 'big5'");
	   $re = mysql_query($stmt,$db) ;
	   	  for ($i=3;$i<(count($row)-1);$i++){;
		      $stmt3=$stmt2.")VALUES(";
	          for ($j=0;$j<(count($data[0]));$j++){;
			       $stmt3=$stmt3."'".$data[$i][$j]."'";
	               if ($j!=(count($data[0])-1)){; 
				      $stmt3=$stmt3.",";
					  };
			      };
			   $stmt3=$stmt3.")";  
	          echo "<p>$stmt3</p>";
		      $re = mysql_query($stmt3,$db) ;
			  };
			  
	   };
?>
</body>
</html>
