<?php
	         require_once dirname(dirname(__FILE__)) .'/PubApi.php';
			 require_once dirname(dirname(__FILE__)) .'/mysqlApi.php';
			 getOutsData();
		     getcount();
        
		
?>
<?php //count 
    function getcount(){
			 global  $outsBaseData,$outsBaseSelects;
		     $data_library="iggtaiperd2"; 
             $outscost=getMysqlDataArray("fpoutsourcingcost");
			 $outscos=filterArray($outscost,0,"cost");
			  echo  $outscos[0][15];
			 for($i=0;$i<count($outsBaseData);$i++){
			    $c= getOutcount($outsBaseData[$i],$outscos);
				
				echo "</br>".$outsBaseData[$i][0].".".$outsBaseData[$i][2]."-".$c;
			 }
			 
	}
	function getOutcount($user,$outscos){
		     		
			 $c=0;
			 
	         for($i=0;$i<count($outscos);$i++){
		
				 if($outscos[$i][15]==$user[1])$c++;
			 }
			 return $c;
	}
	function getOutsData(){
		     global  $outsBaseData,$outsBaseSelects;
			 $outsT= getMysqlDataArray("outsourcing"); 
			 $outs=filterArray($outsT,0,"data");
			 $outsBaseData=array();
			 $outsBaseSelects=array();
			 for($i=0;$i<count($outs);$i++){
				 $name=$outs[$i][15];
				 if($name!=$outs[$i][16])$name=$name."(".$outs[$i][16].")";
			     $tmp=array($outs[$i][17],$outs[$i][1],$name);
				 $sel= $outs[$i][17]."-".$name;
				 array_push($outsBaseData,$tmp);
				 array_push($outsBaseSelects,$sel);
			 }
	}

?>


<?php //code
    function codeTmp(){
           	for($i=2;$i<count($outscost);$i++){
			// for($i=3;$i<4;$i++){
				 echo "</br>";
				 $code= getcode($outscost[$i][7],$outs);
				 if($code!="") UpData($code,$outscost[$i][1],"fpoutsourcingcost");
		    	 echo $outscost[$i][7]."_".$code;
			 }
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