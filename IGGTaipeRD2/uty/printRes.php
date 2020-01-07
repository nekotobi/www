
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作分類排程區</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php
require_once dirname(dirname(__FILE__)) .'\PubApi.php';
                $tableName="fpresdata";
			    $data_library="iggtaiperd2";
				$MainPlanDataT=getMysqlDataArray($tableName); 
				setcookies($CookieArray,$BaseURL);
	            SetGlobalcookieData( $CookieArray);
	            printBut();
				printType();
			    
				function printBut(){
					//echo "X";
					$x=20;
					$y=20;
					$back="printRes.php";
					$types=array("hero","mob","boss");
					$color="#ffffff";
				
					for($i=0;$i<count($types);$i++){
						$ValArray=array("type",$types[$i]);
					    $Rect=array($x+$i*100,$y,"60","14");
						sendVal($back,$ValArray,"type",$types[$i],$Rect);
						 DrawLinkRect2sendVal($types[$i],"10","#000000",$x+$i*100,$y,"60","14",$color,$back."?type=".$types[$i],1);
					  	//DrawLinkRect($types[$i],"10","#000000",$x+$i*100,$y,"60","14",$color,$back."?type=".$types[$i],1);
					}
				}
				 
	  function printType(){
 
		  echo "</br></br></br>";
		  global 	$MainPlanDataT;
		  $type=$_POST["type"];
	      $MainPlanDataT2=filterArray($MainPlanDataT,0,$type); 
		  for($i=0;$i<count($MainPlanDataT2);$i++){
		      $msg=$MainPlanDataT2[$i][2]."_".$MainPlanDataT2[$i][3]."_".$MainPlanDataT2[$i][4];
			  echo $msg."</br>";
		  }
	  }
				
?>