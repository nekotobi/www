<?php //Base
     function returnDataArray($BaseData,$sort,$FindName){//二微陣列中回傳含有字元的陣列
	           for($i=0;$i<count($BaseData);$i++){
				   if($BaseData[$i][$sort]==$FindName)return $BaseData[$i];
				   }
		   return  null;
	 }
     function returnDataCode( ){
	        return  date("Y-m-d-His",(time()+8*3600));
	 }
     function returnTables_Bnk($data_library ,$tableName){ //取得資料表所有欄位名稱
		    $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
 
			$rescolumns = mysql_query("SHOW FULL COLUMNS FROM ".$tableName  ) ;
		    $tables=array();
 
		    while($row = mysql_fetch_array($rescolumns)){
			      array_push($tables,$row[‘Field’] );
				  
            }
			return $tables;
	 }
	 function returnTables($data_library ,$tableName){ //取得資料表所有欄位名稱
		    $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
			$sql = "SELECT * from ".$tableName;
			$result = mysql_query($sql, $db );
		    $tables=array();
			//echo (mysql_fetch_field($result));
		    while($property = mysql_fetch_field($result)){
			      array_push($tables, $property->name);
            }
			return $tables;
	 }
	 function SetMysqldefineData($data_library,$SElectTable,$tableName,$defineStr,$ForceCover){
	          $all_num= getDBAll_num( $data_library,$SElectTable);
		      $t=mysql_num_rows($all_num); 
			  $tableNames=getTableNames($all_num); 
			  for($i=0;$i<$t;$i++){
			      $define=mysql_result(  $all_num,$i,$tableName);
				  if( $define=="" or $ForceCover){
					 $Base=array($tableName);
					 $up=array($defineStr);
					 $t1=$tableNames[0];
					 $t2=$tableNames[1];
					 $WHEREtable=array( $t1 ,$t2);
					 $w1= mysql_result(  $all_num,$i,$t1);
                     $w2= mysql_result(  $all_num,$i,$t2);
					 $WHEREData=array( $w1, $w2);
		             $stmt=  MakeUpdateStmt(  $data_library, $SElectTable,$Base,$up,$WHEREtable,$WHEREData);
				     SendCommand($stmt,$data_library);
				  }
			  }
	 }
     function getlibraryTables($data_library){ //取得資料表格內所有table
	        $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
			$sql = "SHOW TABLES FROM $data_library";
			$result = mysql_query($sql);
			$tables=array();
			while ($row = mysql_fetch_row($result)) {
				   Array_Push($tables, $row[0] );
                 //  echo "</br>Table: {$row[0]}\n";
            }
			return $tables;
	}
     function getMysqlArray($data_library,$SElectTable){
	            $all_num= getDBAll_num( $data_library,$SElectTable);
				$fName=getTableNames($all_num);
				$returnData=array();
			    $t=mysql_num_rows($all_num); 
				for($i=0;$i<$t;$i++){
				    $data=array();
					for ($x=0 ;$x<count($fName);$x++){
						 $d=mysql_result(  $all_num,$i,$fName[$x]);
						 array_push($data,$d);
					}
					array_push($returnData,$data);
				}
				return $returnData;
	  }
     function getTableNames($all_num){
	            $fName=array();
				$fieldnum=mysql_num_fields( $all_num);
				for ($x=0 ;$x<$fieldnum;$x++)	array_push($fName, mysql_field_name($all_num,$x));
				return $fName;
	  }
     function ReSortSn( $data_library,$SElectTable){
	          $all_num=  getDBAll_num( $data_library,$SElectTable);
	          $t=mysql_num_rows($all_num); 
	          $lastSn=getLastSn($all_num,$t,"sn");
			  $tableNames=getTableNames($all_num);//getMysqlArray($SElectTable);
			  for($i=0;$i<$t;$i++){
			     $s=mysql_result(  $all_num,$i,'sn');
			      if($s==""){
				     $lastSn+=1 ;
					 $Base=array("sn");
					 $up=array($lastSn);
					 $t1=$tableNames[0];
					 $t2=$tableNames[1];
					 $WHEREtable=array( $t1 ,$t2);
					 $w1= mysql_result(  $all_num,$i,$t1);
                     $w2= mysql_result(  $all_num,$i,$t2);
					 $WHEREData=array( $w1, $w2);
		             $stmt=  MakeUpdateStmt(  $data_library, $SElectTable,$Base,$up,$WHEREtable,$WHEREData);
				     SendCommand($stmt,$data_library);
				   //  echo $stmt."</br>";
				   }
			   }
			  
	  }
	 function getDBLastSn( $data_library,$SElectTable,$table_sn){
	 
			   $all_num= getAll_num($SElectTable);
			   $t=mysql_num_rows($all_num); 
			   $sn=0;
			    for($i=0;$i<$t;$i++){
			        $s=	mysql_result(  $all_num,$i,$table_sn);
					//echo "[".$s."]";
					if($s>$sn)$sn=$s;
				}
				return ($sn+1); 
	  }
	 function getLastSn($all_num,$t,$tableName){
	           $lastSn=0;
			   for($i=0;$i<$t;$i++){
			      $s=mysql_result(  $all_num,$i,$tableName);
				  if($lastSn<$s)$s=$lastSn;
			   }
			   return $lastSn ;
	  }
	 function getDBAll_num( $data_library,$SElectTable){
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	  }
	 
?>

<?php //fastForm
     function upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray,$codeName="code",$data_type="data" ){
	 		  require_once ('PubApi.php');
	          $tables=returnTables($data_library ,$tableName); 
		      $WHEREtable=array( "data_type", $codeName );
		      $WHEREData=array( $data_type,$code);
			  $Base=array();
			  $up=array();
			  for($i=0;$i<count($tables);$i++){
			     array_push( $Base,$tables[$i]);
			     array_push( $up,$_POST[$tables[$i]]);
			  }
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			  // echo $stmt;
			   SendCommand($stmt,$data_library);	
	 }
     function DrawMysQLEdit($data_library,$tableName,$code,$URL,$PostArray,$title,$filterNum=1,$subStr="修改表單"){
		      require_once ('PubApi.php');
	          $tables=returnTables($data_library ,$tableName); 
			  $base=getMysqlArray($data_library,$tableName);
			  $data=filterArray($base,$filterNum,$code);
			//  echo ">".count($tables);
			  $x=100;
			  $y=100;
			  $w=300;
			  $fontSize=10;
			  $h=count($tables)*22+30;
		      DrawPopBG($x,$y,$w,$h,$title,$fontSize,$URL);
			  $y+=30;
			  $fontColor="#ffffff";
	          $upFormVal=array("EditTask","EditTask",$URL);
			  $UpHidenVal=array(array("code",$code));
			  $UpHidenVal=	addArray( $UpHidenVal,$PostArray);	
			  print_r($UpHidenVal);
			  $inputVal=array();
			  for($i=0;$i<count($tables);$i++){
				  $n=$tables[$i];
				  $v=$data[0][$i];
			      $tarr=array("text", $n, $n,8,$x,$y,200,20,$BgColor,$fontColor,$v,20);
				  array_push($inputVal,$tarr);
				  $y+=22;
			  }
			 $tarr=array("submit", "submit", "submit",8,$x,$y,200,20,$BgColor,$fontColor,$subStr,20);
			   	  array_push($inputVal,$tarr);
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
			  
	 }
	 function pubUpform(){
     	      if($_POST["submit"]!="修改表單") return;
			  global $data_library,$tableName,$URL,$PostArray ;
			  $code=$_POST["code"];
			  upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray );
		     ReLoad();
		 };
     function FastAddMysQLData($data_library,$tableName,$code,$URL,$sendVal){
	          require_once ('PubApi.php');
	          $tables=returnTables($data_library ,$tableName); 
			  $WHEREtable=$tables;
			  //print_r($sendVal);
			  $WHEREData=array();
			  for($i=0;$i<count($WHEREtable);$i++){
				  array_push($WHEREData,$sendVal[ $WHEREtable[$i]]);
			  }
			  
			  $stmt= MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
			  //echo $stmt;
			   SendCommand($stmt,$data_library);
	 }
     function FastAddMysQLDataV2($data_library,$tableName,$URL,$sendVal){
	          require_once ('PubApi.php');
	          $tables=returnTables($data_library ,$tableName); 
			  $WHEREtable=$tables;
			  //print_r($sendVal);
			  $WHEREData=array();
			  echo $sendVal["datatype"];
			  
			  for($i=0;$i<count($WHEREtable);$i++){
			 
				  array_push($WHEREData,$sendVal[$WHEREtable[$i]]);
			  }
			  echo "xx";
			  $stmt= MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
		      echo $stmt;
			   SendCommand($stmt,$data_library);
	 }
?>

<?php //Stmt
     function   ClearAllData($data_library,$tableName){
		        $stmt= 'TRUNCATE TABLE '.$tableName.';';
				SendCommand($stmt,$data_library);
	 }
	 function mergeTableData($data_library,$mergeTable,$joinTables){
              ClearAllData($data_library,$mergeTable);
			  for ($i=0;$i<count($joinTables);$i++){
				 // echo $joinTables[$i];
			      JoinTableData($data_library,$mergeTable,$joinTables[$i]);
			     }
     }
     function   JoinTableData($data_library,$mergeTable,$joinTable){
	            $stmt= "INSERT INTO `".$data_library."`.`".$mergeTable."` SELECT * FROM `".$data_library."`.`".$joinTable."`;";
				// echo $stmt;
				SendCommand($stmt,$data_library);
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
	 function MakeNewStmtv2($tableName,$WHEREtable,$WHEREData){
	      $stmt="INSERT INTO `".$tableName."` (";
	      for($i=0;$i<count($WHEREtable);$i++){
			   $stmt=$stmt.$WHEREtable[$i];
			   	if($i!=(count($WHEREtable)-1)) $stmt=$stmt.",";
		   }
            $stmt=$stmt.") VALUES (";
		   for($i=0;$i<count($WHEREtable);$i++){
			    $stmt=$stmt."'".$WHEREData[$i]."'";
			  if($i!=(count($WHEREData)-1)) $stmt=$stmt.",";
		   }
		   $stmt=$stmt.");";
          return $stmt;
	  }
     function MakeDeleteStmt($table,$WHEREtable,$WHEREData){
		     $stmt= "DELETE FROM ".$table;
	         $stmt=$stmt." WHERE ";
		     for($i=0;$i<count($WHEREtable);$i++){
			     if($i!=0)$stmt=$stmt." AND ";
			       $stmt=$stmt." CONVERT( `".$table."`.`".$WHEREtable[$i]."` USING utf8 ) = '".$WHEREData[$i]."' ";
		     }
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
?>

<?php //Old
     function MakeUpdateStmt($data_library,$table,$Base,$up,$WHEREtable,$WHEREData){
	       $stmt="UPDATE `".$data_library."`.`".$table."` SET ";
           for($i=0;$i<count($Base);$i++){
		         $stmt=$stmt." `".$Base[$i]."` = '".$up[$i]."'";
				 if($i!=(count($Base)-1)) $stmt=$stmt.",";
		   }
		   $stmt=$stmt." WHERE ";
		   for($i=0;$i<count($WHEREtable);$i++){
			   if($i!=0)$stmt=$stmt." AND ";
			     $stmt=$stmt." CONVERT( `".$table."`.`".$WHEREtable[$i]."` USING utf8 ) = '".$WHEREData[$i]."' ";
		   }
		   $stmt=$stmt."  LIMIT 1 ";
	    return $stmt;
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
?>

<?php //pic

?>