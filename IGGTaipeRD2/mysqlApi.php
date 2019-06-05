<?php
     function returnDataArray($BaseData,$sort,$FindName){//二微陣列中回傳含有字元的陣列
	           for($i=0;$i<count($BaseData);$i++){
				   if($BaseData[$i][$sort]==$FindName)return $BaseData[$i];
				   }
		   return  null;
	 }
     function returnDataCode( ){
	        return  date("Y-m-d-His",(time()+8*3600));
	 }
	 function returnTables($data_library ,$table_Name){ //取得資料表所有欄位名稱
		    $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
			$sql = "SELECT * from ".$table_Name;
			$result = mysql_query($sql, $db );
          // $result = mysql_query("SET NAMES 'utf8'");
		     $tables=array();
		      while($property = mysql_fetch_field($result)){
             //   echo  $property->name ;
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
     
   function  getMysqlArray($data_library,$SElectTable){
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
      function  getTableNames($all_num){
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
		      //  $all_num= getAll_num("calendardata");
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
	  
	  //Stmt
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
	   function MakeUpdateStmt(  $data_library,$table,$Base,$up,$WHEREtable,$WHEREData){
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