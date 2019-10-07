<?php
	         require_once dirname(dirname(__FILE__)) .'/PubApi.php';
			 require_once dirname(dirname(__FILE__)) .'/mysqlApi.php';
			 $data_library="iggtaiperd2"; 
             $outscost=getMysqlDataArray("fpoutsourcingcost");
			 $outs=getMysqlDataArray("outsourcing");
              for($i=2;$i<count($outscost);$i++){
			// for($i=3;$i<4;$i++){
				 echo "</br>";
				 $code= getcode($outscost[$i][7],$outs);
				 if($code!="") UpData($code,$outscost[$i][1],"fpoutsourcingcost");
		    	 echo $outscost[$i][7]."_".$code;
			 }
			function getcode($name,$outs){
			     for($i=0;$i<count($outs);$i++){
					  //echo $name."-".$outs[$i][16];
				     if($outs[$i][16]==$name)return $outs[$i][1];
				  }
			}
         	 function UpData($code,$sn,$tableName){
		              global $data_library;
		         	  $WHEREtable=array( "data_type", "sn" );
		              $WHEREData=array( "cost",$sn);
			          $Base=array("outcode");
			          $up=array($code);
			          $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                      SendCommand($stmt,$data_library);			   
	          }
?>