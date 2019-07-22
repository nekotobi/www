
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>排程</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php  //主控台
      $id=$_COOKIE['IGG_id'];
      include('PubApi.php');
      include('CalendarApi.php');  
      include('mysqlApi.php');
      include('scheduleApi.php');
	  defineDataSF();
	  DrawType_v2();
      //DrawTypeCell();
    //  DrawListData();
?>		
<?php  //主資料
      function  defineDataSF(){
		  	    global $data_library,$tableName,$MainPlanData,$PlaneCodes;
				global  $typeCell,$typeSize;
				$tableName="fpschedule";
			    $data_library="iggtaiperd2";
			    $MainPlanDataT=getMysqlDataArray($tableName); 
				$MainPlanData=filterArray($MainPlanDataT,0,"data"); 
			    $PlaneCodest=filterArray($MainPlanDataT,4,"工項");
				$PlaneCodes=sortMainPlaneCode($PlaneCodest);
				$typeCellt=filterArray($MainPlanDataT,0,"name"); 
				$typeCell=$typeCellt[0];
				$typeSizet=filterArray($MainPlanDataT,0,"size"); 
				$typeSize=$typeSizet[0];
				//分類
			    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			    $BaseURL="scheduleform.php";

             
      }
	  function  DrawType_v2(){
	              global   $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
				  $sTypeTmp= getMysqlDataArray("scheduletype");
				  $SelectType_2tmp= filterArray($sTypeTmp ,0,"data2");
				  $SelectType_2=   returnArraybySort($SelectType_2tmp,2);
	   	          $y= 10;
			      $x=20;
				  if($Stype_2=="")$Stype_2=0;
	  			  for ($i=0;$i<count($SelectType_2);$i++){
			           $BackURL2= $BaseURL."?List=CheckState&Stype_2=".$i;
				       $msg=" ".$SelectType_2[$i];
				       $color= "#222222";
				       if($Stype_2==$i and  $Stype_2!="")$color= "#cc2212";
			           DrawLinkRect($msg,"10","#ffffff",$x,$y,"60","17",$color,$BackURL2,1);
				       $x+=66;
			     }
		 
	  }

	  
	  
	  
	  function DrawListData(){
	           global $ListPlans;
			   echo "</br>";
			   getTypeOrder();
	  }
?>
<?php //陣列
      function getTypeOrder(){
		       global   $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
	           global  $MainPlanData,$PlaneCodes;
			   $typeName= trim($SelectType_2[$Stype_2]);
	           $fillerType=filterArray($MainPlanData,5, $typeName ); 
		       
               $x=20;
			   $y=80;
			   $BGcolor="#aaaaaa";
			   $color="#000000";
			  // return;
		       for ($i=0;$i<count($fillerType);$i++){
				    $x=20;
				    $codeA=returnDataArray( $MainPlanData,1,$fillerType[$i][3]);
				    sortArray( $codeA);
				    DrawLinkRect($codeA[3],"12",$color,$x,$y,140,22,$BGcolor,"",1);
					$x+=150;
					DrawLinkRect($fillerType[$i][9],"12",$color,$x,$y,200,22,"#ffffff","",1);
					$y+=30;
			   }
	  }
 	

?>
 
<?php //列印  
      function DrawTypeCell(){
	              global  $typeCell,$typeSize;
				  
	              $y= 50;
			      $x=20;
				  $color="#000000";
				  for ($i=3;$i<count($typeCell);$i++){
				       DrawLinkRect($typeCell[$i],"10","#ffffff",$x,$y,$typeSize[$i],17,$color,"",1);
					   $x+=$typeSize[$i]+5;
				  }
	  }
?>