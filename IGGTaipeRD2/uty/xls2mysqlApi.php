<?php
   function changedata($data){
	        $data_library=$data[0][1];
			$tableName=$data[0][0];
	        $WHEREtable=array( $data[1][0], $data[1][1]);
			$Base=array();
			for($i=2;$i<count($data[1]);$i++){
			    array_push( $Base,$data[1][$i]);
			}
 
			for($i=2;$i<count($data);$i++){ 
		        $WHEREData=array( $data[$i][0], $data[$i][1] );
				$up=array();
			 	for($j=2;$j<count($data[$i]);$j++){
			         array_push( $up,$data[$i][$j]);
		        	}
			    $stmt= MakeUpdateStmtv2 ($tableName,$Base,$up,$WHEREtable,$WHEREData);
				 echo "</br>".$stmt;
		         SendCommand($stmt,$data_library);
			}
   }
   function AddTable($data){
	   		$data_library=$data[0][1];
			$tableName=$data[0][0];
            $tables=returnTables($data_library ,$tableName);
            $WHEREtable=array();
		    for($i=0;$i<count($tables);$i++){
		         array_push($WHEREtable,$tables[$i]);
			}
			$upTables=array();
		    for($i=0;$i<count($data[1]);$i++){
				 array_push( $upTables,$data[1][$i]);
			}
		    for($i=2;$i<count($data);$i++){
				$WHEREData= returnWhereData($data[$i],$upTables,$tables);
				 $stmt= MakeNewStmt( $data_library,$tableName,$WHEREtable,$WHEREData); 
				   SendCommand($stmt,$data_library);		
			    echo  $stmt;
			}
		
   }
   function returnWhereData($base,$upTables,$tables){
	        $rarray=array();
	        for($i=0;$i<count($tables);$i++){
				$up="";
				echo $tables[$i];
				for($j=0;$j<count($upTables);$j++){
				     if($tables[$i]==$upTables[$j])$up=$base[$j];
				}
				array_push($rarray,$up);
			}
			return $rarray;
   }
   function getTxtArray(){
           global $txt;
		   $data=array();
		   $row=explode("\n",$txt) ;//表單名[0]格英文[1] 中文[2] 字數[3]
	       for ($i=0;$i<count($row);$i++){ 
				$line=explode("\t",$row[$i])  ;
			    for ($j=0;$j<count($line);$j++){ 
		             $data[$i][$j]=trim($line[$j]) ;
			         } 
			}
			return $data;
  }

?>