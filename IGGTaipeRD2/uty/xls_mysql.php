<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>excel�Ыت�����</title>
</head>

<body>
<?php
   if ($typ==""){;
      echo "<form id=form name=form method=post action=xls_mysql.php>";
      //���üƭ�
	  echo "<input type=hidden name=typ value=1>";
	  echo "<label><div align=center>";
      echo "<p>����copy �K�W�ŶKï��excel���(�бq�W��..���O..�}�l���d��)<a href=xls_mysql.jpg>�d��</a><br />";
      echo "<textarea name=txt cols=90 rows=12></textarea></p>";
      echo "<p><label>";
      echo "<input type=submit name=Submit2 value=�e�X />";
	  echo "<input type=hidden name=data_library value=$data_library >";
      echo "</label>";
      echo "</div></label></form>";
	  };
   if ($typ=="1"){;
      $row=explode("\n",$txt) ;
	  //���W[0]��^��[1] ����[2] �r��[3]
	  for ($i=0;$i<count($row);$i++){;
	      $line=explode("\t",$row[$i]) ;
	      for ($j=0;$j<count($line);$j++){;
		      $data[$i][$j]=trim($line[$j]);
			  };
	      }; 
		   $data_library=$data[0][1];
       echo "<p>���W=".$data[0][0]."</p>";
	     echo "<p>��Ʈw�W=".$data[0][1]."</p>";
       //echo "<p>���^��=".$data[1][0]."</p>";
      // echo "<p>����r��=".$data[2][0]."</p>";
	  // echo "<p>��椤��=".$data[3][0]."</p>";
	   //�Ыت��

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
      //��J��Ʈw
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
