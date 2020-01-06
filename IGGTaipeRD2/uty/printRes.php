<?php
require_once dirname(dirname(__FILE__)) .'\PubApi.php';
                $tableName="fpresdata";
			    $data_library="iggtaiperd2";
				$MainPlanDataT=getMysqlDataArray($tableName); 
	            printBut();
				printType();
				
				function printBut(){
					echo "X";
					$x=20;
					$y=20;
					$back="printRes.php";
					$types=array("hero","mob","boss");
					 $color="#ffffff";
					for($i=0;$i<count($types);$i++){
						echo $types[$i];
					  	DrawLinkRect($types[$i],"10","#000000",$x+$i*100,$y,"60","14",$color,$back."?type=".$types[$i],1);
					}
			
			    
				}
				 
	  function printType(){
		  
		  global 	$MainPlanDataT,$type;
	      $MainPlanDataT2=filterArray($MainPlanDataT,0,$type); 
		  for($i=0;$i<count($MainPlanDataT2);$i++){
		      $msg=$MainPlanDataT2[$i][2]."_".$MainPlanDataT2[$i][3]."_".$MainPlanDataT2[$i][4];
			  echo $msg."</br>";
		  }
	  }
				
?>