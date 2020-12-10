<?php
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
	   }
	   function getAll_num($SElectTable){
		  $data_library="IGGTaipeRD2";
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	  }
	   function getMysqlDataArray($name){
	            $all_num= getAll_num( $name );
				$fieldnum=mysql_num_fields( $all_num);
				$fName=array();
				for ($x=0 ;$x<$fieldnum;$x++)	array_push($fName, mysql_field_name($all_num,$x));
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
	   function getDBAll_num( $data_library,$SElectTable){
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	   }
	   function getTableNames($all_num){
	            $fName=array();
				$fieldnum=mysql_num_fields( $all_num);
				for ($x=0 ;$x<$fieldnum;$x++)	array_push($fName, mysql_field_name($all_num,$x));
				return $fName;
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
	   function MakeNewStmt($tableName,$WHEREtable,$WHEREData){
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
	   function MAPI_returnTableColumnSort($data_library,$tableName,$ColumnName){ //回傳表格欄位序列
	           $tables= returnTables($data_library ,$tableName);
			   for($i=0;$i<count($tables);$i++){
			       if($tables[$i]==$ColumnName)return $i;
			   }
			   return 0;
	  }
	   function MAPI_DrawMysQLEdit($data_library,$tableName,$code,$URL,$PostArray,$title,$filterNum=1,$subStr="修改表單"){
		      require_once('/Apis/PubApi20.php');
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
			 // print_r($UpHidenVal);
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
       function MAPI_pubUpform(){
     	      if($_POST["submit"]!="修改表單") return;
			  global $data_library,$tableName,$URL,$PostArray ;
			  $code=$_POST["code"];
			  MAPI_upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray );
		     // JAPI_ReLoad($PostArray,$URL);
		      } 
	   function MAPI_upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray,$codeName="ECode",$data_type="data" ){
	 		    require_once('/Apis/PubApi20.php');
	            $tables=returnTables($data_library ,$tableName); 
		        $WHEREtable=array( "EData", $codeName );
		        $WHEREData=array( $data_type,$code);
			    $Base=array();
			    $up=array();
			    for($i=0;$i<count($tables);$i++){
			        array_push( $Base,$tables[$i]);
			        array_push( $up,$_POST[$tables[$i]]);
			     }
			    $stmt= MAPI_MakeUpdateStmt(  $tableName,$Base,$up,$WHEREtable,$WHEREData);
			   // echo $tableName."xx>".$stmt;
			    SendCommand($stmt,$data_library);	
	 }
 
?>

<?php
       function MAPI_MakeUpdateStmt($tableName,$Base,$up,$WHEREtable,$WHEREData){
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
     function APi_UpNewTask($data_library,$tableName){
              $tables=returnTables($data_library,$tableName);
		      $WHEREtable=array();
			  $WHEREData=array();
		      for($i=0;$i<count( $tables);$i++){
				  array_push($WHEREtable, $tables[$i] );
			      $data=$_POST[$tables[$i]];
			      array_push($WHEREData,$data);
			      //echo  "</br>".$tables[$i].">".$_POST[$tables[$i]]."]";
		         }
			  $stmt=  MakeNewStmt($tableName,$WHEREtable,$WHEREData);
			  SendCommand($stmt,$data_library);
			  // ReLoad();
     }
?>